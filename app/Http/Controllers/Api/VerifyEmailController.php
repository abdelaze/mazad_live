<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
           // return redirect(url('/'). '/email/verify/already-success');
            return redirect()->route('verify.already-success');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

          return redirect()->route('verify.success');
    }
}
