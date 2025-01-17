<?php

namespace App\Http\Controllers\Seller\Auth;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Session;
use function App\CPU\translate;

class RegisterController extends Controller
{
    public function create()
    {
        $business_mode=Helpers::get_business_settings('business_mode');
        $seller_registration=Helpers::get_business_settings('seller_registration');
        if((isset($business_mode) && $business_mode=='single') || (isset($seller_registration) && $seller_registration==0))
        {
            Toastr::warning(translate('access_denied!!'));
            return redirect('/');
        }
        return view(VIEW_FILE_NAMES['seller_registration']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image'         => 'required|mimes: jpg,jpeg,png,gif',
            'logo'          => 'required|mimes: jpg,jpeg,png,gif',
            'banner'        => 'required|mimes: jpg,jpeg,png,gif',
            'bottom_banner' => 'mimes: jpg,jpeg,png,gif',
            'email'         => 'required|unique:sellers',
            'shop_address'  => 'required',
            'f_name'        => 'required',
            'l_name'        => 'required',
            'shop_name'     => 'required',
            'phone'         => 'required',
            'holder_name'         => 'required',
            'bank_name'         => 'required',
            'branch'         => 'required',
            'account_no'         => 'required',
            'password'      => 'required|min:8',
        ]);

        // if($request['from_submit'] != 'admin') {
        //     //recaptcha validation
        //     $recaptcha = Helpers::get_business_settings('recaptcha');
        //     if (isset($recaptcha) && $recaptcha['status'] == 1) {
        //         try {
        //             $request->validate([
        //                 'g-recaptcha-response' => [
        //                     function ($attribute, $value, $fail) {
        //                         $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
        //                         $response = $value;
        //                         $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
        //                         $response = \file_get_contents($url);
        //                         $response = json_decode($response);
        //                         if (!$response->success) {
        //                             $fail(\App\CPU\translate('ReCAPTCHA Failed'));
        //                         }
        //                     },
        //                 ],
        //             ]);
        //         } catch (\Exception $exception) {
        //         }
        //     } else {
        //         if (strtolower($request->default_recaptcha_id_seller_regi) != strtolower(Session('default_recaptcha_id_seller_regi'))) {
        //             Session::forget('default_recaptcha_id_seller_regi');
        //             return back()->withErrors(\App\CPU\translate('Captcha Failed'));
        //         }
        //     }
        // }

        DB::transaction(function ($r) use ($request) {
            $seller = new Seller();
            $seller->f_name = $request->f_name;
            $seller->l_name = $request->l_name;
            $seller->phone = $request->phone;
            $seller->holder_name = $request->holder_name;
            $seller->bank_name = $request->bank_name;
            $seller->branch = $request->branch;
            $seller->account_no = $request->account_no;
            $seller->email = $request->email;
            $seller->image = ImageManager::upload('seller/', 'png', $request->file('image'));
            $seller->password = bcrypt($request->password);
            $seller->status =  $request->status == 'approved'?'approved': "pending";
            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->shop_address;
            $shop->contact = $request->phone;
            $shop->image = ImageManager::upload('shop/', 'png', $request->file('logo'));
            $shop->banner = ImageManager::upload('shop/banner/', 'png', $request->file('banner'));
            $shop->bottom_banner = ImageManager::upload('shop/banner/', 'png', $request->file('bottom_banner'));
            $shop->stores_count = $request->stores_count;
            $shop->store_type_id = $request->store_type_id; 
            $shop->save();

            DB::table('seller_wallets')->insert([
                'seller_id' => $seller['id'],
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        });

        if($request->status == 'approved'){
            Toastr::success('Shop apply successfully!');
            return back();
        }else{
            Toastr::success('Shop apply successfully!');
            return redirect()->route('seller.auth.login');
        }


    }
}
