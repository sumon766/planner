<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div class="container-fluid">
        <div class="row min-vh-100">

            <!-- Left Panel -->
            <div class="col-lg-7 d-none d-lg-flex align-items-center justify-content-center text-white"
                 style="background: linear-gradient(135deg, #0f172a, #1f2937);">

                <div class="text-center px-5">
                    <h1 class="fw-bold mb-3">Verify Your Email 📩</h1>
                    <p class="text-white-50">
                        We’ve sent a verification link to your email address.
                        Please verify your account to continue.
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
                        <small class="text-muted">Email verification required</small>
                    </div>

                    <!-- Info text -->
                    <p class="text-muted small text-center mb-3">
                        Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent you.
                    </p>

                    <!-- Success message -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success py-2 small text-center">
                            A new verification link has been sent to your email.
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="d-grid gap-2">

                        <!-- Resend Email -->
                        <button wire:click="sendVerification"
                                class="btn btn-dark btn-lg">
                            Resend Verification Email
                        </button>

                        <!-- Logout -->
                        <button wire:click="logout"
                                class="btn btn-outline-secondary">
                            Log Out
                        </button>

                    </div>

                    <!-- Optional helper -->
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Didn’t receive the email? Check your spam folder.
                        </small>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
