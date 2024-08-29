<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Model\BusinessSetting;
use Illuminate\Support\Facades\Http;
use Brian2694\Toastr\Facades\Toastr;
use App\User;
use function App\CPU\translate;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class QibController extends Controller
{
    public function callback(Request $request)
    {
        try {
            Log::error('Error in QIB callback: ' . $request);
            $status = $request->status;
            $referenceId = $request->input('referenceId');
            $transactionId = $request->input('transactionId');
            $datetime = $request->input('datetime');
            $amount = $request->input('amount');
            // $check = $this->verifyTransaction($referenceId,$amount,$transactionId);
            if ($status == 'success') {
                Log::error('enter if condition success ');
                $unique_id = OrderManager::gen_unique_id();
                $order_ids = [];
                foreach (CartManager::get_cart_group_ids() as $group_id) {
                    $data = [
                        'payment_method' => 'stripe',
                        'order_status' => 'confirmed',
                        'payment_status' => 'paid',
                        'transaction_ref' => $referenceId,
                        'order_group_id' => $unique_id,
                        'cart_group_id' => $group_id
                    ];
                    $order_id = OrderManager::generate_order($data);
                    array_push($order_ids, $order_id);
                }
                CartManager::cart_clean();
                if (auth('customer')->check()) {
                    Log::error('no user');
                    Toastr::success('Payment success.');
                    return view(VIEW_FILE_NAMES['order_complete']);
                }
                return response()->json(['message' => 'Payment succeeded'], 200);
            }
            Log::error('request not success');
    
            if (auth('customer')->check()) {
                Log::error('no user');
                Toastr::error('Payment failed.');
                return redirect('/');
            }
            return response()->json(['message' => 'Payment failed'], 403);
        } catch (\Exception $e) {
            Log::error('Error in QIB callback: ' . $e->getMessage());
            Toastr::error('An error occurred. Please try again later.');
            return redirect('/');
        }
    }
    
    private function verifyTransaction($referenceId, $amount, $transactionId)
    {
        $gatewayId = "019856927";
        $secretKey = "pwxUSEo0DXoRfvsH";
    
        $hashable_string = "gatewayId=".$gatewayId.",amount=".$amount.",transactionId=".$transactionId;
    
        $signature = base64_encode(hash_hmac('sha256', $hashable_string, $secretKey, true));
    
        $requestParams = [
            'action' => 'status',
            'gatewayId' => $gatewayId,
            'signatureFields' => 'gatewayId,amount,transactionId',
            'signature' => $signature,
            'mode' => 'Live',
            'referenceId' => $referenceId,
            'amount' => $amount,
        ];
    
        $response = Http::post('https://paymentapi.qib.com.qa/api/gateway/v2.0', $requestParams);
        Log::info('QIB API Request: ' . json_encode($requestParams));

        if ($response->successful()) {
            $statusResponse = json_decode($response->body(), true);
    
            if ($statusResponse['referenceId'] == $referenceId) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        return false;
    }
    
    public function initiatePaymentAPI(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        $user = User::find(2);
        // $user = auth()->user();
        $order_id = "ORDER" . time() . mt_rand(1000, 9999);
        $amount = number_format($request->input('amount'), 2, '.', '');
        $name = $user->name;

        $data = [
            'action' => 'capture',
            'gatewayId' => '019856927',
            'signatureFields' => 'gatewayId,amount,referenceId',
            'signature' => $this->generateSignature($amount, $order_id),
            'referenceId' => $order_id,
            'amount' => $amount,
            'currency' => 'QAR',
            'mode' => 'LIVE',
            'description' => 'PRODUCT_DESC',
            'returnUrl' => url('/qib/callback'),
            'name' => $name,
            'address' => $user->street_address ?? 'no address found',
            'city' => $user->city ?? 'no city found',
            'state' => $user->city ?? 'no state found',
            'country' => 'QA',
            'phone' => $user->phone,
            'email' => $user->email,
        ];
        
        return response()->json(['data' => $data]);
    }

    // Helper method to generate the signature
    private function generateSignature($amount, $order_id)
    {
        $gatewayId = '019856927';
        $secretKey = 'pwxUSEo0DXoRfvsH';

        $hashable_string = "gatewayId=".$gatewayId.",amount=".$amount.",referenceId=".$order_id;
        return base64_encode(hash_hmac('sha256', $hashable_string, $secretKey, true));
    }


}
