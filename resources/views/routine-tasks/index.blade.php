@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                    <div class="card-body p-4">
                        <livewire:routine-tasks.task-list />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
