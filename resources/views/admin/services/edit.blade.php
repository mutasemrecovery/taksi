@extends('layouts.admin')

@section('title', __('messages.Edit_Service'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('messages.Edit_Service') }}</h1>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.Back_to_List') }}
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.Service_Details') }}</h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="name_en">{{ __('messages.Name_English') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_en" name="name_en" value="{{ old('name_en', $service->name_en) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="name_ar">{{ __('messages.Name_Arabic') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_ar" name="name_ar" value="{{ old('name_ar', $service->name_ar) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="photo">{{ __('messages.Photo') }}</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="photo" name="photo">
                                <label class="custom-file-label" for="photo">{{ __('messages.Choose_file') }}</label>
                            </div>
                            <div class="mt-3" id="image-preview">
                                @if($service->photo)
                                <img src="{{ asset('assets/admin/uploads/' . $service->photo) }}" alt="{{ $service->getName() }}" class="img-fluid img-thumbnail" style="max-height: 200px;">
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="capacity">{{ __('messages.Capacity') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $service->capacity) }}" required min="0">
                            <small class="form-text text-muted">{{ __('messages.Capacity_Info') }}</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Pricing Information -->
                        <div class="form-group">
                            <label for="start_price">{{ __('messages.Start_Price') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="start_price" name="start_price" value="{{ old('start_price', $service->start_price) }}" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="price_per_km">{{ __('messages.Price_Per_KM') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="price_per_km" name="price_per_km" value="{{ old('price_per_km', $service->price_per_km) }}" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="waiting_time">{{ __('messages.Waiting_Time') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="waiting_time" name="waiting_time" value="{{ old('waiting_time', $service->waiting_time) }}" required min="0">
                            <small class="form-text text-muted">{{ __('messages.Waiting_Time_Info') }}</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="cancellation_fee">{{ __('messages.Cancellation_Fee') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="cancellation_fee" name="cancellation_fee" value="{{ old('cancellation_fee', $service->cancellation_fee) }}" required min="0">
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <!-- Commission and Payment Settings -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="admin_commision">{{ __('messages.Admin_Commission') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="admin_commision" name="admin_commision" value="{{ old('admin_commision', $service->admin_commision) }}" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="type_of_commision">{{ __('messages.Commission_Type') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="type_of_commision" name="type_of_commision" required>
                                <option value="1" {{ old('type_of_commision', $service->type_of_commision) == 1 ? 'selected' : '' }}>{{ __('messages.Fixed_Amount') }}</option>
                                <option value="2" {{ old('type_of_commision', $service->type_of_commision) == 2 ? 'selected' : '' }}>{{ __('messages.Percentage') }}</option>
                            </select>
                        </div>
                    </div>
                    
                      <div class="form-group">
                        <label>{{ __('Payment Methods') }}</label>
                        <div class="checkbox-list">
                            @php
                                $paymentMethods = $service->servicePayments->pluck('payment_method')->toArray();
                            @endphp
                            <label class="checkbox">
                                <input type="checkbox" name="payment_methods[]" value="1" {{ in_array(1, $paymentMethods) ? 'checked' : '' }}>
                                <span></span>{{ __('Cash') }}
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="payment_methods[]" value="2" {{ in_array(2, $paymentMethods) ? 'checked' : '' }}>
                                <span></span>{{ __('Visa') }}
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="payment_methods[]" value="3" {{ in_array(3, $paymentMethods) ? 'checked' : '' }}>
                                <span></span>{{ __('Wallet') }}
                            </label>
                        </div>
                        @error('payment_methods')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                       <div class="form-group">
                            <label for="activate">{{ __('messages.Status') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="activate" name="activate" required>
                                <option value="1" {{ old('activate', $service->activate) == 1 ? 'selected' : '' }}>{{ __('messages.Active') }}</option>
                                <option value="2" {{ old('activate', $service->activate) == 2 ? 'selected' : '' }}>{{ __('messages.Inactive') }}</option>
                            </select>
                        </div>
                </div>

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('messages.Update') }}
                    </button>
                    <a href="{{ route('services.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Show image preview
    $(document).ready(function() {
        // Show filename on file select
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
            
            // Image preview
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" class="img-fluid img-thumbnail" style="max-height: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endsection