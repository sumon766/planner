<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section>

    <h5 class="fw-semibold text-danger mb-1">Delete Account</h5>

    <p class="text-muted small mb-3">
        Once your account is deleted, all data will be permanently removed.
        Please proceed carefully.
    </p>

    <!-- Delete Button -->
    <button type="button"
            class="btn btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#deleteAccountModal">
        Delete Account
    </button>

    <!-- Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title text-danger fw-semibold">
                        Confirm Account Deletion
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">

                    <p class="text-muted small">
                        Are you sure you want to delete your account?
                        This action cannot be undone.
                    </p>

                    <p class="text-muted small">
                        Please enter your password to confirm.
                    </p>

                    <!-- Password -->
                    <div class="mt-3">
                        <label class="form-label">Password</label>
                        <input type="password"
                               wire:model="password"
                               class="form-control"
                               placeholder="Enter your password">

                        @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button"
                            class="btn btn-danger"
                            wire:click="deleteUser"
                            wire:loading.attr="disabled">

                        <span wire:loading.remove>
                            Delete Account
                        </span>

                        <span wire:loading>
                            Deleting...
                        </span>

                    </button>

                </div>

            </div>

        </div>
    </div>

</section>
