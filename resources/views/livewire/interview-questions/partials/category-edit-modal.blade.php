<div
    class="modal fade {{ $showEditModal ? 'show' : '' }}"
    tabindex="-1"
    style="
        display:block;
        background:rgba(0,0,0,.5);
        opacity:{{ $showEditModal ? '1' : '0' }};
        visibility:{{ $showEditModal ? 'visible' : 'hidden' }};
        transition:opacity .25s ease, visibility .25s ease;
    ">

    <div
        class="modal-dialog modal-lg modal-dialog-centered"
        style="
            transform:{{ $showEditModal ? 'translateY(0) scale(1)' : 'translateY(-20px) scale(.95)' }};
            transition:transform .25s ease;
        ">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fa-solid fa-pen-to-square me-2"></i>

                    Edit Category

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    wire:click="$set('showEditModal', false)">
                </button>

            </div>

            <div class="modal-body">

                {{-- Name --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Name
                    </label>

                    <input
                        type="text"
                        class="form-control @error('editingName') is-invalid @enderror"
                        wire:model.live="editingName">

                    @error('editingName')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                {{-- Slug --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Slug
                    </label>

                    <input
                        type="text"
                        class="form-control @error('editingSlug') is-invalid @enderror"
                        wire:model.live="editingSlug">

                    @error('editingSlug')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                {{-- Type --}}
{{--                <div class="mb-3">--}}

{{--                    <label class="form-label fw-semibold">--}}
{{--                        Type--}}
{{--                    </label>--}}

{{--                    <input--}}
{{--                        type="text"--}}
{{--                        class="form-control @error('editingType') is-invalid @enderror"--}}
{{--                        wire:model.live="editingType"--}}
{{--                        placeholder="Laravel, PHP, SQL, HR...">--}}

{{--                    @error('editingType')--}}
{{--                    <div class="invalid-feedback">--}}
{{--                        {{ $message }}--}}
{{--                    </div>--}}
{{--                    @enderror--}}

{{--                </div>--}}

                {{-- Description --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Description
                    </label>

                    <textarea
                        rows="4"
                        class="form-control @error('editingDescription') is-invalid @enderror"
                        wire:model.live="editingDescription"></textarea>

                    @error('editingDescription')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                {{-- Active --}}
                <div class="form-check form-switch">

                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="editingIsActive"
                        wire:model.live="editingIsActive">

                    <label
                        class="form-check-label"
                        for="editingIsActive">

                        Active Category

                    </label>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    wire:click="$set('showEditModal', false)">

                    Cancel

                </button>

                <button
                    type="button"
                    class="btn btn-warning"
                    wire:click="update"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove>

                        <i class="fa-solid fa-floppy-disk me-2"></i>

                        Update Category

                    </span>

                    <span wire:loading>

                        <span class="spinner-border spinner-border-sm me-2"></span>

                        Updating...

                    </span>

                </button>

            </div>

        </div>

    </div>

</div>
