<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="container-fluid">
        <div class="row min-vh-100">

            <!-- Left Panel -->
            <div class="col-lg-7 d-none d-lg-flex align-items-center justify-content-center text-white"
                 style="background: linear-gradient(135deg, #0f172a, #1f2937);">

                <div class="text-center px-5">
                    <h1 class="fw-bold mb-3">Welcome Back 👋</h1>
                    <p class="text-white-50">
                        Sign in to manage your dashboard, users and daily activities.
                    </p>
                </div>

            </div>

            <!-- Right Panel -->
            <div class="col-lg-5 d-flex align-items-center justify-content-center bg-light">

                <div class="card border-0 shadow-lg p-4 p-md-5 w-100"
                     style="max-width: 420px; border-radius: 16px;">

                    <!-- Header -->
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">{{ config('app.name', 'Planner') }}</h4>
                        <small class="text-muted">Login to your account</small>
                    </div>

                    <!-- Status message -->
                    @if (session('status'))
                        <div class="alert alert-success py-2 small">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form wire:submit="login">

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   wire:model="form.email"
                                   class="form-control form-control-lg"
                                   placeholder="you@example.com">
                            @error('form.email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
                                   wire:model="form.password"
                                   class="form-control form-control-lg"
                                   placeholder="••••••••">
                            @error('form.password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember + Forgot -->
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div class="form-check">
                                <input type="checkbox"
                                       wire:model="form.remember"
                                       class="form-check-input"
                                       id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-decoration-none small text-primary"
                                   wire:navigate>
                                    Forgot password?
                                </a>
                            @endif

                        </div>

                        <!-- Button -->
                        <button type="submit"
                                class="btn btn-dark btn-lg w-100">
                            Sign In
                        </button>

                    </form>

                    <!-- Divider -->
                    <div class="text-center my-3">
                        <span class="text-muted small">OR</span>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <span class="text-muted small">Don’t have an account?</span>
                        <a href="{{ route('register') }}"
                           class="fw-semibold text-decoration-none text-primary ms-1"
                           wire:navigate>
                            Create one here
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
