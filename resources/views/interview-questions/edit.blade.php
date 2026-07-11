@extends('layouts.app')

@section('content')

    <div class="sticky-top bg-white border-bottom shadow-sm" style="top:60px;z-index:0;">

        <div class="container-fluid py-3 d-flex justify-content-between align-items-center">

            <div class="p-2">
                <h5 class="fw-bold mb-0">
                    Edit Interview Question
                </h5>
            </div>

            <div class="text-muted small d-none d-md-block">
                Update your interview preparation database
            </div>

        </div>

    </div>

    <div class="container-fluid content-area py-4">

        <div class="row justify-content-center">

            <div class="col-lg-12">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4 p-md-5">

                        <livewire:interview-questions.edit-form
                            :question="$question" />

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

@push('js')
    <script>
        document.addEventListener('livewire:init', () => {

            $('#categories').select2({
                placeholder: 'Select categories',
                width: '100%',
                closeOnSelect: false
            });

            $('#categories').val(@json($question->categories->pluck('id')->map(fn($id)=>(string)$id)) ).trigger('change');

            $('#categories').on('change', function () {
                Livewire.find($(this).closest('[wire\\:id]').attr('wire:id'))
                    .set('categories', $(this).val());
            });

        });
    </script>
@endpush
