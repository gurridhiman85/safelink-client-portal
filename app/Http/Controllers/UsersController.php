<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Library\Ajax;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use \Illuminate\Support\Facades\View as View;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return view('users.index');
    }

    public function getUsers(Request $request,Ajax $ajax){
        $tabid = $request->input('tabid');
        $filters = $request->input('filters',[]);
        $page = $request->input('page',1);
        $records_per_page = 15;
        $position = ($page-1) * $records_per_page;
        $rType = $request->input('rtype','');

        $query = User::query();
        Helper::applyFilters($filters, $query, 'users');
        $records = $query->skip($position)->take($records_per_page)->get();

        $tquery = User::query();
        Helper::applyFilters($filters, $tquery, 'users');
        $total_records = $tquery->count();
        $tabName = 'Completed';

        $data = [
            'records' => $records,
            'tab' => $tabName,
            'filters' => json_encode($filters)
        ];

        if($rType == 'pagination'){
            $html = View::make('users.tabs.all.table',$data)->render();
        }else {
            $html = View::make('users.tabs.all.index', $data)->render();
        }

        $paginationhtml = View::make('users.tabs.all.pagination-html',[
            'total_records' => $total_records,
            'records' => $records,
            'position' => $position,
            'records_per_page' => $records_per_page,
            'page' => $page
        ])->render();

        return $ajax->success()
            ->appendParam('records',$records)
            ->appendParam('total_records',$total_records)
            ->appendParam('html',$html)
            ->appendParam('paginationHtml',$paginationhtml)
            ->jscallback('load_ajax_tab')
            ->response();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function UserForm($rec_id, Ajax $ajax)
    {
        $title = 'Add User';
        $data = [
            'is_create' => true
        ];
        if($rec_id != '0'){
            $record = User::find($rec_id);
            $data = [
                'record' => $record,
                'is_create' => false
            ];
            $title = 'Edit User';
        }

        $content = View::make('users.form.add', $data)->render();

        $sdata = [
            'content' => $content
        ];

        $size = 'modal-md';

        if (isset($title)) {
            $sdata['title'] = $title;
        }
        if (isset($size)) {
            $sdata['size'] = $size;
        }

        $view = View::make('layouts.modal-popup-layout', $sdata);
        $html = $view->render();

        return $ajax->success()
            ->appendParam('html', $html)
            ->jscallback('loadModalLayout')->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeUser(Request $request, Ajax $ajax)
    {
        $rules = [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users,email|email:rfc,dns',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required',
        ];

        if($request->input('rec_id') != '0'){
            $rules['email'] = 'required|email|unique:users,email,'.$request->input('rec_id');
            if(!empty($request->input('password')))
                $rules['password'] = 'required|string|min:8|confirmed';
            else
                unset($rules['password']);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }
        $id = $request->input('rec_id');
        if($request->input('rec_id') != '0'){
            $user = User::find($id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->user_type = $request->input('user_type');
            if(!empty(trim($request->input('password')))){
                $user->password = bcrypt($request->input('password'));
            }
            $user->save();

            $msg = 'updated';
        }else{
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'user_type' => $request->input('user_type')
            ]);
            $msg = 'created';
        }

        return $ajax->success()
            ->message('User ' . $msg . ' successfully')
            ->jscallback('ajax_user_load')
            ->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($rec_id, Ajax $ajax)
    {

        $user = User::find($rec_id);
        $user->delete();

        return $ajax->success()
            ->message('User deleted successfully')
            ->jscallback('ajax_user_load')
            ->response();
    }
}
