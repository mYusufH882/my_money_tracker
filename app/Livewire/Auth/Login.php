<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login - Money Tracker')]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:1')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        // Validate input
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:1'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi'
        ]);

        // Attempt login
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            // Flash success message
            session()->flash('success', 'Login berhasil!');

            // Redirect to dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Add error if failed
        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
