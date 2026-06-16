<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>

    <h5 class="fw-semibold mb-1">Profile Information</h5>
    <p class="text-muted small mb-3">
        Update your account information and email address.
    </p>

    <form wire:submit.prevent="updateProfileInformation">

        <!-- Name -->
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text"
                   wire:model="name"
                   class="form-control"
                   placeholder="Enter your name">

            @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email"
                   wire:model="email"
                   class="form-control"
                   placeholder="Enter your email">

            @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Verify Notice -->
        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail
            && ! auth()->user()->hasVerifiedEmail())

            <div class="alert alert-warning small mt-3">

                Your email is not verified.

                <button wire:click.prevent="sendVerification"
                        class="btn btn-link p-0 ms-1 text-decoration-none">
                    Resend verification email
                </button>

                @if (session('status') === 'verification-link-sent')
                    <div class="text-success mt-2">
                        Verification link sent!
                    </div>
                @endif

            </div>

        @endif

        <!-- Save Button -->
        <div class="mt-3">
            <button type="submit"
                    class="btn btn-dark"
                    wire:loading.attr="disabled">

                <span wire:loading.remove>
                    Save Changes
                </span>

                <span wire:loading>
                    Saving...
                </span>

            </button>
        </div>

    </form>

</section>
