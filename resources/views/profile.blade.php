@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">

        <div class="row">
            <div class="col-12">

                <!-- Page Title -->
                <div class="mb-4">
                    <h4 class="fw-bold mb-1">Profile</h4>
                    <p class="text-muted small mb-0">Manage your account information and security settings</p>
                </div>

                <div class="row g-4">

                    <!-- Profile Info -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                            <div class="card-body p-4">
                                <livewire:profile.update-profile-information-form />
                            </div>
                        </div>
                    </div>

                    <!-- Password Update -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                            <div class="card-body p-4">
                                <livewire:profile.update-password-form />
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm border-danger" style="border-radius: 14px;">
                            <div class="card-body p-4">
                                <livewire:profile.delete-user-form />
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection
