<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Helpers\Helper;
use App\Library\Ajax;
use App\Models\Attachment;
use App\Models\SubscriptionTerm;
use Illuminate\Http\Request;
use Validator;
use Auth;
use \Illuminate\Support\Facades\View as View;

class SubscriptionTermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return view('subscription_terms.index');
    }

    public function getSubscriptionTerms(Request $request,Ajax $ajax){
        $tabid = $request->input('tabid');
        $filters = $request->input('filters',[]);
        $page = $request->input('page',1);
        $records_per_page = 15;
        $position = ($page-1) * $records_per_page;
        $rType = $request->input('rtype','');

        $query = SubscriptionTerm::query();
        Helper::applyFilters($filters, $query, 'subscription_terms');
        $records = $query->skip($position)->take($records_per_page)->get();

        $tquery = SubscriptionTerm::query();
        Helper::applyFilters($filters, $tquery, 'subscription_terms');
        $total_records = $tquery->count();
        $tabName = 'Completed';

        $data = [
            'records' => $records,
            'tab' => $tabName,
            'filters' => json_encode($filters)
        ];

        if($rType == 'pagination'){
            $html = View::make('subscription_terms.tabs.all.table',$data)->render();
        }else {
            $html = View::make('subscription_terms.tabs.all.index', $data)->render();
        }

        $paginationhtml = View::make('subscription_terms.tabs.all.pagination-html',[
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
    public function SubscriptionTermForm($rec_id, Ajax $ajax)
    {
        $title = 'Add Subscription Term';
        $data = [
            'is_create' => true
        ];
        if($rec_id != '0'){
            $record = SubscriptionTerm::find($rec_id);
            $data = [
                'record' => $record,
                'is_create' => false
            ];
            $title = 'Edit Subscription Term';
        }

        $content = View::make('subscription_terms.form.add', $data)->render();

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
    public function storeSubscriptionTerm(Request $request, Ajax $ajax)
    {
        $rules = [
            'Name' => 'required|min:3|max:50',
            'Term' => 'required|numeric|gt:0'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }
        $id = $request->input('rec_id');
        if($request->input('rec_id') != '0'){
            $subscription_term = SubscriptionTerm::find($id);
            $subscription_term->Name = $request->input('Name');
            $subscription_term->Term = $request->input('Term');
            $subscription_term->save();

            $msg = 'updated';
        }else{
            SubscriptionTerm::create([
                'Name' => $request->input('Name'),
                'Term' => $request->input('Term')
            ]);
            $msg = 'created';
        }

        return $ajax->success()
            ->message('Subscription Term ' . $msg . ' successfully')
            ->jscallback('ajax_subscription_term_load')
            ->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($rec_id, Ajax $ajax)
    {

        $subscription_term = SubscriptionTerm::find($rec_id);
        $subscription_term->delete();

        return $ajax->success()
            ->message('Subscription Term deleted successfully')
            ->jscallback('ajax_subscription_term_load')
            ->response();
    }
}
