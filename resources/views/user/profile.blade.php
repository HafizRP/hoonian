@extends('layouts.main')

@section('content')
    <div class="hero page-inner overlay" style="background-image: url('images/hero_bg_1.jpg')">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center mt-5">
                    <div>
                        <img src="{{ asset($user->profile_img) }}" alt="..." class="img-fluid rounded-circle w-25 mb-4">
                    </div>
                    <h1 class="heading" data-aos="fade-up">Profile</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <div class="row">
                {{-- <div class="col-lg-4 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-info">
                        <div class="address mt-2">
                            <i class="icon-room"></i>
                            <h4 class="mb-2">Location:</h4>
                            <p>
                                43 Raymouth Rd. Baltemoer,<br />
                                London 3910
                            </p>
                        </div>

                        <div class="open-hours mt-4">
                            <i class="icon-clock-o"></i>
                            <h4 class="mb-2">Open Hours:</h4>
                            <p>
                                Sunday-Friday:<br />
                                11:00 AM - 2300 PM
                            </p>
                        </div>

                        <div class="email mt-4">
                            <i class="icon-envelope"></i>
                            <h4 class="mb-2">Email:</h4>
                            <p>info@Untree.co</p>
                        </div>

                        <div class="phone mt-4">
                            <i class="icon-phone"></i>
                            <h4 class="mb-2">Call:</h4>
                            <p>+1 1234 55488 55</p>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-12" data-aos="fade-up" data-aos-delay="200">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('users.profile.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="">Fullname</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                    placeholder="Your Name" />
                            </div>
                            <div class="col-6 mb-3">
                                <label for="">Email</label>

                                <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                    placeholder="Your Email" />
                            </div>
                            <div class="col-12 mb-3">
                                <label for="">Bio</label>
                                <textarea name="bio" cols="30" rows="7" class="form-control" placeholder="Message">{{ $user->bio }}</textarea>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="">Instagram URL</label>
                                <input type="text" name="ig_url" class="form-control" value="{{ $user->ig_url }}"
                                    placeholder="Subject" />
                            </div>

                            <div class="col-12 mb-3">
                                <label for="">Whatsapp URL</label>
                                <input type="text" name="wa_url" class="form-control" value="{{ $user->wa_url }}"
                                    placeholder="Subject" />
                            </div>

                            <div class="col-12 mb-3">
                                <label for="">X URL</label>
                                <input type="text" name="x_url" class="form-control" value="{{ $user->x_url }}"
                                    placeholder="Subject" />
                            </div>

                            <div class="col-12">
                                <input type="submit" value="Update" class="btn btn-primary" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
