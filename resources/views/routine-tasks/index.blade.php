@extends('layouts.app')

@section('content')

    <!-- Sticky Header -->
    <div class="sticky-top bg-white border-bottom shadow-sm" style="top: 60px; z-index: 0;">
        <div class="container-fluid py-3 d-flex justify-content-between align-items-center">

            <!-- Left: Title + Breadcrumb -->
            <div class="p-2">
                <h5 class="fw-bold mb-0">Routine Tasks</h5>
            </div>

            <!-- Right: Quick hint / action -->
            <div class="text-muted small d-none d-md-block">
                Maintain your daily routine
            </div>

        </div>
    </div>

    <!-- Page Content -->
    <div class="container-fluid content-area py-4">

        <div class="row justify-content-center">
            <div class="col-12 col-lg-12">

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <livewire:routine-tasks.task-list />

                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection
