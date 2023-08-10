<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Helpers\Helper;
use App\Library\Ajax;
use App\Models\Attachment;
use App\Models\BillingProfile;
use App\Models\MasterProduct;
use App\Models\Order;
use Illuminate\Http\Request;
use Validator;
use Auth;
use \Illuminate\Support\Facades\View as View;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return view('billings.index');
    }

    public function getBilling(Request $request,Ajax $ajax){
        $tabid = $request->input('tabid');
        $filters = $request->input('filters',[]);
        $page = $request->input('page',1);
        $records_per_page = 15;
        $position = ($page-1) * $records_per_page;
        $rType = $request->input('rtype','');

        $query = Order::query()->with(['order_details', 'billing_profile']);
        Helper::applyFilters($filters, $query, 'billings');
        $records = $query->where('user_id', Auth::id())->orderByDesc('id')->skip($position)->take($records_per_page)->get();

        $tquery = Order::query();
        Helper::applyFilters($filters, $tquery, 'billings');
        $total_records = $tquery->where('user_id', Auth::id())->count();
        $tabName = 'Completed';

        $data = [
            'records' => $records,
            'tab' => $tabName,
            'filters' => json_encode($filters)
        ];

        if($rType == 'pagination'){
            $html = View::make('billings.tabs.all.table',$data)->render();
        }else {
            $html = View::make('billings.tabs.all.index', $data)->render();
        }

        $paginationhtml = View::make('billings.tabs.all.pagination-html',[
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
    public function BillingForm($rec_id, Ajax $ajax)
    {
        $title = 'Add Billing';
        $data = [
            'is_create' => true
        ];
        if($rec_id != '0'){
            $record = MasterProduct::find($rec_id);
            $data = [
                'record' => $record,
                'is_create' => false
            ];
            $title = 'Edit Billing';
        }

        $content = View::make('billings.form.add', $data)->render();

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
    public function storeBilling(Request $request, Ajax $ajax)
    {
        $rules = [
            'company_name' => 'required|min:3|max:50',
            'billing_email' => 'required|email|unique:billing_profiles,billing_email|email:rfc,dns',
            'first_name' => 'required|min:3|max:50',
            //'last_name' => 'required|min:3|max:50',
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
            $billing_profile = BillingProfile::find($id);
            $billing_profile->user_id = Auth::id();
            $billing_profile->company_name = $request->input('company_name');
            $billing_profile->billing_email = $request->input('billing_email');
            $billing_profile->first_name = $request->input('first_name');
            $billing_profile->last_name = $request->input('last_name');
            $billing_profile->save();

            $msg = 'updated';
        }else{
            $insertedData = BillingProfile::create([
                'user_id' => Auth::id(),
                'company_name' => $request->input('company_name'),
                'billing_email' => $request->input('billing_email'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name')
            ]);
            $id = $insertedData['id'];
            $msg = 'created';
        }

        return $ajax->success()
            ->message('Billing profile ' . $msg . ' successfully')
            ->jscallback('ajax_billing_load')
            ->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($rec_id, Ajax $ajax)
    {

        $order = BillingProfile::find($rec_id);


        $order->delete();

        return $ajax->success()
            ->message('Billing profile deleted successfully')
            ->jscallback('ajax_billing_load')
            ->response();
    }
}
