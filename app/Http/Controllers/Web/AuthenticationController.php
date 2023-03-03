<?php

namespace App\Http\Controllers\Web;

use App\Mail\NewUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Auth;
class AuthenticationController extends Controller
{
    public function getSocialRedirect( ){
        return Socialite::driver('google')->with(['hd' => 'jumpsixmarketing.com'])->redirect();

    }

    public function getSocialCallback( ){
        $google_user = Socialite::driver('google')->stateless()->user();
        if(!User::where('email', $google_user->email)){
            $new_user = true;
        }
        $user = User::updateOrCreate([
            'email' => $google_user->email
        ], [
            'avatar' => $google_user->avatar,
            'name' => $google_user->name,

        ]);


        Auth::login($user);
        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
