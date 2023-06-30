<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\customer;
use App\Models\User;
use App\Models\hostingPlan;
use Exception;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;


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
                'returnUrl' => url('https://da19-46-213-123-153.ngrok-free.app/api/customer/success?customer_id='.$customer_id.'&package_id='.$package_id.'&final_price='.$final_price.''),
                'cancelUrl' => url('https://da19-46-213-123-153.ngrok-free.app/api/customer/error'),
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
    public function index($id = null)
    {

        $orders = collect([]);
        $_orders = order::all()->where('status','waiting');
        foreach($_orders as $order){
            $customer = customer::find($order->customer_id);
            $user = User::find($customer->user_id);
            $hosting_plan = hostingPlan::find($order->hostingplan_id);
            $payment_method = ($order->paymentId == null)? 'Al-haram':'Paypal';
            $created_at_date = new Carbon($order->created_at);
            $created_at_date = '20'.$created_at_date->format('y-m-d');

         if($payment_method == 'Al-haram'){
                $orders->push([
                    'order_id' => $order->id,
                    'customer_fname' => $user->f_name,
                    'customer_lname' => $user->l_name,
                    'package_name' => $hosting_plan->package_type,
                    'payment_method' => $payment_method,
                    'created_at' => $created_at_date,
                    'currency' => $order->currency,
                    'final_price' => $order->final_price,
                    'image' => $order->receipt_path
                ]);
        }else {
            $orders->push([
                'order_id' => $order->id,
                'customer_fname' => $user->f_name,
                'customer_lname' => $user->l_name,
                'package_name' => $hosting_plan->package_type,
                'payment_method' => $payment_method,
                'created_at' => $order->created_at,
                'currency' => $order->currency,
                'final_price' => $order->final_price,
                'paypal_payment_id' => $order->paymentId
            ]);
        }
        }
        return response()->json(['orders' => $orders]);

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
        $path = $image_file->move(public_path('receipts'),$image_name);

        $order = new order();
        $order->customer_id = $request->customer_id;
        $order->hostingplan_id = $request->hostingplan_id;
        $order->receipt_path = $image_name;
        $order->currency = 'SYP';
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

    public function approve(Order $order)
    {
        $order->update(['status' => 'approved']);
        customerHostingPlan::create(
            [
                'customer_id' => $order->customer_id,
                'hostingplan_id' => $order->hostingplan_id,
                'price' => $order->final_price,
                'expiry_date' => Carbon::now()->addYear(),
            ]
        );
        return response()->json( 'package approved successfully ');
    }
    public function disapprove(Order $order)
    {
        $order->update(['status' => 'rejected']);

        return response()->json( 'package disapproved successfully ');
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
