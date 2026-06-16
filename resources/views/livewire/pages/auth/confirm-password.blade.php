<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

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
                    <h1 class="fw-bold mb-3">Secure Area 🔐</h1>
                    <p class="text-white-50">
                        Please confirm your password to continue accessing this section.
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
                        <small class="text-muted">Password confirmation required</small>
                    </div>

                    <!-- Info -->
                    <p class="text-muted small text-center mb-3">
                        This is a secure area. Please confirm your password before continuing.
                    </p>

                    <form wire:submit="confirmPassword">

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password"
                                   wire:model="password"
                                   class="form-control form-control-lg"
                                   placeholder="Enter your password"
                                   required>

                            @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                                class="btn btn-dark btn-lg w-100">
                            Confirm Password
                        </button>

                    </form>

                    <!-- Optional back link -->
                    <div class="text-center mt-3">
                        <a href="{{ route('dashboard') }}"
                           class="text-decoration-none small text-primary"
                           wire:navigate>
                            ← Back to dashboard
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
