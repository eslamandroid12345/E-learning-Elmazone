<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscribe;
use App\Models\UserPackage;
use App\Models\UserSubscribe;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use IZaL\Knet\KnetBilling;
use Omnipay\Common\CreditCard;
use Omnipay\Omnipay;
use Stripe\Exception\CardException;
use Stripe\StripeClient;

class PaymentService
{

    // link web https://www.ultimateakash.com/blog-details/IixTJGAKYAo=/How-to-Integrate-Stripe-Payment-Gateway-In-Laravel-2022
    private $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.api_keys.secret_key'));
    }


    public function pay($request)
    {
        if($request->payment_method =="knet_payment"){
          return   $this->knet_payment();

        }
        $validator = Validator::make($request->all(), [
            'fullName' => 'required',
            'cardNumber' => 'required',
            'month' => 'required',
            'year' => 'required:',
            'cvv' => 'required'
        ]);


        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->errors()->first());
            return response()->json(["data"=>'',$validator->errors()->first(),'code'=>422],200);
        }

        $token = $this->createToken($request);
        $user = auth()->guard('user-api')->user();

        if (!empty($token['error'])) {
            return response()->json(["data"=>'','error'=> $token['error'],'message'=>"Payment failed.",'code'=>422],200);
        }
        if (empty($token['id'])) {
            return response()->json(["data"=>'',"errors"=>'','message'=>"Payment failed.",'code'=>409],200);
        }

        $charge = $this->createCharge($token['id'], $request->amount);
//        dd($charge);
        if (!empty($charge) && $charge['error']) {
            return response()->json(["data"=>'',"errors"=>['error'=>$charge['error']],'message'=>$charge['error']],409);
        }if (!empty($charge) && $charge['status'] == 'succeeded') {
           $payment = new Payment;
            $payment->user_id = auth()->guard('user-api')->id();
            $payment->payment_id = $charge->id;
            $payment->payer_email = $charge->billing_details->email;
            $payment->amount = $charge->amount;
            $payment->currency = $charge->currency;
            $payment->status = $charge->status;
            $payment->save();
            $months = [];
            foreach ($request->subscribes_ids as  $item){
                $subscribe_item = Subscribe::find($item);
                UserSubscribe::create([
                    'price' => $subscribe_item->price_in_center,
                    'month' => $subscribe_item->month,
                    'year' => $subscribe_item->year,
                    'student_id' =>  auth()->guard('user-api')->user()->id,
                ]);
                array_push($months,$subscribe_item->month);
            }  //

           $subscribed_months = getFromToMonthsList($user->date_start_code, $user->date_end_code);
//        dd($months,$subscribed_months);
           $months_to_subscribe = sort(array_merge($months,$subscribed_months));
           $dates = getFromToFromMonthsList($months_to_subscribe);

//           $user = User::find(auth()->guard('user-api')->user()->id)->update(['date_start_code'=> $dates[0],'date_end_code'=> $dates[1]]);
           return response()->json(["data"=>'',"errors"=>'','message'=>"Payment Successfully.",'code'=>200],200);

        } else {
            return response()->json(["data"=>'',"errors"=>[],'message'=>"Payment failed.",'code'=>406],200);
        }
        return response()->json(["data"=>''],200);
    }

    private function createToken($cardData)
    {
        $token = null;
        try {
            $token = $this->stripe->tokens->create([
                'card' => [
                    'number' => $cardData['cardNumber'],
                    'exp_month' => $cardData['month'],
                    'exp_year' => $cardData['year'],
                    'cvc' => $cardData['cvv']
                ]
            ]);
        } catch (CardException $e) {
//            dd($e->getError()->message);
//            return response()->json(["data"=>'',"errors"=>[],'message'=> $e->getError()->message],422);
            $token['error'] = $e->getError()->message;
        } catch (Exception $e) {
//            return response()->json(["data"=>'',"errors"=>[],'message'=>$e->getMessage()],422);
            $token['error'] = $e->getMessage();
        }
        return $token;
    }

    private function createCharge($tokenId, $amount)
    {
        $charge = null;
        try {
            $charge = $this->stripe->charges->create([
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $tokenId,
                'description' => 'My first payment'
            ]);
        } catch (Exception $e) {
            $charge['error'] = $e->getMessage();
        }
        return $charge;
    }

    public function knet_payment()
    {
        $responseURL = 'http://127.0.0.1:8000/api/payment/response.php';
        $successURL = 'http://127.0.0.1:8000/api/payment/success.php';
        $errorURL = 'http://127.0.0.1:8000/api/payment/error.php';
        $knetAlias = 'TEST_ALIAS';
        $resourcePath = '/home/mywebapp/';
        $amount = 150;
        $trackID = 'UNIQUETRACKID';

        try {

            $knetGateway = new KnetBilling([
                'alias'        => $knetAlias,
                'resourcePath' => $resourcePath
            ]);

            $knetGateway->setResponseURL($successURL);
            $knetGateway->setErrorURL($errorURL);
            $knetGateway->setAmt($amount);
            $knetGateway->setTrackId($trackID);

            $knetGateway->requestPayment();
            $paymentURL = $knetGateway->getPaymentURL();

            // helper function to redirect
            return header('Location: '.$paymentURL);

        } catch (\Exception $e) {

            // to debug error message
            // die(var_dump($e->getMessage()));
            return header('Location: '.$errorURL);

        }
    }

}
