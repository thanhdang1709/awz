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
        $task_id = $request->task_id;
        $user_id = $this->user->id;

        if(!$this->user->address)
        {   
            $total_earn = Earn::where('user_id', $user_id)->where('subject', 'ads')->whereDate('created_at', Carbon::today())->count();
            if($total_earn < MAX_VIP_CLICK_TASK) {
                $reward = POINT_REWARD_ADS;
                if(true){
                    $history = Earn::insert(['user_id' => $user_id, 'status' => 2, 'reward' => $reward, 'subject' => 'ads', 'description' => 'Reward point from ads', 'created_at' => Carbon::now()]);
                    User::where('id', $user_id)->increment('balance', $reward);
                    return $this->responseOK(null, 'success');
                }else{
                    return $this->responseError();
                }
            } else {
                return $this->responseError('You watched max daily.', 200);
            }
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
        
    }
    
    public function check_show_ads(Request $request)
    {
        $task_id = $request->task_id;
        $user_id = $this->user->id;

        if(!$this->user->address)
        {   
            $total_earn = Earn::where('user_id', $user_id)->where('subject', 'ads')->whereDate('created_at', Carbon::today())->count();
            if($total_earn < MAX_VIP_CLICK_TASK)
            {
                    return $this->responseOK(['allow_show_ads' => 1], 'success');

            } else {
                return $this->responseError('You watched max daily.', 200);
            }
           
        } else {
            return $this->responseError('You\'re not a VIP member.', 200);
        }
    }
}
