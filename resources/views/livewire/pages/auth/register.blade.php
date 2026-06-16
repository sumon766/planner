<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="container-fluid">
        <div class="row min-vh-100">

            <!-- Left Panel -->
            <div class="col-lg-7 d-none d-lg-flex align-items-center justify-content-center text-white"
                 style="background: linear-gradient(135deg, #0f172a, #1f2937);">

                <div class="text-center px-5">
                    <h1 class="fw-bold mb-3">Create Your Account 🚀</h1>
                    <p class="text-white-50">
                        Start managing your dashboard, tasks and users in minutes.
                    </p>
                </div>

            </div>

            <!-- Right Panel -->
            <div class="col-lg-5 d-flex align-items-center justify-content-center bg-light">

                <div class="card border-0 shadow-lg p-4 p-md-5 w-100"
                     style="max-width: 440px; border-radius: 16px;">

                    <!-- Header -->
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">{{ config('app.name', 'Planner') }}</h4>
                        <small class="text-muted">Create a new account</small>
                    </div>

                    <form wire:submit="register">

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text"
                                   wire:model="name"
                                   class="form-control form-control-lg"
                                   placeholder="Your name">
                            @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   wire:model="email"
                                   class="form-control form-control-lg"
                                   placeholder="you@example.com">
                            @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
                                   wire:model="password"
                                   class="form-control form-control-lg"
                                   placeholder="••••••••">
                            @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password"
                                   wire:model="password_confirmation"
                                   class="form-control form-control-lg"
                                   placeholder="••••••••">
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <a href="{{ route('login') }}"
                               class="text-decoration-none small text-primary"
                               wire:navigate>
                                Already have an account?
                            </a>

                        </div>

                        <!-- Submit -->
                        <button type="submit"
                                class="btn btn-dark btn-lg w-100">
                            Create Account
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>
