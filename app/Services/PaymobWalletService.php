<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use Illuminate\Support\Facades\Http;
use Nafezly\Payments\Exceptions\MissingPaymentInfoException;

class PaymobWalletService extends Controller
{
    private $paymob_api_key;
    private $paymob_wallet_integration_id;

    public function __construct()
    {
        $this->paymob_api_key = env('PAYMOB_API_KEY');
        $this->currency = config("nafezly-payments.PAYMOB_CURRENCY");
        $this->paymob_wallet_integration_id = env("PAYMOB_WALLET_INTEGRATION_ID");
    }

    /**
     * @param $amount
     * @param null $user_id
     * @param null $user_first_name
     * @param null $user_last_name
     * @param null $user_email
     * @param null $user_phone
     * @param null $source
     * @return void
     * @throws MissingPaymentInfoException
     */
    public function pay($request, $amount = null, $user_id = null, $user_first_name = null, $user_last_name = null, $user_email = null, $user_phone = null, $source = null)
    {
//        $this->setPassedVariablesToGlobal($amount,$user_id,$user_first_name,$user_last_name,$user_email,$user_phone,$source);
//        $required_fields = ['amount', 'user_first_name', 'user_last_name', 'user_email', 'user_phone'];
//        $this->checkRequiredFields($required_fields, 'PayMob');

        $request_new_token = Http::withHeaders(['content-type' => 'application/json'])
            ->post('https://accept.paymobsolutions.com/api/auth/tokens', [
                "api_key" => $this->paymob_api_key
            ])->json();

        $get_order = Http::withHeaders(['content-type' => 'application/json'])
            ->post('https://accept.paymobsolutions.com/api/ecommerce/orders', [
                "auth_token" => $request_new_token['token'],
                "delivery_needed" => "false",
                "amount_cents" => $amount * 100,
                "items" => []
            ])->json();
        $get_url_token = Http::withHeaders(['content-type' => 'application/json'])
            ->post('https://accept.paymobsolutions.com/api/acceptance/payment_keys', [
                "auth_token" => $request_new_token['token'],
                "expiration" => 36000,
                "amount_cents" => $get_order['amount_cents'],
                "order_id" => $get_order['id'],
                "billing_data" => [
                    "apartment" => "NA",
                    "email" => $user_email,
                    "floor" => "NA",
                    "first_name" => $user_first_name,
                    "street" => "NA",
                    "building" => "NA",
                    "phone_number" => $user_phone,
                    "shipping_method" => "NA",
                    "postal_code" => "NA",
                    "city" => "NA",
                    "country" => "NA",
                    "last_name" => $user_last_name,
                    "state" => "NA"
                ],
                "currency" => $this->currency,
                "integration_id" => $this->paymob_wallet_integration_id,
                'lock_order_when_paid'=>true
            ])->json();

        $get_pay_link = Http::withHeaders(['content-type' => 'application/json'])
            ->post('https://accept.paymob.com/api/acceptance/payments/pay', [
                'source'=>[
                    "identifier"=>'01010101010',
                    'subtype'=>"WALLET"
                ],
                "payment_token"=>$get_url_token['token']
            ])->json();
//        dd($get_pay_link);
        $inputs = $request->all();
        $inputs['user_id'] = $user_id;
        $payment = PaymentLog::create(
            ['payment_id'=>$get_pay_link['id'],'request_variables'=> $inputs,'payment_type'=>'wallet']
        );
//        dd($payment->request_variables);
        return self::returnResponseDataApi(['payment_url' => $get_pay_link['redirect_url']],"تم استلام لينك الدفع بنجاح ",200);
//        return [
//            'payment_id'=>$get_order['id'],
//            'html' => $get_pay_link['iframe_redirection_url'],
//            'redirect_url'=>$get_pay_link['redirect_url']
//        ];
    }


}
