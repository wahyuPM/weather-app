<?php

// app/Actions/Auth/ShowLoginAction.php
namespace App\Actions\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowLoginAction
{
    use AsAction;

    public function handle(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function asController(): Response
    {
        return $this->handle();
    }
}



// app/Actions/Auth/LogoutAction.php
namespace App\Actions\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class LogoutAction
{
    use AsAction;

    public function handle(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function asController(Request $request): RedirectResponse
    {
        return $this->handle($request);
    }
}
