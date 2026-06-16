<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="container-fluid">
        <div class="row min-vh-100">

            <!-- Left Panel -->
            <div class="col-lg-7 d-none d-lg-flex align-items-center justify-content-center text-white"
                 style="background: linear-gradient(135deg, #0f172a, #1f2937);">

                <div class="text-center px-5">
                    <h1 class="fw-bold mb-3">Forgot Password? 🔐</h1>
                    <p class="text-white-50">
                        No worries. We’ll send you a reset link to regain access to your account.
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
                        <small class="text-muted">Reset your password</small>
                    </div>

                    <!-- Info text -->
                    <p class="text-muted small text-center mb-3">
                        Enter your email address and we’ll send you a reset link.
                    </p>

                    <!-- Success message -->
                    @if (session('status'))
                        <div class="alert alert-success py-2 small">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form wire:submit="sendPasswordResetLink">

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email"
                                   wire:model="email"
                                   class="form-control form-control-lg"
                                   placeholder="you@example.com"
                                   required>

                            @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                                class="btn btn-dark btn-lg w-100">
                            Send Reset Link
                        </button>

                    </form>

                    <!-- Back to login -->
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}"
                           class="text-decoration-none small text-primary"
                           wire:navigate>
                            ← Back to login
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
