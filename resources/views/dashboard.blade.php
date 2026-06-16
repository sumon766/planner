@extends('layouts.app')

@section('content')

    @isset($header)
        <div class="mb-3">
            {!! $header !!}
        </div>
    @endisset

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-2">Dashboard</h5>
            <p class="text-muted mb-0">
                You're logged in!
            </p>
        </div>
    </div>

@endsection
