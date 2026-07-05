@if($showDeleteModal)

    <div
        class="modal fade show d-block"
        tabindex="-1"
        style="background: rgba(0,0,0,.5);">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <h5 class="modal-title text-danger">

                        <i class="fa-solid fa-triangle-exclamation me-2"></i>

                        Delete Task

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        wire:click="$set('showDeleteModal', false)">
                    </button>

                </div>

                <div class="modal-body">

                    <p class="mb-2">

                        {{ $modalMessage }}

                    </p>

                    <small class="text-danger">

                        This action cannot be undone.

                    </small>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        wire:click="$set('showDeleteModal', false)">

                        Cancel

                    </button>

                    <button
                        class="btn btn-danger"
                        wire:click="deleteTask">

                        <i class="fa-solid fa-trash me-1"></i>

                        Delete

                    </button>

                </div>

            </div>

        </div>

    </div>

@endif
