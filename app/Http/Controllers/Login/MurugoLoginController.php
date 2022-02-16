<?php

namespace App\Http\Controllers\Login;

use RwandaBuild\MurugoAuth\Facades\MurugoAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MurugoLoginController extends Controller
{



    public function redirectToMurugo()
    {
        return MurugoAuth::redirect();
    }


    public function murugoCallback()
    {
        $murugoUser = MurugoAuth::user();

        $user = $murugoUser->user;

        if (!$user) {
            $user = $murugoUser->user()->create([
                'name' => $murugoUser->name,
                'bio' => Inspiring::quote()
            ]);
        }

        Auth::login($user);

        return redirect()->route('home');
    }
}
