<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Earn;
use Illuminate\Support\Carbon;

class AdsController extends Controller
{
    private $user;
    public function __construct()
    {   
        $this->middleware(['check_token']);
        $this->user = auth()->user();
    }

    public function limit()
    {   
        $limit = MAX_VIP_REWARD_ADS;
        if($limit){
            return $this->responseOK((int)$limit, 'success');
         }else{
            return $this->responseError();
         }
    }

    public function earn(Request $request)
    {   
        // $validator = \Validator::make($request->all(), [
        //     'g-recaptcha-response' => 'required|recaptcha'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }
        $task_id = $request->task_id;
        $user_id = $this->user->id;
        $address = $this->user->address;
        // dd($address);
        // if(!$this->user->is_vip){
        //     return $this->responseError('You are not in Mainnet List', 200);
        // }
        $now = Carbon::now();
        
        if($address)
        {   
            $balance = $this->check_vip($address);
            if($balance)
            {
                $total_earn = Earn::where('user_id', $user_id)->where('subject', 'ads')->whereDate('created_at', Carbon::today())->count();
                if($total_earn < (int)env('LIMIT_ADS_VIDEO')) {
                    $reward = (int)env('POINT_REWARD_ADS');
                    $price = $this->getPrice();
                    $reward =  (double)env('POINT_REWARD_ADS') / $price;
                    $reward = intval($reward);

                    $key = $user_id.'_'.$now;

                    if(true){
                        $history = Earn::insert(['user_id' => $user_id, 'status' => 1, 'reward' => $reward, 'subject' => 'ads', 'description' => 'Reward from ads', 'created_at' => $now, 'key' => $key]);
                        User::where('id', $user_id)->increment('pending_balance', $reward);
                        return $this->responseOK(true, 'success');
                    }else{
                        return $this->responseError();
                    }
                } else {
                    return $this->responseError('You watched max daily.', 200);
                }
            } else {
                return $this->responseError('You\'re not a VIP member.', 200);
            }
            
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
        
    }
    
    public function check_show_ads(Request $request)
    {
        $task_id = $request->task_id;
        $user_id = $this->user->id;
        $address = $this->user->address;
        // if(!$this->user->is_vip){
        //     return $this->responseError('You are not in Mainnet List', 200);
        // }
        // return $this->responseError('You can use P2C for now. We are updating Video ADS', 200);

        $now = Carbon::now();

        $after = 10; //minute
        // echo (Carbon::now()->toDateTimeString());
        $date = Carbon::now()->subMinutes($after);
        
        if($address && $this->check_vip($address))
        {   
            $total_earn2 = Earn::where('user_id', $user_id)->where('subject', 'ads')->where('created_at' , '>=', $date)->count();

            if($total_earn2 > 0) {
                
                $earn = Earn::where('user_id', $user_id)->where('subject', 'ads')->where('created_at' , '>=', $date)->orderBy('id', 'desc')->first();

                $created_at = ($earn->created_at->toDateTimeString());

                $count_down = $after  -  Carbon::parse($created_at)->diffInMinutes(Carbon::now());

                // $count_down = floor($count_down / 60).'h'.($count_down -   floor($count_down / 60) * 60);

                return $this->responseError('You can watch every '.$after.' minutes. After '.$count_down . 'm', 200);
            }


            $total_earn = Earn::where('user_id', $user_id)->where('subject', 'ads')->whereDate('created_at', Carbon::today())->count();
            if($total_earn < MAX_VIP_REWARD_ADS)
            { 

                    return $this->responseOK(['allow_show_ads' => 1], 'success');

            } else {
                return $this->responseError('You watched max daily.', 200);
            }
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
    }

    public function check_show_offers(Request $request)
    {
        
        return $this->responseOK(['allow_show_offers' => 1], 'success');
        $user_id = $this->user->id;
        $address = $this->user->address;
        // if(!$this->user->is_vip){
        //     return $this->responseError('You are not in Mainnet List', 200);
        // }
        if($address && $this->check_vip($address))
        {   
            $total_earn = Earn::where('user_id', $user_id)->where('subject', 'ads')->whereDate('created_at', Carbon::today())->count();
            
            if($total_earn < env('MAX_VIP_REWARD_ADS'))
            { 
                    return $this->responseOK(['allow_show_offers' => 1], 'success');

            } else {
                return $this->responseError('You watched max daily.', 200);
            }
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
    }


    


}
