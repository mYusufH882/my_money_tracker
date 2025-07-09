<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Register - My Money Tracker')]
class Register extends Component
{
    #[Validate('required|min:2')]
    public string $name = '';

    #[Validate('required|email|unique:users')]
    public string $email = '';

    #[Validate('required|min:8')]
    public string $password = '';

    #[Validate('required|same:password')]
    public string $password_confirmation = '';

    public function register()
    {
        // Validate
        $this->validate([
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.min' => 'Nama minimal 2 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok'
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Login user
            Auth::login($user);
            session()->regenerate();

            // Flash success
            session()->flash('success', 'Registrasi berhasil! Selamat datang.');

            // Redirect
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            $this->addError('email', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
