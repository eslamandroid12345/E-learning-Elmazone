<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use App\Models\Subscribe;
use App\Models\UserSubscribe;
use App\Services\PaymentService;
use App\Services\PaymobWalletService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Nafezly\Payments\Classes\FawryPayment;
use Nafezly\Payments\Classes\KashierPayment;
use Nafezly\Payments\Classes\PaymobPayment;
use Nafezly\Payments\Classes\PaymobWalletPayment;
use Nafezly\Payments\Classes\PaytabsPayment;
use function env;

class Payment extends Controller
{

    private Paymentservice $paymentService;

    /**
     * @param PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function pay(Request $request)
    {
        return $this->paymentService->pay($request);
    }

    public function stripe()
    {
        return view('stripe');
    }

    public function stripePost(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        \Stripe\Charge::create([

            'amount' => $request->amount,
            'currency'=>"usd",
            'source'=> $request->stripeToken,
            'description' =>'Test payment from muhammed essa'
        ]);

        Session::flash('success','Payment has been successfully');
        return back();
    }
    public function pay_(Request $request)
    {
        $inputs = $request->all();
//dd($inputs);
        $endpoint = "https://accept.paymobsolutions.com/api/auth/tokens";
        $orderEndpoint = "https://accept.paymobsolutions.com/api/ecommerce/orders";
        $payment_keysEndpoint = "https://accept.paymobsolutions.com/api/acceptance/payment_keys";
        $value = env('PAYMOB_API_KEY');

        session()->put('items_posts', $inputs);
        session()->put('user_type', );

        $response = Http::withHeaders(['content-type' => 'application/json'])
            ->post($endpoint, [
                "api_key" => $value
            ])->json();
//
        $order = Http::withHeaders(['content-type' => 'application/json'])
            ->post($orderEndpoint, [
                "auth_token" => $response['token'],
                "delivery_needed" => 'false',
                "merchant_id" => 743638,
                "amount_cents" => 2 *100,
                "currency" => "EGP",
                'items'=>[]
            ])->json();

        $payment_to_pay = Http::withHeaders(['content-type' => 'application/json'])
            ->post($payment_keysEndpoint, [
                 "auth_token"=>  $response['token'],
                  "amount_cents"=>2 *100,
                  "expiration"=>3600,
                  "order_id"=> $order['id'],
                  "billing_data"=>[
                        "apartment"=>"803",
                        "email"=>"claudette09@exa.com",
                        "floor"=>"42",
                        "first_name"=>"Clifford",
                        "street"=>"Ethan Land",
                        "building"=>"8028",
                        "phone_number"=>"+86(8)9135210487",
                        "shipping_method"=>"PKG",
                        "postal_code"=>"01898",
                        "city"=>"Jaskolskiburgh",
                        "country"=>"CR",
                        "last_name"=>"Nicolas",
                        "state"=>"Utah"
                      ],
                  "currency"=> "EGP",
                  "integration_id"=>3673470,
                'integration_type' => env('PAYMOB_INTEGRATION_TYPE'),

                "user_id"=>777,
                  "lock_order_when_paid"=> false
            ])->json();
        $url = "https://accept.paymobsolutions.com/api/acceptance/iframes/743638?payment_token=".$payment_to_pay['token'];

        return self::returnResponseDataApi(['payment_url' => $url],"تم استلام لينك الدفع بنجاح ",200);

    }

    public function pay_callback()
    {
//        dd(request()->all());
        $endpoint = "https://accept.paymobsolutions.com/api/auth/tokens";
        $value = env('PAYMOB_API_KEY');
        $transaction = Http::withHeaders(['content-type' => 'application/json'])
            ->get($endpoint, [
                "api_key" => $value
            ])->json();
        $response = request()->query();

        if($response['success'] == true){

            $payment = PaymentLog::where('payment_id',$response['id'])->first();
            foreach ($payment->request_variables['subscribes_ids'] as  $item){
                $subscribe_item = Subscribe::find($item);
                UserSubscribe::create([
                    'price' => $subscribe_item->price_in_center,
                    'month' => $subscribe_item->month,
                    'year' => $subscribe_item->year,
                    'student_id' =>  $payment->request_variables['user_id'],
                ]);
//                array_push($months,$subscribe_item->month);
            }  //

//            $user = User::find($payment->request_variables['user_id']);
//            $subscribed_months = getFromToMonthsList($user->date_start_code, $user->date_end_code);
////        dd($months,$subscribed_months);
//            $months_to_subscribe = sort(array_merge($months,$subscribed_months));
//            $dates = getFromToFromMonthsList($months_to_subscribe);

//            return response()->json(["data"=>'',"errors"=>'','message'=>"Payment Successfully.",'code'=>200],200);
//            dd(Session::get('items_posts'));
//            foreach (Session::get('items_posts')['subscribes_ids'] as  $item){
//                $subscribe_item = Subscribe::find($item);
//                UserSubscribe::create([
//                    'price' => $subscribe_item->price_in_center,
//                    'month' => $subscribe_item->price_in_center,
//                    'student_id' => $subscribe_item->price_in_center,
//                ]);
//            }
        }
        return redirect()->to('api/checkout?status='.$response['success'].'&id='.$response['id']);
    }

    public function checkout(Request $request)
    {
//        $payment = new PaymobPayment();
//        $response = $payment->verify($request);
//        $user = auth()->guard('user-api')->user();
//        foreach ($request->subscribes_ids as  $item){
//            $subscribe_item = Subscribe::find($item);
//            UserSubscribe::create([
//                'price' => ($user->center == "in")? $subscribe_item->price_in_center : $subscribe_item->price_out_center,
//                'month' => $subscribe_item->month,
//                'year' => $subscribe_item->year,
//                'student_id' =>  $user->id,
//            ]);
//            array_push($months,$subscribe_item->month);
//        }  //
//        return $request;
//        return self::returnResponseDataApi(null,"تد الدفع بنجاح",200);
    }

    public function test(Request $request)
    {
        $client = new Client();

        $response = $client->post('https://vodafone-cash.judopay-sandbox.com/v1/payments',
            [
                'auth' => [
                    env('VODAFONE_CASH_API_KEY'),
                    env('VODAFONE_CASH_API_SECRET')
                ],
                'json' => [
                    'your_transaction_details' => 'here'
                ]
            ]
        );

        $responseBody = $response->getBody()->getContents();

        // handle response
    }


    public function payWithPaymobVisa($request,$name,$amount,$email,$user_id,$phone){
        $user = auth()->guard('user-api');
        $payment = new PaymobPayment();
        $response = $payment
            ->setUserFirstName($name)
            ->setUserLastName($name)
            ->setUserEmail($email)
            ->setUserPhone($phone)
            ->setUserId($user_id)
            ->setAmount($amount*100)
            ->pay();

//        return $response;

        PaymentLog::create(
            ['payment_id'=>$response['payment_id'],'request_variables'=>$request->all(),'payment_type'=>'visa']
        );
        return self::returnResponseDataApi(['payment_url' => $response['redirect_url']],"تم استلام لينك الدفع بنجاح ",200);
        //output
        //[
        //    'payment_id'=>"", // refrence code that should stored in your orders table
        //    'redirect_url'=>"", // redirect url available for some payment gateways
        //    'html'=>"" // rendered html available for some payment gateways
        //]

    }

    public function payment_verify(Request $request){
//        dd($request);
        $payment = new PaymobWalletPayment();
        $response = $payment->verify($request);


        dd($response);
        //output
        //[
        //    'success'=>true,//or false
        //    'payment_id'=>"PID",
        //    'message'=>"Done Successfully",//message for client
        //    'process_data'=>""//payment response
        //]
    }

    public function go_pay(Request $request)
    {
        $user = auth()->guard('user-api')->user();
//        dd($user);
        switch ($request->payment_method) {
            case "wallet":
                return (new PaymobWalletService())->pay($request,$request->amount*100,$user->id,$user->name,$user->name,$user->email??"email@pay.com",$user->phone,null);
                break;
            case "visa":
                return $this->payWithPaymobVisa($request,$user->name,$request->amount*100,$user->id,$user->email??"email@pay.com",$user->phone);
                break;
            case "green":
                echo "Your favorite color is green!";
                break;
            default:
                return $this->paymentService->pay($request);
        }

    }


}
