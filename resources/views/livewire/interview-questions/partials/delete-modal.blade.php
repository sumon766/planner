<div
    class="modal fade {{ $showDeleteModal ? 'show' : '' }}"
    tabindex="-1"
    style="
        display: block;
        background: rgba(0,0,0,.5);
        opacity: {{ $showDeleteModal ? '1' : '0' }};
        visibility: {{ $showDeleteModal ? 'visible' : 'hidden' }};
        transition: opacity .25s ease, visibility .25s ease;
    "
>

    <div
        class="modal-dialog modal-dialog-centered"
        style="
            transform: {{ $showDeleteModal ? 'translateY(0) scale(1)' : 'translateY(-20px) scale(.95)' }};
            transition: transform .25s ease;
        "
    >

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5 class="modal-title text-danger">

                    <i class="fa-solid fa-triangle-exclamation me-2"></i>

                    Delete Interview Question

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
                    wire:click="deleteQuestion">

                    <i class="fa-solid fa-trash me-1"></i>

                    Delete Question

                </button>

            </div>

        </div>

    </div>

</div>
