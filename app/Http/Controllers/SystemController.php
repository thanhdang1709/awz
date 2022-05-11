<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
class SystemController extends Controller
{   



    public function __construct() {
        $this->middleware(['api_throttle:10,1']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function app_version()
    {   
        $data = [
            'version' => env('APP_VERSION'),
            'home_banner' => 'https://cdn.azworld.network/icon.gif',
            'member_banner' => 'https://1014081465-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F6SJlk6stT2h3qjzcShZJ%2Fuploads%2F3EDIVnicUoA5AZWkRVFa%2Fmember.jpg?alt=media&token=20300823-a7a6-4e64-85b4-365d1147959e',
            'symbol' => 'AZW',
            'min_vip' => env('AMOUNT_TOKEN_IS_VIP'),
            'page_ref_text' => 'Send a referral link to your friend\nIf the people you refer go shopping - You will get up to 20% Cashback commission  in  that order\nMax 5 users / day',
            'page_ref_how_it_work' => 'A referral program is a system that incentivizes previous customers to recommend your products to their family and friends. Retail stores create their own referral programs as a way to reach more people. It\'s a marketing strategy that asks previous happy, loyal customers to become brand advocates',
            'page_wheel_text' => 'The total value of the payout pool is '.env('POOL').', which will decrease after each spin.',
            'home_no_data_earning_today' => 'You have not earned AZW token today, or quickly get rewarded by referring friends or using lucky wheel, earn money by reading news, watching ads'
        ];

        return $this->responseOK($data, 'success');
    }

    public function guest_token()
    {
        return $this->responseOK(env('GUEST_TOKEN'), 'success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function allow_function(Request $request)
    {
        $data = [
            'cashback' => 1,
            'ptc' => 1,
        ];

        return $this->responseOK($data, 'success');
    }

    public function home_alert(Request $request)
    {
        $data = env("HOME_NOTICE");

        return $this->responseOK($data, 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function currency()
    {   
        $currencies = Currency::where('status', 1)->get();
       
        if($currencies) {
            return $this->responseOK(['items' => $currencies], 'success');
        } 
        return $this->responseError();


        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
