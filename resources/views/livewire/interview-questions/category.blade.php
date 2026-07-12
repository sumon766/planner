<div class="row">

    <div class="col-lg-4">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <h6 class="fw-bold mb-4">
                    Add Category
                </h6>

                {{-- Name --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Name
                    </label>

                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        wire:model.live="name">

                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                {{-- Slug --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Slug
                    </label>

                    <input
                        type="text"
                        class="form-control @error('slug') is-invalid @enderror"
                        wire:model.live="slug">

                    @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                {{-- Type --}}
{{--                <div class="mb-3">--}}

{{--                    <label class="form-label fw-semibold">--}}
{{--                        Type--}}
{{--                    </label>--}}

{{--                    <input--}}
{{--                        type="text"--}}
{{--                        class="form-control"--}}
{{--                        wire:model.live="type"--}}
{{--                        placeholder="Laravel, PHP, HR, SQL...">--}}

{{--                </div>--}}

                {{-- Description --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Description
                    </label>

                    <textarea
                        rows="3"
                        class="form-control"
                        wire:model.live="description"></textarea>

                </div>

                {{-- Active --}}
                <div class="form-check form-switch mb-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        wire:model.live="is_active">

                    <label class="form-check-label">

                        Active

                    </label>

                </div>

                <button
                    class="btn btn-primary w-100"
                    wire:click="save"
                    wire:loading.attr="disabled">

                    <i class="fa-solid fa-plus me-2"></i>

                    Create Category

                </button>

            </div>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <table class="table align-middle">

                    <thead>

                    <tr>

                        <th>Name</th>

                        <th>Type</th>

                        <th>Status</th>

                        <th width="140"></th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($categories as $category)

                        <tr>

                            <td>

                                <div class="fw-semibold">

                                    {{ $category->name }}

                                </div>

                                <small class="text-muted">

                                    {{ $category->slug }}

                                </small>

                            </td>

                            <td>

                                {{ $category->type ?: '-' }}

                            </td>

                            <td>

                                @if($category->is_active)

                                    <span class="badge bg-success">

                Active

            </span>

                                @else

                                    <span class="badge bg-secondary">

                Inactive

            </span>

                                @endif

                            </td>

                            <td class="text-end">

                                <button
                                    class="btn btn-light btn-sm"
                                    wire:click="edit({{ $category->id }})">

                                    <i class="fa-solid fa-pen"></i>

                                </button>

                                <button
                                    class="btn btn-light btn-sm text-danger"
                                    wire:click="confirmDelete({{ $category->id }})">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center py-5 text-muted">

                                No categories found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    @include('livewire.interview-questions.partials.category-edit-modal')

    @include('livewire.interview-questions.partials.category-delete-modal')

</div>
