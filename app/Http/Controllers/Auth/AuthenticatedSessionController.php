<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function telegramAuth(Request $request){
        Log::info(print_r($request->all(), true));

        $tgId = $request->id;

        Log::info("tgId=>$tgId");

        $user = User::query()
            ->where("email", "$tgId@your-cashman.ru")
            ->first();

        if (is_null($user))
            return response()->redirectTo("login");

        Log::info("here 1");

        Auth::attempt(['email' => $user->email,'password'=>$tgId]);

        Log::info("here 2");

        $request->session()->regenerate();

        Log::info("here 3");

        return redirect()->route('dashboard');
    }
    /**
     * Display the login view.
     */
    public function create()
    {
        return view("admin.login");
        /*return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);*/
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
