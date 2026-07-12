@extends('layouts.app')

@section('content')

    <div class="sticky-top bg-white border-bottom shadow-sm" style="top:60px;z-index:0;">

        <div class="container-fluid py-3">

            <h5 class="fw-bold mb-0">
                Categories
            </h5>

        </div>

    </div>

    <div class="container-fluid py-4">

        <livewire:interview-questions.category />

    </div>

@endsection
