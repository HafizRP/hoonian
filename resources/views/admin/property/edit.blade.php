@extends('layouts.admin')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Properties</h4>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <ul class="breadcrumbs">
            <li class="nav-home"><a href="{{ route('backoffice.index') }}"><i class="flaticon-home"></i></a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="{{ route('backoffice.properties') }}">Property</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="#">Update Property</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Update Property: {{ $property->name }}</div>
                </div>
                <form action="{{ route('properties.update', $property->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="row">
                            {{-- Left Column: Basic Info --}}
                            <div class="col-md-6">
                                <h5 class="text-uppercase fw-bold text-primary mb-3">Basic Information</h5>
                                <div class="form-group">
                                    <label>Property Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $property->name) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Price (IDR) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" class="form-control" value="{{ old('price', $property->price) }}" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City <span class="text-danger">*</span></label>
                                            <input type="text" name="city" class="form-control" value="{{ old('city', $property->city) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Type <span class="text-danger">*</span></label>
                                            <select name="property_type" class="form-control" required>
                                                <option value="">Select Type</option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}" {{ $property->property_type == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Owner <span class="text-danger">*</span></label>
                                    <select name="owner_id" class="form-control" id="ownerSelect" required>
                                        <option value="">Select Owner</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $property->owner_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="3" required>{{ old('address', $property->address) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" rows="4">{{ old('description', $property->description) }}</textarea>
                                </div>
                            </div>

                            {{-- Right Column: Specs & Images --}}
                            <div class="col-md-6">
                                <h5 class="text-uppercase fw-bold text-primary mb-3">Specifications</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Land Area (m²)</label>
                                            <input type="number" name="land_area" class="form-control" value="{{ old('land_area', $property->land_area) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Building Area (m²)</label>
                                            <input type="number" name="building_area" class="form-control" value="{{ old('building_area', $property->building_area) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Bedrooms</label>
                                            <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $property->bedrooms) }}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Bathrooms</label>
                                            <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $property->bathrooms) }}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Floors</label>
                                            <input type="number" name="floors" class="form-control" value="{{ old('floors', $property->floors) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Google Maps Embed URL</label>
                                    <input type="text" name="maps_url" class="form-control" value="{{ old('maps_url', $property->maps_url) }}" placeholder="https://www.google.com/maps/embed?..." >
                                </div>

                                <div class="separator-solid"></div>
                                <h5 class="text-uppercase fw-bold text-primary mb-3">Images</h5>

                                <div class="form-group">
                                    <label>Main Thumbnail</label>
                                    <div class="mb-2">
                                        @if($property->thumbnail)
                                            <img src="{{ asset($property->thumbnail) }}" alt="Current Thumbnail" class="img-thumbnail" style="height: 100px;">
                                        @endif
                                    </div>
                                    <input type="file" name="thumbnail" class="form-control" accept="image/*" onchange="previewImage(this, 'thumbnailPreview')">
                                    <small class="text-muted">Upload to replace current thumbnail.</small>
                                    <img id="thumbnailPreview" src="#" class="mt-2 rounded img-thumbnail" style="max-height: 150px; display: none;">
                                </div>

                                <div class="form-group">
                                    <label>Additional Gallery Images</label>
                                    <div class="row mb-2">
                                        @foreach($property->gallery as $gal)
                                            <div class="col-3 mb-2">
                                                 <img src="{{ asset($gal->url) }}" class="img-thumbnail w-100" style="height: 60px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple id="galleryInput">
                                    <small class="form-text text-muted">Upload to add more images to gallery.</small>
                                    <div id="galleryPreview" class="row mt-3"></div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" name="featured" value="1" {{ $property->featured ? 'checked' : '' }}>
                                            <span class="form-check-sign">Mark as Featured</span>
                                        </label>
                                        <label class="form-check-label ms-3">
                                            <input class="form-check-input" type="checkbox" name="popular" value="1" {{ $property->popular ? 'checked' : '' }}>
                                            <span class="form-check-sign">Mark as Popular</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action text-end">
                        <a href="{{ route('backoffice.properties') }}" class="btn btn-danger">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Property</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {
        $('#ownerSelect').select2({
          width: '100%',
          placeholder: "Select Owner",
          allowClear: true
        });

        $('#galleryInput').on('change', function() {
            var previewContainer = $('#galleryPreview');
            previewContainer.empty();

            if (this.files) {
                [...this.files].forEach(file => {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var html = `
                            <div class="col-4 mb-3">
                                <img src="${e.target.result}" class="img-thumbnail rounded w-100" style="height: 100px; object-fit: cover;">
                            </div>
                        `;
                        previewContainer.append(html);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
