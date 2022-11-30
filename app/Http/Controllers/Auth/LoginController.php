<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public const DRIVER = 'google';
    public const LAT = 'lat';
    public const LON = 'lon';
    public const TOKEN_NAME = 'Laravel Password Grant Client';

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider(Request $request)
    {
        return Socialite::driver(self::DRIVER)
            ->redirect()
            ->withCookies([
                cookie(self::LAT, $request->get(self::LAT), 60),
                cookie(self::LON, $request->get(self::LON), 60)
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request)
    {
        $lat = $request->cookie(self::LAT);
        $lon = $request->cookie(self::LON);
        
        $googleUser = Socialite::driver(self::DRIVER)->stateless()->user();

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

        $token = $user->createToken(self::TOKEN_NAME)->accessToken;
        return response(['token' => $token], 200);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
