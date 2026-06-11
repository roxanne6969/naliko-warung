<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended((auth()->user() && auth()->user()->role === 'admin' ? route('admin.dashboard', absolute: false) : route('kasir.dashboard', absolute: false)))
                    : view('auth.verify-email');
    }
}
