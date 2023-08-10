<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Helpers\Helper;
use App\Library\Ajax;
use App\Models\Attachment;
use App\Models\MasterProduct;
use Illuminate\Http\Request;
use Validator;
use Auth;
use \Illuminate\Support\Facades\View as View;

class MasterProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return view('master_products.index');
    }

    public function getMasterProduct(Request $request,Ajax $ajax){
        $tabid = $request->input('tabid');
        $filters = $request->input('filters',[]);
        $page = $request->input('page',1);
        $records_per_page = 15;
        $position = ($page-1) * $records_per_page;
        $rType = $request->input('rtype','');

        $query = MasterProduct::query()->with(['attachment']);
        Helper::applyFilters($filters, $query, 'master_products');
        $records = $query->skip($position)->take($records_per_page)->get();

        $tquery = MasterProduct::query();
        Helper::applyFilters($filters, $tquery, 'master_products');
        $total_records = $tquery->count();
        $tabName = 'Completed';

        $data = [
            'records' => $records,
            'tab' => $tabName,
            'filters' => json_encode($filters)
        ];

        if($rType == 'pagination'){
            $html = View::make('master_products.tabs.all.table',$data)->render();
        }else {
            $html = View::make('master_products.tabs.all.index', $data)->render();
        }

        $paginationhtml = View::make('master_products.tabs.all.pagination-html',[
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
    public function masterProductForm($rec_id, Ajax $ajax)
    {
        $title = 'Add Master Product';
        $data = [
            'is_create' => true
        ];
        if($rec_id != '0'){
            $record = MasterProduct::find($rec_id);
            $data = [
                'record' => $record,
                'is_create' => false
            ];
            $title = 'Edit Master Product';
        }

        $content = View::make('master_products.form.add', $data)->render();

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
    public function storeMasterProduct(Request $request, Ajax $ajax)
    {
        $rules = [
            'Name' => 'required|min:3|max:50',
            'Price' => 'required|numeric|gt:0',
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
            $master_product = MasterProduct::find($id);
            $master_product->Name = $request->input('Name');
            $master_product->Descriptions = $request->input('Descriptions');
            $master_product->Price = $request->input('Price');
            $master_product->save();
            if($request->file('Photo') && !empty($request->file('Photo')) && isset($master_product->attachment->attachment_url)){
                $file_path = public_path('/uploads/master_products/'.$master_product->attachment->attachment_url);

                $file_path_thumb = public_path('/uploads/master_products/thumb/'.$master_product->attachment->attachment_url);

                if(file_exists($file_path)) unlink($file_path);
                if(file_exists($file_path_thumb)) unlink($file_path_thumb);

                $master_product->attachment->delete();
            }
            $msg = 'updated';
        }else{
            $insertedData = MasterProduct::create([
                'Name' => $request->input('Name'),
                'Descriptions' => $request->input('Descriptions'),
                'Price' => $request->input('Price')
            ]);
            $id = $insertedData['id'];
            $msg = 'created';
        }
        if($request->file('Photo') && !empty($request->file('Photo'))) {
            $destination = public_path('/uploads/master_products/');
            $result = FileUpload::uploadSingle($request->file('Photo'), $destination, 1);

            if (count($result) > 0) {
                Attachment::create([
                    'user_id' => Auth::id(),
                    'type_id' => $id,
                    'attachment_type' => 'Master_Products',
                    'attachment_title' => $result['attachment_title'],
                    'attachment_url' => $result['attachment_url'],
                    'is_thumbnail' => 1,
                ]);
            }
        }
        return $ajax->success()
            ->message('Master product ' . $msg . ' successfully')
            ->jscallback('ajax_master_product_load')
            ->response();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function masterProductVeiw($rec_id, Ajax $ajax)
    {
        $title = 'View Master Product';
        $record = MasterProduct::with('attachment')->where('id', $rec_id)->first(['id','Name', 'Descriptions', 'Price', 'Created_at'])->toArray();

        $content = View::make('master_products.form.view', ['record' => $record])->render();

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
     * Remove the specified resource from storage.
     */
    public function destroy($rec_id, Ajax $ajax)
    {

        $master_product = MasterProduct::find($rec_id);

        $file_path = public_path('/uploads/master_products/'.$master_product->attachment->attachment_url);

        $file_path_thumb = public_path('/uploads/master_products/thumb/'.$master_product->attachment->attachment_url);

        if(file_exists($file_path)) unlink($file_path);
        if(file_exists($file_path_thumb)) unlink($file_path_thumb);

        $master_product->attachment->delete();

        $master_product->delete();

        return $ajax->success()
            ->message('Master product deleted successfully')
            ->jscallback('ajax_master_product_load')
            ->response();
    }
}
