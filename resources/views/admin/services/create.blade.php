@extends('layouts.admin')

@section('title', __('messages.Create_Service'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('messages.Create_Service') }}</h1>
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

            <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="name_en">{{ __('messages.Name_English') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_en" name="name_en" value="{{ old('name_en') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="name_ar">{{ __('messages.Name_Arabic') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="photo">{{ __('messages.Photo') }} <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="photo" name="photo" required>
                                <label class="custom-file-label" for="photo">{{ __('messages.Choose_file') }}</label>
                            </div>
                            <div class="mt-3" id="image-preview"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="capacity">{{ __('messages.Capacity') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', 0) }}" required min="0">
                            <small class="form-text text-muted">{{ __('messages.Capacity_Info') }}</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Pricing Information -->
                        <div class="form-group">
                            <label for="start_price">{{ __('messages.Start_Price') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="start_price" name="start_price" value="{{ old('start_price', 0) }}" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="price_per_km">{{ __('messages.Price_Per_KM') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="price_per_km" name="price_per_km" value="{{ old('price_per_km', 0) }}" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="waiting_time">{{ __('messages.Waiting_Time') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="waiting_time" name="waiting_time" value="{{ old('waiting_time', 0) }}" required min="0">
                            <small class="form-text text-muted">{{ __('messages.Waiting_Time_Info') }}</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="cancellation_fee">{{ __('messages.Cancellation_Fee') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="cancellation_fee" name="cancellation_fee" value="{{ old('cancellation_fee', 0) }}" required min="0">
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <!-- Commission and Payment Settings -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="admin_commision">{{ __('messages.Admin_Commission') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="admin_commision" name="admin_commision" value="{{ old('admin_commision', 0) }}" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="type_of_commision">{{ __('messages.Commission_Type') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="type_of_commision" name="type_of_commision" required>
                                <option value="1" {{ old('type_of_commision', 1) == 1 ? 'selected' : '' }}>{{ __('messages.Fixed_Amount') }}</option>
                                <option value="2" {{ old('type_of_commision') == 2 ? 'selected' : '' }}>{{ __('messages.Percentage') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_method">{{ __('messages.Payment_Method') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="1" {{ old('payment_method', 1) == 1 ? 'selected' : '' }}>{{ __('messages.Cash') }}</option>
                                <option value="2" {{ old('payment_method') == 2 ? 'selected' : '' }}>{{ __('messages.Visa') }}</option>
                                <option value="3" {{ old('payment_method') == 3 ? 'selected' : '' }}>{{ __('messages.Wallet') }}</option>
                            </select>
                        </div>
                    </div>
                        <div class="form-group">
                            <label for="activate">{{ __('messages.Status') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="activate" name="activate" required>
                                <option value="1" {{ old('activate', 1) == 1 ? 'selected' : '' }}>{{ __('messages.Active') }}</option>
                                <option value="2" {{ old('activate') == 2 ? 'selected' : '' }}>{{ __('messages.Inactive') }}</option>
                            </select>
                     </div>
                </div>

               

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('messages.Save') }}
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