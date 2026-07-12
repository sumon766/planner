<div class="modal fade {{ $showDeleteModal ? 'show' : '' }}"
     tabindex="-1"
     style="display:block;background:rgba(0,0,0,.5);visibility:{{ $showDeleteModal ? 'visible':'hidden' }};">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="text-danger">

                    Delete Category

                </h5>

                <button
                    class="btn-close"
                    wire:click="$set('showDeleteModal', false)">
                </button>

            </div>

            <div class="modal-body">

                {{ $modalMessage }}

                <br>

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
                    wire:click="delete">

                    Delete

                </button>

            </div>

        </div>

    </div>

</div>
