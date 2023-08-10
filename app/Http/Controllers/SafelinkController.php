<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Helpers\Helper;
use App\Helpers\Sdwan;
use App\Library\Ajax;
use App\Models\Attachment;
use App\Models\BillingProfile;
use App\Models\Bond;
use App\Models\Country;
use App\Models\MasterProduct;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\RawInfo;
use App\Models\Safelink;
use App\Models\Space;
use App\Models\SubscriptionTerm;
use http\Url;
use Illuminate\Http\Request;
use Validator;
use Auth;
use \Illuminate\Support\Facades\View as View;

class SafelinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $master_products = MasterProduct::with(['attachment'])->orderBy('Name')->get()->toArray();
        $subscription_terms = SubscriptionTerm::orderByRaw('CONVERT(Term, SIGNED) asc')->get()->toArray();
        $billing_profiles = BillingProfile::where('user_id', Auth::id())->orderBy('company_name')->get()->toArray();
        $countries = Country::orderBy('name')->get();
        return view('safelinks.index', [
            'master_products' => $master_products,
            'subscription_terms' => $subscription_terms,
            'billing_profiles' => $billing_profiles,
            'countries' => $countries
        ]);
    }

    public function getPayment(Request $request, Ajax $ajax)
    {
        $rawInfo = new RawInfo();
        $rawInfo->products = $request->input('products');
        $rawInfo->order_details = $request->input('order_details');
        $rawInfo->billing_details = $request->input('billing_details');
        $rawInfo->save();

        $products = json_decode($request->input('products'), true);
        $orderDetails = json_decode($request->input('order_details'), true);
        $billingDetails = json_decode($request->input('billing_details'), true);

        $product_names = $quanity = $price = [];
        foreach ($products as $product) {
            $product_names[] = $product['prod_name'];
            $quanity[] = 1;
            $unit_price = ((int)$product['single_prod_total_price'] * 16500);
            $vat_amount = ($unit_price * 11) / 100;
            $price[] = (int)$unit_price + (float)$vat_amount;
        }

        $va = config('constant.IPaymu_VA'); //'0000005755180590';//config('constant.VA');
        $apiKey = config('constant.IPaymu_APIKEY'); //'SANDBOXEDF3F940-53A8-4746-A846-048A6398526E'; //get on iPaymu dashboard

        $url = config('constant.IPaymu_URL');
        // $url          = 'https://my.ipaymu.com/api/v2/payment'; // for production mode

        $method = 'POST';

        //Request Body//
        $body['product'] = $product_names;
        $body['qty'] = $quanity;
        $body['price'] = $price;
        $body['returnUrl'] = url('safelink/thank-you/' . $rawInfo->id);
        $body['cancelUrl'] = url('safelink/cancel/' . $rawInfo->id);
        $body['notifyUrl'] = url('safelink/callback/' . $rawInfo->id);
        $body['referenceId'] = '1234'; //your reference id

        //End Request Body//

        //Generate Signature
        // *Don't change this
        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
        $signature = hash_hmac('sha256', $stringToSign, $apiKey);
        $timestamp = Date('YmdHis');
        //End Generate Signature


        $ch = curl_init($url);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);

        if ($err) {
            echo $err;
        } else {
            $ret = json_decode($ret);
            if ($ret->Status == 200) {
                $sessionId = $ret->Data->SessionID;
                $url = $ret->Data->Url;
                return $ajax->success()
                    ->jscallback()
                    ->reload_page(true)
                    ->redirectTo($url)
                    ->response();
            } else {
                $rawInfo->delete();
                return $ajax->fail()
                    ->jscallback()
                    ->message('Payment process failed because ' . $ret->Message, '', 'error')
                    ->response();
            }
            //End Response
        }
    }

    public function getPayment_old(Request $request, Ajax $ajax)
    {

        $rawInfo = new RawInfo();
        $rawInfo->products = $request->input('products');
        $rawInfo->order_details = $request->input('order_details');
        $rawInfo->billing_details = $request->input('billing_details');
        $rawInfo->save();

        $products = json_decode($request->input('products'), true);
        $orderDetails = json_decode($request->input('order_details'), true);
        $billingDetails = json_decode($request->input('billing_details'), true);

        $product_names = $quanity = $price = [];
        foreach ($products as $product) {
            $product_names[] = $product['prod_name'];
            $quanity[] = 1;
            $unit_price = ((int)$product['single_prod_total_price'] * 16500);
            $vat_amount = ($unit_price * 11) / 100;
            $price[] = (int)$unit_price + (float)$vat_amount;
        }


        $va = env('IPaymu_URL', '0000005755180590'); //'0000005755180590';//config('constant.VA');
        $apiKey = env('IPaymu_VA', 'SANDBOXEDF3F940-53A8-4746-A846-048A6398526E'); //'SANDBOXEDF3F940-53A8-4746-A846-048A6398526E'; //get on iPaymu dashboard

        $url = env('IPaymu_APIKEY', 'https://sandbox.ipaymu.com/api/v2/payment'); //'https://sandbox.ipaymu.com/api/v2/payment'; // for development mode
        // $url          = 'https://my.ipaymu.com/api/v2/payment'; // for production mode

        $method = 'POST'; //method

        //Request Body//
        $body['product'] = $product_names;
        $body['qty'] = $quanity;
        $body['price'] = $price;
        $body['returnUrl'] = url('safelink/thank-you/' . $rawInfo->id);
        $body['cancelUrl'] = url('safelink/cancel/' . $rawInfo->id);
        $body['notifyUrl'] = url('safelink/callback/' . $rawInfo->id);
        $body['referenceId'] = '1234'; //your reference id
        $body['name'] = $billingDetails['first_name'] . ' ' . $billingDetails['last_name']; //your reference id
        $body['email'] = $billingDetails['billing_email'];
        //End Request Body//

        //Generate Signature
        // *Don't change this
        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
        $signature = hash_hmac('sha256', $stringToSign, $apiKey);
        $timestamp = Date('YmdHis');
        //End Generate Signature


        /*$ch = curl_init($url);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);*/

        /*$va           = '1179000899'; //get on iPaymu dashboard
        $apiKey       = 'QbGcoO0Qds9sQFDmY0MWg1Tq.xtuh1'; //get on iPaymu dashboard

        $url          = 'https://sandbox.ipaymu.com/api/v2/payment'; // for development mode
        // $url          = 'https://my.ipaymu.com/api/v2/payment'; // for production mode

        $method       = 'POST';*/ //method

        //Request Body//
        $body['product'] = array('headset', 'softcase');
        $body['qty'] = array('1', '3');
        $body['price'] = array('100000', '20000');
        $body['returnUrl'] = url('safelink/thank-you/' . $rawInfo->id);
        $body['cancelUrl'] = url('safelink/cancel/' . $rawInfo->id);
        $body['notifyUrl'] = url('safelink/callback/' . $rawInfo->id);
        $body['referenceId'] = '1234'; //your reference id
        //End Request Body//

        //Generate Signature
        // *Don't change this
        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $apiKey;
        $signature = hash_hmac('sha256', $stringToSign, $apiKey);
        $timestamp = Date('YmdHis');
        //End Generate Signature


        $ch = curl_init($url);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);

        if ($err) {
            echo $err;
        } else {
            $ret = json_decode($ret);
            if ($ret->Status == 200) {
                $sessionId = $ret->Data->SessionID;
                $url = $ret->Data->Url;
                return $ajax->success()
                    ->jscallback()
                    ->reload_page(true)
                    ->redirectTo($url)
                    ->response();
            } else {
                $rawInfo->delete();
                return $ajax->fail()
                    ->jscallback()
                    ->message('Payment process failed because ' . $ret->Message, '', 'error')
                    ->response();
            }
            //End Response
        }
    }

    public function orderPlaced(Request $request, Ajax $ajax)
    {
        $rawInfo = new RawInfo();
        $rawInfo->products = $request->input('products');
        $rawInfo->order_details = $request->input('order_details');
        $rawInfo->billing_details = $request->input('billing_details');
        $rawInfo->save();

        $products = json_decode($request->input('products'), true);
        $orderDetails = json_decode($request->input('order_details'), true);
        $billingProfiles = json_decode($request->input('billing_details'), true);

        if (isset($billingProfiles['exist_billing']) && is_array($billingProfiles['exist_billing'])) {
            $billing_profile_id = isset($billingProfiles['exist_billing']['id']) ? $billingProfiles['exist_billing']['id'] : 0;
        } else {
            $billing_profile = new BillingProfile();
            $billing_profile->user_id = $billingProfiles['user_id'];
            $billing_profile->company_name = $billingProfiles['company_name'];
            $billing_profile->billing_email = $billingProfiles['billing_email'];
            $billing_profile->first_name = $billingProfiles['first_name'];
            $billing_profile->last_name = $billingProfiles['last_name'];
            $billing_profile->last_name = $billingProfiles['last_name'];
            $billing_profile->save();
            $billing_profile_id = $billing_profile->id;
        }

        $last_order = Order::latest()->first(['invoice_number']);
        if ($last_order) {
            $invoice_number_parts = explode('-', $last_order->invoice_number);
            $last_invoice_number = $invoice_number_parts[1];
            $new_order_number = ++$last_invoice_number;
        } else {
            $new_order_number = 1001;
        }

        $order = new Order();
        $order->invoice_number = 'SL-' . $new_order_number;
        $order->user_id = Auth::id();
        $order->billing_profile_id = $billing_profile_id;
        $order->sub_amount = $orderDetails['sub_amount'];
        $order->vat_percentage = $orderDetails['vat_percentage'];
        $order->grand_total_amount = $orderDetails['grand_total_amount'];
        $order->payment_method = $billingProfiles['payment_type'];
        $order->order_date = now();
        $order->payment_status = $billingProfiles['payment_type'] == 'Bank_Transfer' ? 'Waiting for Payment' : 'Waiting for Payment';
        $order->save();

        foreach ($products as $product) {
            $orderDetails = new OrderDetail();
            $orderDetails->order_id = $order->id;
            $orderDetails->product_id = $product['prod_id'];
            $orderDetails->quality = 1;
            $orderDetails->price = $product['prod_price'];
            $orderDetails->save();

            $safelink = new Safelink();
            $safelink->billing_profile_id = $billing_profile_id;
            $safelink->safelink_id = $product['safelink_id'];
            $safelink->safelink_name = $product['safelink_name'];
            $safelink->country_id = !empty($product['country_id']) ? $product['country_id'] : 0;
            $safelink->address = $product['address'];
            $safelink->lat = !empty($product['lat']) ? $product['lat'] : 0;
            $safelink->long = !empty($product['long']) ? $product['long'] : 0;
            $safelink->status = 0;
            $safelink->save();
        }

        return $ajax->success()
            ->jscallback('bank_transfer_order_placed')
            ->message('Order placed successfully')
            ->response();
    }

    public function thankYou($rid, Request $request, Ajax $ajax)
    {
        $rowinfo = RawInfo::find($rid);

        if (!$rowinfo) {
            return redirect(route('billings'))->with('error', 'your message,here');;
        }
        self::saveOrder($rid, $request);

        return redirect(route('billings'))->with('success', 'Order placed successfully');
    }

    public function cancelOrder($rid, Request $request, Ajax $ajax)
    {
        $rowinfo = RawInfo::find($rid);
        if (!$rowinfo) {
            return redirect(route('billings'))->with('error', 'your message,here');;
        }
        self::saveOrder($rid, $request);
        return redirect(route('billings'))->with('success', 'Order placed successfully');
    }

    public static function saveOrder($rid, $request)
    {
        $rowinfo = RawInfo::find($rid);
        if (!$rowinfo) {
            return redirect(route('billings'))->with('error', 'your message,here');;
        }
        $products = json_decode($rowinfo->products, true);
        $orderDetails = json_decode($rowinfo->order_details, true);
        $billingProfiles = json_decode($rowinfo->billing_details, true);

        $existing_profile = false;
        if (isset($billingProfiles['exist_billing']) && is_array($billingProfiles['exist_billing'])) {
            $billing_profile_id = isset($billingProfiles['exist_billing']['id']) ? $billingProfiles['exist_billing']['id'] : 0;
            $existing_profile = true;
        } else {
            $billing_profile = new BillingProfile();
            $billing_profile->user_id = $billingProfiles['user_id'];
            $billing_profile->company_name = $billingProfiles['company_name'];
            $billing_profile->billing_email = $billingProfiles['billing_email'];
            $billing_profile->first_name = $billingProfiles['first_name'];
            $billing_profile->last_name = $billingProfiles['last_name'];
            $billing_profile->last_name = $billingProfiles['last_name'];
            $billing_profile->save();
            $billing_profile_id = $billing_profile->id;
        }

        $last_order = Order::latest()->first(['invoice_number']);
        if ($last_order) {
            $invoice_number_parts = explode('-', $last_order->invoice_number);
            $last_invoice_number = $invoice_number_parts[1];
            $new_order_number = ++$last_invoice_number;
        } else {
            $new_order_number = 1001;
        }

        $order = new Order();
        $order->invoice_number = 'SL-' . $new_order_number;
        $order->user_id = Auth::id();
        $order->billing_profile_id = $billing_profile_id;
        $order->sub_amount = $orderDetails['sub_amount'];
        $order->vat_percentage = $orderDetails['vat_percentage'];
        $order->grand_total_amount = $orderDetails['grand_total_amount'];
        $order->payment_method = $billingProfiles['payment_type'];
        $order->order_date = now();
        $order->payment_status = ucfirst($request->get('status'));
        $order->save();

        foreach ($products as $product) {
            $orderDetails = new OrderDetail();
            $orderDetails->order_id = $order->id;
            $orderDetails->product_id = $product['prod_id'];
            $orderDetails->quality = 1;
            $orderDetails->price = $product['prod_price'];
            $orderDetails->save();

            $safelink = new Safelink();
            $safelink->billing_profile_id = $billing_profile_id;
            $safelink->safelink_id = $product['safelink_id'];
            $safelink->safelink_name = $product['safelink_name'];
            $safelink->country_id = !empty($product['country_id']) ? $product['country_id'] : 0;
            $safelink->address = $product['address'];
            $safelink->lat = !empty($product['lat']) ? $product['lat'] : 0;
            $safelink->long = !empty($product['long']) ? $product['long'] : 0;
            $safelink->status = 0;
            $safelink->save();
        }

        $billing_profile = BillingProfile::with(['newsafelinks'])->find($billing_profile_id);
        $request_body = [
            'name' => strtolower($billing_profile->company_name),
            'key' => strtolower($billing_profile->company_name),
            'parent' => config('constant.SDWAN.parent_url'),
            'SDWAN' => config('constant.SDWAN'),
        ];
        $safelink_ids = [];
        if ($request->has('status') && strtolower($request->get('status')) == 'success') {

            if ($existing_profile == false) {

                $authorization = Sdwan::getAuthToken($request_body);
                $authorization = (array)json_decode($authorization, true);

                $result = Sdwan::create_new_space($request_body, $authorization['auth_token']);
                $result = json_decode($result, true);

                if ($result['key'][0] == 'This field is required.' || $result['name'][0] == 'This field is required.' || $result['key'][0] == 'Key must be unique.') {
                    $space = Space::where('key', strtolower($billing_profile->company_name))->first();

                } else {
                    $space = new Space();
                    $space->user_id = Auth::id();
                    $space->billing_profile_id = $billing_profile->id;
                    $space->response_id = $result['id'];
                    $space->name = $result['name'];
                    $space->url = $result['url'];
                    $space->key = $result['key'];
                    $space->level = $result['level'];
                    $space->crm_id = $result['crm_id'];
                    $space->related_bonds_counts = $result['related_object_counts']['bonds'];
                    $space->parent = config('constant.SDWAN.parent_name');
                    $space->parent_url = $result['parent'];
                    $space->save();
                }


                if (isset($billing_profile->newsafelinks)) {
                    foreach ($billing_profile->newsafelinks as $safelink) {
                        //$safelink->safelink_id
                        //$safelink->safelink_name
                        $bonds_request = [
                            'name' => $safelink->safelink_name,
                            'circuit_id' => $safelink->safelink_id,
                            'space' => [
                                'name' => $space->name,
                                'key' => $space->key,
                                'url' => $space->url
                            ],
                            'aggregator' => 'https://poc.pilot.multapplied.net/api/v4/aggregators/1590/',
                            'SDWAN' => config('constant.SDWAN'),
                        ];

                        $bresult = Sdwan::create_new_bond($bonds_request, $authorization['auth_token']);
                        $bresult = (array)json_decode($bresult, true);

                        if($bresult){
                            $bond = new Bond();
                            $bond->response_bond_id = $bresult['id'];
                            $bond->name = $bresult['name'];
                            $bond->circuit_id = $bresult['circuit_id'];
                            $bond->aggregator = $bresult['aggregator'];
                            $bond->space_id = $space->id;
                            $bond->save();
                            $safelink_ids[] = $safelink->id;
                        }


                    }
                }
            } else {
                $authorization = Sdwan::getAuthToken($request_body);
                $authorization = (array)json_decode($authorization, true);

                $space = Space::where('billing_profile_id', $billing_profile_id)->first();
                if (isset($billing_profile->newsafelinks)) {

                    foreach ($billing_profile->newsafelinks as $safelink) {
                        //$safelink->safelink_id
                        //$safelink->safelink_name
                        $bonds_request = [
                            'name' => $safelink->safelink_name,
                            'circuit_id' => $safelink->safelink_id,
                            'space' => [
                                'name' => $space->name,
                                'key' => $space->key,
                                'url' => $space->url
                            ],
                            'aggregator' => 'https://poc.pilot.multapplied.net/api/v4/aggregators/1590/',
                            'SDWAN' => config('constant.SDWAN'),
                        ];

                        $bresult = Sdwan::create_new_bond($bonds_request, $authorization['auth_token']);
                        $bresult = (array)json_decode($bresult, true);

                        $bond = new Bond();
                        $bond->response_bond_id = $bresult['id'];
                        $bond->name = $bresult['name'];
                        $bond->circuit_id = $bresult['circuit_id'];
                        $bond->aggregator = $bresult['aggregator'];
                        $bond->space_id = $space->id;
                        $bond->save();
                        $safelink_ids[] = $safelink->id;
                    }
                }
            }
            if(count($safelink_ids) > 0){
                Safelink::whereIn('id', $safelink_ids)
                    ->update(['status'=> 1]);
            }
        }
        $rowinfo->delete();
    }

    public function testSDWAN(Ajax $ajax)
    {
        $billing_profile = BillingProfile::with(['safelinks'])->find(1);
        $request_body = [
            'name' => strtolower($billing_profile->company_name),
            'key' => strtolower($billing_profile->company_name),
            'parent' => config('constant.SDWAN.parent_url'),
            'SDWAN' => config('constant.SDWAN'),
        ];

        $authorization = Sdwan::getAuthToken($request_body);
        $authorization = (array)json_decode($authorization, true);

        $result = Sdwan::create_new_space($request_body, $authorization['auth_token']);
        $result = json_decode($result, true);
        if ($result['key'][0] == 'This field is required.' || $result['name'][0] == 'This field is required.' || $result['key'][0] == 'Key must be unique.') {
            $space = Space::where('key', strtolower($billing_profile->company_name))->first();

        } else {
            $space = new Space();
            $space->user_id = Auth::id();
            $space->billing_profile_id = $billing_profile->id;
            $space->response_id = $result['id'];
            $space->name = $result['name'];
            $space->url = $result['url'];
            $space->key = $result['key'];
            $space->level = $result['level'];
            $space->crm_id = $result['crm_id'];
            $space->related_bonds_counts = $result['related_object_counts']['bonds'];
            $space->parent = config('constant.SDWAN.parent_name');
            $space->parent_url = $result['parent'];
            $space->save();
        }

        if (isset($billing_profile->safelinks)) {
            foreach ($billing_profile->safelinks as $safelink) {
                //$safelink->safelink_id
                //$safelink->safelink_name
                $bonds_request = [
                    'name' => $space->name.'-'.$safelink->safelink_name,
                    'circuit_id' => $safelink->safelink_id,
                    'space' => [
                        'name' => $space->name,
                        'key' => $space->key,
                        'url' => $space->url
                    ],
                    'aggregator' => 'https://poc.pilot.multapplied.net/api/v4/aggregators/1590/',
                    'SDWAN' => config('constant.SDWAN'),
                ];

                $bresult = Sdwan::create_new_bond($bonds_request, $authorization['auth_token']);
                $bresult = (array)json_decode($bresult, true);

                $bond = new Bond();
                $bond->response_bond_id = $bresult['id'];
                $bond->name = $bresult['name'];
                $bond->circuit_id = $bresult['circuit_id'];
                $bond->aggregator = $bresult['aggregator'];
                $bond->space_id = $space->id;
                $bond->save();

            }
        }
        echo '<pre>';

        print_r($bresults);
    }

    public function testBond($bond_id){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://poc.pilot.multapplied.net/api/v4/spaces/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'eddy@brightcorporation.biz:Safelink2030');
        //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_dody));

        $response = curl_exec($ch);

        curl_close($ch);
        $response = (array) json_decode($response, true);
        echo '<pre>';
        print_r($response);
    }
}
