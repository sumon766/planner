<div>
    <div class="mb-4">

        <div class="position-relative">

            <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>

            <input
                type="text"
                class="form-control ps-5"
                placeholder="Search questions & answers..."
                wire:model.live.debounce.300ms="search">

        </div>

    </div>

    @if($categories->isNotEmpty())

        <div class="mb-4">

            <div class="d-flex flex-wrap gap-2">

                <button
                    class="btn btn-sm {{ is_null($selectedCategory) ? 'btn-primary' : 'btn-outline-primary' }}"
                    wire:click="$set('selectedCategory', null)">

                    All

                </button>

                @foreach($categories as $category)

                    <button
                        class="btn btn-sm {{ $selectedCategory == $category->id ? 'btn-primary' : 'btn-outline-primary' }}"
                        wire:click="$set('selectedCategory', {{ $category->id }})">

                        {{ $category->name }}

                    </button>

                @endforeach

            </div>

        </div>

    @endif

    @if($questions->isEmpty())

        <div class="text-center py-5">

            <i class="fa-solid fa-circle-question fa-3x text-secondary mb-3"></i>

            @if($search)

                <h5>No matching questions found.</h5>

                <p class="text-muted">

                    Try a different keyword.

                </p>

            @else

                <h5>No interview questions found.</h5>

                <p class="text-muted">

                    Start building your interview preparation database.

                </p>

                <a
                    href="{{ route('interview-prep.create') }}"
                    class="btn btn-primary">

                    <i class="fa-solid fa-plus me-2"></i>

                    Add Question

                </a>

            @endif

            <a
                href="{{ route('interview-prep.create') }}"
                class="btn btn-primary">

                <i class="fa-solid fa-plus me-2"></i>

                Add Question

            </a>

        </div>

    @else

        <div class="accordion" id="questionAccordion">

            @foreach($questions as $question)

                <div class="accordion-item border rounded-4 shadow-sm mb-3 overflow-hidden">

                    <h2
                        class="accordion-header"
                        id="heading{{ $question->id }}">

                        <div class="d-flex justify-content-between align-items-center px-3 py-3">

                            <button
                                class="accordion-button collapsed shadow-none bg-white p-0 d-flex justify-content-between align-items-center"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $question->id }}"
                                aria-expanded="false">

                                <div class="flex-grow-1">

                                    <strong>
                                        {{ $question->question }}
                                    </strong>

{{--                                    <div class="mt-2">--}}
{{--                                        @foreach($question->categories as $category)--}}
{{--                                            <span class="badge bg-primary me-1">--}}
{{--                                                {{ $category->name }}--}}
{{--                                            </span>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
                                </div>

                                <i class="fa-solid fa-chevron-down ms-3 accordion-icon"></i>

                            </button>

                            <div class="d-flex align-items-center gap-2 ms-3">

                                <a
                                    href="{{ route('interview-prep.edit', $question) }}"
                                    class="btn btn-sm">

                                    <i class="fa-solid fa-pen"></i>

                                </a>

                                <button
                                    class="btn btn-sm text-danger"
                                    wire:click="confirmDelete({{ $question->id }})">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </div>

                        </div>

                    </h2>

                    <div
                        id="collapse{{ $question->id }}"
                        class="accordion-collapse collapse"
                        data-bs-parent="#questionAccordion">

                        <div class="accordion-body">

                            {!! $question->answer !!}

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

    @include('livewire.interview-questions.partials.delete-modal')

</div>
