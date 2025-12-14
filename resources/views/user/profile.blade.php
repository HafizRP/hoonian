@extends('layouts.main')

@section('title', 'Profile Settings')

@section('content')
    <div class="hero page-inner overlay" style="background-image: url('{{ asset('images/hero_bg_1.jpg') }}')">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center mt-5">
                    {{-- Profile Image Preview Container --}}
                    <div class="position-relative d-inline-block">
                        <div class="profile-preview-wrapper mb-4">
                            <img id="imagePreview" src="{{ asset($user->profile_img ?? 'images/person_1.jpg') }}"
                                alt="Profile" class="img-fluid rounded-circle shadow-lg"
                                style="width: 160px; height: 160px; object-fit: cover; border: 5px solid rgba(255,255,255,0.2);">

                            {{-- Edit Icon Overlay --}}
                            <label for="imageUpload" class="btn-edit-photo" title="Change Photo">
                                <span class="icon-camera"></span>
                                <input type='file' id="imageUpload" name="profile_img" form="profileForm"
                                    accept=".png, .jpg, .jpeg" style="display: none;" />
                            </label>
                        </div>
                    </div>
                    <h1 class="heading" data-aos="fade-up">Profile Settings</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up">
                    <div class="card border-0 shadow-sm p-4 p-md-5 mt-n5" style="border-radius: 15px;">
                        <form id="profileForm" method="POST" action="{{ route('users.profile.update') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-12 mb-4">
                                    <h4 class="text-primary fw-bold">Personal Information</h4>
                                    <p class="text-muted small">Update your personal details and social presence.</p>
                                    <hr>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-2">Fullname</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" required />
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-2">Email Address</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required />
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="fw-bold mb-2">Bio</label>
                                    <textarea name="bio" rows="3" class="form-control" placeholder="Brief info about you...">{{ old('bio', $user->bio) }}</textarea>
                                </div>

                                {{-- Social Media Section --}}
                                <div class="col-12 mb-4 mt-2">
                                    <h4 class="text-primary fw-bold">Social Media Links</h4>
                                    <hr>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="icon-instagram"></i></span>
                                        <input type="text" name="ig_url" class="form-control"
                                            value="{{ $user->ig_url }}" placeholder="Instagram URL" />
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="icon-whatsapp"></i></span>
                                        <input type="text" name="wa_url" class="form-control"
                                            value="{{ $user->wa_url }}" placeholder="WhatsApp Number" />
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="icon-twitter"></i></span>
                                        <input type="text" name="x_url" class="form-control"
                                            value="{{ $user->x_url }}" placeholder="X URL" />
                                    </div>
                                </div>

                                <div class="col-12 mt-4 text-center">
                                    <button type="submit" class="btn btn-primary px-5 rounded-pill">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Live Preview --}}
    <script>
        document.getElementById('imageUpload').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('imagePreview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>

    {{-- Tambahan CSS Khusus Preview --}}
    <style>
        .profile-preview-wrapper {
            position: relative;
            display: inline-block;
        }

        .btn-edit-photo {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #005555;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
            border: 3px solid #fff;
        }

        .btn-edit-photo:hover {
            background: #007777;
            transform: scale(1.1);
        }

        .input-group-text {
            border-right: none;
            color: #005555;
        }

        .form-control:focus {
            border-color: #005555;
            box-shadow: none;
        }
    </style>
@endsection
