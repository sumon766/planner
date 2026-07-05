@if($runningTimer)

    <div class="card border-success shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                {{-- Left --}}
                <div class="col-lg-8">

                    <div class="d-flex align-items-center mb-3">

                        <span class="badge bg-success me-2">
                            LIVE
                        </span>

                        <h5 class="mb-0 fw-semibold">

                            Current Work Session

                        </h5>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Main Task
                            </small>

                            <div class="fw-semibold">

                                {{ $runningTimer->task->parent?->title ?? $runningTimer->task->title }}

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <small class="text-muted d-block">
                                Current Activity
                            </small>

                            <div class="fw-semibold">

                                @if($runningTimer->task->parent)

                                    {{ $runningTimer->task->title }}

                                @else

                                    —

                                @endif

                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Started At
                            </small>

                            <div>

                                {{ $runningTimer->started_at->format('h:i A') }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Elapsed Time
                            </small>

                            <div
                                class="fw-bold text-success"
                                wire:poll.1s="refreshRunningTimer">

                                {{ $this->getTimerDisplay($timerSeconds) }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Right --}}
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    <button
                        class="btn btn-danger"
                        wire:click="stopTimer">

                        <i class="fa-solid fa-stop me-2"></i>

                        End Session

                    </button>

                </div>

            </div>

        </div>

    </div>

@endif
