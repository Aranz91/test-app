<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToProvider(Request $request)
    {
        return Socialite::driver('google')->redirect()->withCookies([cookie('lat', $request->get('lat'), 60), cookie('lon', $request->get('lon'), 60)]);
    }

    public function handleProviderCallback(Request $request)
    {
        Auth::logout();
        $lat = $request->cookie('lat');
        $lon = $request->cookie('lon');
        
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate([
            'google_id' => $googleUser->id
        ], [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
            'lat' => $lat,
            'lon' => $lon
        ]);

        Auth::login($user);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
//        return view('afterAuth');
        $response = ['token' => $token];
        return response($response, 200);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
