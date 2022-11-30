<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public const DRIVER = 'google';
    public const LAT = 'lat';
    public const LON = 'lon';
    public const TOKEN_NAME = 'Laravel Password Grant Client';

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;
    
    /**
     * LoginController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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

        $user = $this->userRepository->getByGoogleId($googleUser->id);

        $userData = [
            'google_id' => $googleUser->id,
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'avatar' => $googleUser->avatar,
            'lat' => $lat,
            'lon' => $lon
        ];

        if ($user) {
            $this->userRepository->update($userData);
        } else {
            $user = $this->userRepository->create($userData);
        }

        $token = $user->createToken(self::TOKEN_NAME)->accessToken;
        return response(['token' => $token], 200);
    }
}
