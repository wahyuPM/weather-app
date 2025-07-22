<?php

// app/Actions/Profile/EditProfileAction.php
namespace App\Actions\Profile;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EditProfileAction
{
    use AsAction;

    public function handle(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function asController(Request $request): Response
    {
        return $this->handle($request);
    }
}
