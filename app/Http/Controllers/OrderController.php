<?php

namespace App\Http\Controllers;

use App\Models\order;
use Exception;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\hostingPlan;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**s
     * Display a listing of the resource.
     */

    private $gateway;
    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }
    public function pay(Request $request)
    {
        $customer_id = $request->customer_id;
        $package_id = $request->package_id;
        $final_price = $request->final_price;

        $desc = hostingPlan::find($package_id);
        $description = "Subscription to the hosting plan : " . $desc['package_type'];
        try {
            $response = $this->gateway->purchase(array(
                'amount' => $final_price,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('https://0725-185-177-126-128.ngrok-free.app/api/customer/success?customer_id=1&package_id=1&final_price=1'),
                'cancelUrl' => url('https://0725-185-177-126-128.ngrok-free.app/api/customer/error'),
                'description' => $description
            ))->send();
            if ($response->isRedirect()) {
                return ["URL" => $response->getRedirectUrl()];
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function success(Request $request)
    {

        if ($request->input('paymentId') and $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ));
            $customer_id = $request->input('customer_id');
            $package_id = $request->input('package_id');
            $final_price = $request->input('final_price');

            $response = $transaction->send();
            if ($response->isSuccessful()) {
                $arr = $response->getData();
                $order = new order();
                $order->customer_id = $customer_id;
                $order->hostingplan_id = $package_id;
                $order->paymentId = $arr['id'];
                $order->currency = env('PAYPAL_CURRENCY');
                $order->status = "waiting";
                $order->final_price = $final_price;

                if ($order->save()) {
                    return redirect()->to('http://localhost:8080/Customer_mainpage?status=Payment_Complete')->send();
                }
            } else {
                return redirect()->to('http://localhost:8080/Customer_mainpage?status=Server_Error')->send();
            }
        }
    }
    public function error()
    {
        return redirect()->to('http://localhost:8080/Customer_mainpage?status=Customer_Declined_Payment')->send();
    }
    public function index($id = -1)
    {

        if ($id == -1)
            return order::all();

        else if (order::find($id))
            return order::find($id);

        else return ['status' => 'order not found'];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // For al-haram payment (image upload)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'hostingplan_id' => 'required',
            'final_price' => 'required',
            'receipt' => 'required|file|mimes:jpeg,png,jpg'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $image_file = $request->file('receipt');
        //image name format is : c_id-p_id-time.extension as (customer_id-plan_id-time.extension)
        $image_name = "c_" . $request->customer_id . "-p_" . $request->hostingplan_id . "-" . time() .".". $image_file->extension();
        $path = $image_file->storeAs('receipts', $image_name);

        $order = new order();
        $order->customer_id = $request->customer_id;
        $order->hostingplan_id = $request->hostingplan_id;
        $order->receipt_path ="storage/app/".$path;
        $order->currency = env('PAYPAL_CURRENCY');
        $order->paymentId = null;
        $order->status = "waiting";
        $order->final_price = $request->final_price;
        if ($order->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Order added successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Order adding failed'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(order $order)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        //
    }
}
