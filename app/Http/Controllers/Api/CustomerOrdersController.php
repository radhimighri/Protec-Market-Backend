<?php

namespace App\Http\Controllers\Api;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\ProductCollection;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use Validator;
use Illuminate\Support\Arr;
use Faker\Factory as Faker;
use Omnipay\Omnipay;
use Mail;
use App\Mail\OrderMail;
class CustomerOrdersController extends Controller
{
    private $gateway;
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Customer $customer )
    {
      //  $this->authorize('view', $customer);

      $orders = auth()->user()->orders;

      return response()->json([
          'success' => true,
          'data' => $orders
      ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function store_stripe(Request $request, Customer $customer ,Product $product)
    {
        //$this->authorize('create', Order::class);
        

        $validator = Validator::make($request->all(), [
            'card_no' => 'required',
            'ccExpiryMonth' => 'required',
            'ccExpiryYear' => 'required',
            'cvvNumber' => 'required',
            'amount' => 'required',
            'products'=> ['required','array'],
            'quantity' => ['required','array'],
            'pick_up_date'=>'required'
            ]);
            $input = $request->all();
            if ($validator->passes()) { 
            $input = Arr::except($input,array('_token'));
           $stripe = \Stripe::setApiKey(env('STRIPE_SECRET'));
            try {
            $token = $stripe->tokens()->create([
            'card' => [
            'number' => $request->get('card_no'),
            'exp_month' => $request->get('ccExpiryMonth'),
            'exp_year' => $request->get('ccExpiryYear'),
            'cvc' => $request->get('cvvNumber'),
            ],
            ]);
           if (!isset($token['id'])) {
             return response()->json([
                'success' => false,
                'message' => 'Order not added'
            ], 500);
            } 
            
            $charge = $stripe->charges()->create([
            'card' => $token['id'],
            'currency' => 'EUR',
            'amount' => $request->get('amount'),
            'description' => 'anything',
            //'customer'=> 'name',
            ]);
            
            if($charge['status'] == 'succeeded') {
                
                $faker = Faker::create();
                $order = new Order;
                $order->total_price = $request->amount;
                $order->payment_status = 'paid';
                $order->payment_method = 'stripe';
                $order->stauts = 'pending';
                $order->number = 'OR' . $faker->unique()->randomNumber(6) ;
                $order->pick_up_date = $request->pick_up_date;
                $customer_mail = auth()->user()->email;

                if (auth()->user()->orders()->save($order)){
                    foreach (array_combine($request->products,$request->quantity) as $ids => $qty){
                        $order->products()->syncWithoutDetaching([$ids => ['quantity' => $qty]  ]);
                        if((($product->where('id', $ids)->first()->stock) - $qty) >= 0){
                            $product->where('id', $ids)->decrement('stock',$qty );}
                        else return response()->json([
                             'success' => false,
                             'message' => 'reached stock limit'
                         ], 500);
                    
                    
                     }
                     $products_mail= $order->products()->get();
                     $mailData = [
                        'title' => $order->number,
                        'body' => [ $order->total_price,
                        $order->payment_status,
                        $order->payment_method ,
                        $order->stauts,
                        $order->pick_up_date ,
                        
                     ],
                     'products'=> $products_mail,
                    ];
                Mail::to($customer_mail)->send(new OrderMail($mailData));
               return response()->json([
                'success' => true,
                'data' => [$order->toArray()],
                'products' => [$order->products()->get() ]
            ]);}
                else
                    return response()->json([
                        'success' => false,
                        'message' => 'Order not added'
                    ], 500);
            

           // return redirect()->route('addmoney.paymentstripe');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not added'
                ], 500);
            }
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            //return redirect()->route('addmoney.paymentstripe');
            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            //return redirect()->route('addmoney.paywithstripe');
            } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
           // return redirect()->route('addmoney.paymentstripe');
            }
            }
            else{
                return response()->json([
                    'success' => false,
                    'message' => ' not valid'
                ], 500);
            }
    }

    public function show($id)
    {
        $order = auth()->user()->orders()->find($id);
 
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found '
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => $order->toArray(),
            'products'=>$order->products()->get() 
        ], 200);
    }

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(false); //set it to 'false' when go live
     }
   
    /**
     * Call a view.
     */
   
    /**
     * Initiate a payment on PayPal.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function charge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            ]);
       //     $request->session()->put('req', $request);

            try {
                $response = $this->gateway->purchase(array(
                    'amount' => $request->input('amount'),
                    'currency' => env('PAYPAL_CURRENCY'),
                    'returnUrl' => 'http://mevdnic.cluster031.hosting.ovh.net/protech/public/api/success',
                    'cancelUrl' => 'http://mevdnic.cluster031.hosting.ovh.net/protech/public/api/error',
                ))->send();
            
                if ($response->isRedirect()) {
                    return $response->getRedirectUrl();
                    // $response->redirect(); // this will automatically forward the customer
                } else  {
                    // not successful
                    return $response->getMessage();
                }
            } catch(Exception $e) {
                return  response()->json([
                    'success' => true,
                    'message' => $e,
                ], 500);
            }
        
    }
   
    /**
     * Charge a payment and store the transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function success(Request $request, Customer $customer)
    {
        // Once the transaction has been approved, we need to complete it.
        if ($request->input('paymentId') && $request->input('PayerID'))
        {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));
            $response = $transaction->send();
            
            if ($response->isSuccessful())
            {


               return response()->json([
                'success' => true,
                'message' => 'Transaction succeeded',
               
            ], 200);
             
            }
        } else {
        return response()->json([
                'success' => false,
                'message' => 'Transaction declined'
            ], 500);
        }
    }
   
    /**
     * Error Handling.
     */
    public function error()
    {
     return response()->json([
            'success' => false,
            'message' => 'Transaction cancelled'
        ], 500);
    }






    public function store_pickup(Request $request, Product $product )
    {
        $this->validate($request, [
            'amount' => 'required',
            'products'=> ['required','array'],
            'quantity' => ['required','array'],
            'pick_up_date'=>'required'
        ]);
        
        $faker = Faker::create();
        $order = new Order;
        $order->total_price = $request->amount;
        $order->payment_status = 'not paid';
        $order->payment_method = 'pay on site';
        $order->stauts = 'pending';
        $order->number = 'OR' . $faker->unique()->randomNumber(6) ;
        $order->pick_up_date = $request->pick_up_date;
        $customer_mail = auth()->user()->email;

        if (auth()->user()->orders()->save($order)){
        foreach (array_combine($request->products,$request->quantity) as $ids => $qty){
            $order->products()->syncWithoutDetaching([$ids => ['quantity' => $qty]  ]);
            if($product->where('id', $ids)->decrement('stock',$qty ) > 0 ){
               $product->where('id', $ids)->decrement('stock',$qty );}
               else return response()->json([
                'success' => false,
                'message' => 'reached stock limit'
            ], 500);
            }
            $products_mail= $order->products()->get();
            $mailData = [
               'title' => $order->number,
               'body' => [ $order->total_price,
               $order->payment_status,
               $order->payment_method ,
               $order->stauts,
               $order->pick_up_date ,
               
            ],
            'products'=> $products_mail,
           ];
       Mail::to($customer_mail)->send(new OrderMail($mailData));
            return response()->json([
                'success' => true,
                'data' => [$order->toArray()],
                'products' => [$order->products()->get() ]
            ]);}
        else
            return response()->json([
                'success' => false,
                'message' => 'Order not added'
            ], 500);
    }

    public function store_paypal(Request $request ,Product $product)
    {
        $this->validate($request, [
            'amount' => 'required',
            'products'=> ['required','array'],
            'quantity' => ['required','array'],
            'pick_up_date'=>'required'
        ]);
        
        $faker = Faker::create();
        $order = new Order;
        $order->total_price = $request->amount;
        $order->payment_status = 'paid';
        $order->payment_method = 'paypal';
        $order->stauts = 'pending';
        $order->number = 'OR' . $faker->unique()->randomNumber(6) ;
        $order->pick_up_date = $request->pick_up_date;
        $customer_mail = auth()->user()->email;

        if (auth()->user()->orders()->save($order)){
        foreach (array_combine($request->products,$request->quantity) as $ids => $qty){
            $order->products()->syncWithoutDetaching([$ids => ['quantity' => $qty]  ]);
            if($product->where('id', $ids)->decrement('stock',$qty ) > 0 ){
                $product->where('id', $ids)->decrement('stock',$qty );}
                else return response()->json([
                 'success' => false,
                 'message' => 'reached stock limit'
             ], 500);
            }
            $products_mail= $order->products()->get();
            $mailData = [
               'title' => $order->number,
               'body' => [ $order->total_price,
               $order->payment_status,
               $order->payment_method ,
               $order->stauts,
               $order->pick_up_date ,
               
            ],
            'products'=> $products_mail,
           ];
       Mail::to($customer_mail)->send(new OrderMail($mailData));
            return response()->json([
                'success' => true,
                'data' => [$order->toArray()],
                'products' => [$order->products()->get() ]
            ]);}
        else
            return response()->json([
                'success' => false,
                'message' => 'Order not added'
            ], 500);
    }

    public function vr_payment(Request $request, Customer $customer ,Product $product){

        $this->validate($request, [
            'amount' => 'required'
          
        ]);
        $instanceName = 'protec';

        // $secret is the VR pay secure secret for the communication between the applications
        // if you think someone got your secret, just regenerate it in the VR pay secure administration
        $secret = 'a9TK3tmMTbTJL2P6KO0LboK8j1uAM6';
        
        $vrpay = new \VRpay\VRpay($instanceName, $secret);
        
        // init empty request object
        $invoice = new \VRpay\Models\Request\Invoice();
        
        // info for payment link (reference id)
        $invoice->setReferenceId('Order number of my online shop application');
        
        // info for payment page (title, description)
        $invoice->setTitle('Online shop payment');
        $invoice->setDescription('Thanks for using VR pay secure to pay your order');
        
        // administrative information, which provider to use (psp)
        // psp #1 = VR pay secure test mode
        $invoice->setPsp(1);
        
        // internal data only displayed to administrator
        $invoice->setName('Online-Shop payment #001');
        
        // payment information
        $invoice->setPurpose('Shop Order #001');
        $amount = $request->amount;
        // don't forget to multiply by 100
        $invoice->setAmount($amount * 100);
        
        // ISO code of currency
        $invoice->setCurrency('EUR');
        
        // VAT rate percentage (nullable)
        // $vatRate = 0;
        // $invoice->setVatRate($vatRate);
        
        //Product SKU
        //$invoice->setSku('P01122000');
        
        // whether charge payment manually at a later date
        $invoice->setPreAuthorization(false);
        
        $invoice->setSuccessRedirectUrl('http://mevdnic.cluster031.hosting.ovh.net/protech/public/api/vr_success');
        $invoice->setFailedRedirectUrl('http://mevdnic.cluster031.hosting.ovh.net/protech/public/api/error');

        // whether charge payment manually at a later date (type reservation)
        //$invoice->setReservation(false);
        
        // subscription information if you want the customer to authorize a recurring payment
        // NOTE: This functionality is currently only available by using PAYMILL as a payment service provider. This also does not work in combination with pre-authorization payments.
        //$invoice->setSubscriptionState(true);
        //$invoice->setSubscriptionInterval('P1M');
        //$invoice->setSubscriptionPeriod('P1Y');
        //$invoice->setSubscriptionCancellationInterval('P3M');
        
        // add contact information fields which should be filled by customer
        // it would be great to provide at least an email address field
        // $invoice->addField($type = 'email', $mandatory = true, $defaultValue = 'my-customer@example.com');
        // $invoice->addField($type = 'company', $mandatory = true, $defaultValue = 'Ueli Kramer Firma');
        // $invoice->addField($type = 'forename', $mandatory = true, $defaultValue = 'Ueli');
        // $invoice->addField($type = 'surname', $mandatory = true, $defaultValue = 'Kramer');
        // $invoice->addField($type = 'country', $mandatory = true, $defaultValue = 'AT');
        // $invoice->addField($type = 'title', $mandatory = true, $defaultValue = 'miss');
        // $invoice->addField($type = 'terms', $mandatory = true);
        // $gateway->addField($type = 'privacy_policy', $mandatory = true);
        // $invoice->addField($type = 'custom_field_1', $mandatory = true, $defaultValue = 'Value 001', $name = 'Das ist ein Feld');
        
        // fire request with created and filled link request-object.
        try {
            $response = $vrpay->create($invoice);
            return response()->json([
                'success' => true ,
                'link' => $response->getLink(),
            ],200);
        } catch (\VRpay\VRpayException $e) {
            return $e->getMessage();
        
    }


            
    }
    public function store_vr(Request $request ,Product $product)
    {
        $this->validate($request, [
            'amount' => 'required',
            'products'=> ['required','array'],
            'quantity' => ['required','array'],
            'pick_up_date'=>'required'
        ]);
        
        $faker = Faker::create();
        $order = new Order;
        $order->total_price = $request->amount;
        $order->payment_status = 'paid';
        $order->payment_method = 'vr pay';
        $order->stauts = 'pending';
        $order->number = 'OR' . $faker->unique()->randomNumber(6) ;
        $order->pick_up_date = $request->pick_up_date;
        $customer_mail = auth()->user()->email;

        if (auth()->user()->orders()->save($order)){
        foreach (array_combine($request->products,$request->quantity) as $ids => $qty){
            $order->products()->syncWithoutDetaching([$ids => ['quantity' => $qty]  ]);
            if($product->where('id', $ids)->decrement('stock',$qty ) > 0 ){
                $product->where('id', $ids)->decrement('stock',$qty );}
                else return response()->json([
                 'success' => false,
                 'message' => 'reached stock limit'
             ], 500);
            }
            $products_mail= $order->products()->get();
            $mailData = [
               'title' => $order->number,
               'body' => [ $order->total_price,
               $order->payment_status,
               $order->payment_method ,
               $order->stauts,
               $order->pick_up_date ,
               
            ],
            'products'=> $products_mail,
           ];
       Mail::to($customer_mail)->send(new OrderMail($mailData));
            return response()->json([
                'success' => true,
                'data' => [$order->toArray()],
                'products' => [$order->products()->get() ]
            ]);}
        else
            return response()->json([
                'success' => false,
                'message' => 'Order not added'
            ], 500);
    }

    public function vr_success()
    {
        return response()->json([
            'success' => true,
            'message' => 'Transaction succeeded',
           
        ], 200); 
    }
}



