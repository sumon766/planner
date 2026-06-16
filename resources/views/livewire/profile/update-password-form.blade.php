<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>

    <h5 class="fw-semibold mb-1">Update Password</h5>
    <p class="text-muted small mb-3">
        Ensure your account is using a strong password for better security.
    </p>

    <form wire:submit.prevent="updatePassword">

        <!-- Current Password -->
        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password"
                   wire:model="current_password"
                   class="form-control"
                   placeholder="Enter current password">

            @error('current_password')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password"
                   wire:model="password"
                   class="form-control"
                   placeholder="Enter new password">

            @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password"
                   wire:model="password_confirmation"
                   class="form-control"
                   placeholder="Confirm new password">

            @error('password_confirmation')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-3">

            <!-- Save Button -->
            <button type="submit"
                    class="btn btn-dark"
                    wire:loading.attr="disabled">

                <span wire:loading.remove>Save Changes</span>
                <span wire:loading>Updating...</span>

            </button>

            <!-- Success message -->
            <div x-data="{ show: false }"
                 x-on:password-updated.window="show = true; setTimeout(() => show = false, 3000)"
                 x-show="show"
                 x-transition
                 class="alert alert-success py-2 small mt-2 mb-0">
                Password updated successfully.
            </div>

            <!-- Livewire event listener replacement -->
            <div x-data="{ show: false }"
                 x-on:password-updated.window="show = true; setTimeout(() => show = false, 2000)"
                 x-show="show"
                 class="text-success small">
                Password updated successfully.
            </div>

        </div>

    </form>

</section>
