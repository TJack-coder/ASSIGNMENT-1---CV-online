@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Create / Update Your CV</h3>
            <small>One CV per job seeker • All fields are structured and normalized</small>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form id="cvForm" method="POST" action="{{ route('cv.store') }}">
                @csrf

                <!-- ==================== Personal Information ==================== -->
                <h5 class="mt-3">Personal Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" required value="{{ old('full_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label>Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" class="form-control" required value="{{ old('date_of_birth') }}">
                    </div>
                    <div class="col-md-3">
                        <label>Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    </div>
                    <div class="col-md-6">
                        <label>Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone_number" class="form-control" required value="{{ old('phone_number') }}">
                    </div>
                </div>

                <!-- ==================== Structured Contact Address (MANDATORY) ==================== -->
                <h5 class="mt-5">Structured Contact Address</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Country <span class="text-danger">*</span></label>
                        <select name="country_id" class="form-control" required>
                            <option value="">-- Select Country --</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>City / Province <span class="text-danger">*</span></label>
                        <select name="city_id" class="form-control" required>
                            <option value="">-- Select City --</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>District (Optional)</label>
                        <select name="district_id" class="form-control">
                            <option value="">-- Select District --</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label>Street Address <span class="text-danger">*</span></label>
                        <input type="text" name="street_address" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Postal Code (Optional)</label>
                        <input type="text" name="postal_code" class="form-control">
                    </div>
                </div>

                <!-- ==================== C. CV Category (MANDATORY)
 ==================== -->
                <h5 class="mt-5">CV Category <span class="text-danger">*</span></h5>
                <select name="cv_category_id" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <!-- ==================== D. Education (Multiple Degrees – Dynamic Form)
 ==================== -->
                <h5 class="mt-5">Education 
                    <button type="button" class="btn btn-primary btn-sm" onclick="addEducation()">
                        + Add Degree
                    </button>
                </h5>
                <div id="educationContainer"></div>

                <!-- ==================== E. Work History (Multiple Records – Dynamic Form)
 ==================== -->
                <h5 class="mt-5">Work History 
                    <button type="button" class="btn btn-primary btn-sm" onclick="addWorkHistory()">
                        + Add Work History
                    </button>
                </h5>
                <div id="workContainer"></div>

                <!-- ==================== F. Certificates (Multiple Records – Dynamic Form)
 ==================== -->
                <h5 class="mt-5">Certificates 
                    <button type="button" class="btn btn-primary btn-sm" onclick="addCertificate()">
                        + Add Certificate
                    </button>
                </h5>
                <div id="certificateContainer"></div>

                <!-- ==================== G. Skills (Strongest Skills Only)
 (Max 5) ==================== -->
                <h5 class="mt-5">Strongest Skills (Maximum 5) 
                    <button type="button" id="addSkillBtn" class="btn btn-primary btn-sm" onclick="addSkill()">
                        + Add Skill
                    </button>
                </h5>
                <div id="skillsContainer" class="row g-3"></div>

                <!-- Submit -->
                <div class="mt-5">
                    <button type="submit" class="btn btn-success btn-lg px-5">Save My CV</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ==================== TEMPLATES FOR DYNAMIC SECTIONS ==================== --}}
@include('seeker.cv.templates')

<script src="{{ asset('js/cv-form.js') }}"></script>

@endsection