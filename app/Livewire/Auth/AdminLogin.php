<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class AdminLogin extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $errorMessage = '';

    public function loginAdmin()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to login
        if (Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            $user = Auth::user();

            // Check if user is admin
            if ($user->isAdmin()) {
                return redirect()->route('dashboard')->with('success', 'Berhasil login sebagai admin');
            }

            // If not admin, logout and show error
            Auth::logout();
            $this->errorMessage = 'Anda tidak memiliki akses sebagai admin';
            return;
        }

        $this->errorMessage = 'Email atau password salah';
    }

    public function render()
    {
        return view('livewire.auth.admin-login');
    }
}
