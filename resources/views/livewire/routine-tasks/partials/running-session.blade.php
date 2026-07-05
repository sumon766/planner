@if($runningTimer)

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">

        <div class="card-body p-0">

            <div class="row g-0 align-items-center">

                {{-- Left --}}
                <div class="col-lg-8 p-4 p-lg-5">

                    <div class="d-flex align-items-center gap-2 mb-4">

                    <span class="badge bg-success rounded-pill px-3 py-2">

                        <i class="fa-solid fa-circle fa-2xs me-2"></i>

                        LIVE SESSION

                    </span>

                        <span class="text-success small fw-semibold">

                        Focus Mode Enabled

                    </span>

                    </div>

                    <h2 class="fw-bold mb-2">

                        {{ $runningTimer->task->parent?->title ?? $runningTimer->task->title }}

                    </h2>

                    @if($runningTimer->task->parent)

                        <div class="text-secondary fs-5 mb-4">

                            <i class="fa-solid fa-arrow-turn-down me-2"></i>

                            {{ $runningTimer->task->title }}

                        </div>

                    @endif

                    <div class="row g-4">

                        <div class="col-auto">

                            <div class="text-secondary small">

                                Started

                            </div>

                            <div class="fw-semibold">

                                {{ $runningTimer->started_at->format('h:i A') }}

                            </div>

                        </div>

                        <div class="col-auto">

                            <div class="text-secondary small">

                                Status

                            </div>

                            <div class="fw-semibold text-success">

                                Working

                            </div>

                        </div>

                        <div class="col-auto">

                            <div class="text-secondary small">

                                Date

                            </div>

                            <div class="fw-semibold">

                                {{ now()->format('d M Y') }}

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Right --}}
                <div class="col-lg-4">

                    <div class="h-100 d-flex flex-column justify-content-center align-items-center bg-light p-5">

                        <div
                                wire:poll.1s="refreshRunningTimer"
                                class="display-4 fw-bold text-success">

                            {{ $this->getTimerDisplay($timerSeconds) }}

                        </div>

                        <div class="text-secondary mt-2">

                            Elapsed Time

                        </div>

                        <button
                                wire:click="stopTimer"
                                class="btn btn-danger rounded-pill px-4 mt-4">

                            <i class="fa-solid fa-stop me-2"></i>

                            End Session

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endif
