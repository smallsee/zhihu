<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use MercurySeries\Flashy\Flashy;

class EmailController extends Controller
{
    //
    public function verify($token){
        $user = User::where('confirmation_token',$token)->first();

        if (is_null($user)){
            Flashy::error('这个用激活码已经过期，或者没有这个用户','/');
            return redirect('/');
        }

        $user->is_active = 1;
        $user->confirmation_token = str_random(40);
        $user->save();
        Flashy::success('激活成功！');
        return redirect('/home');
    }
}
