<div>

    @if($questions->isEmpty())

        <div class="text-center py-5">

            <i class="fa-solid fa-circle-question fa-3x text-secondary mb-3"></i>

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
                                class="accordion-button collapsed shadow-none bg-white p-0"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $question->id }}">

                                <div>

                                    <strong>

                                        {{ $question->question }}

                                    </strong>

                                    <div class="mt-2">

                                        @foreach($question->categories as $category)

                                            <span class="badge bg-primary me-1">

                                                {{ $category->name }}

                                            </span>

                                        @endforeach

                                    </div>

                                </div>

                            </button>

                            <div class="d-flex align-items-center gap-2 ms-3">

                                <a
                                    href="{{ route('interview-prep.edit', $question) }}"
                                    class="btn btn-light btn-sm">

                                    <i class="fa-solid fa-pen"></i>

                                </a>

                                <button
                                    class="btn btn-light btn-sm text-danger"
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
