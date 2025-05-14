@extends('layouts.admin')

@section('title', __('messages.View_Service'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('messages.View_Service') }}</h1>
        <div>
            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> {{ __('messages.Edit') }}
            </a>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('messages.Back_to_List') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Service Image -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.Service_Image') }}</h6>
                </div>
                <div class="card-body text-center">
                    @if($service->photo)
                    <img src="{{ asset('assets/admin/uploads/' . $service->photo) }}" alt="{{ $service->getName() }}" class="img-fluid rounded mb-3" style="max-height: 250px;">
                    @else
                    <img src="{{ asset('assets/admin/img/no-image.png') }}" alt="No Image" class="img-fluid rounded mb-3" style="max-height: 250px;">
                    @endif
                    <h4 class="font-weight-bold">{{ $service->name_en }}</h4>
                    <p class="text-muted mb-1">{{ $service->name_ar }}</p>
                    <p class="mb-2">{{ __('messages.Capacity') }}: {{ $service->capacity }}</p>
                </div>
            </div>
            
            <!-- Payment Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.Payment_Information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="badge badge-primary px-3 py-2">{{ $service->getPaymentMethodText() }}</span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="60%">{{ __('messages.Admin_Commission') }}</th>
                                    <td>
                                        {{ $service->admin_commision }}
                                        <span class="badge badge-info">{{ $service->getCommisionTypeText() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Cancellation_Fee') }}</th>
                                    <td>{{ $service->cancellation_fee }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Waiting_Time') }}</th>
                                    <td>{{ $service->waiting_time }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Service Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.Service_Details') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%">{{ __('messages.ID') }}</th>
                                    <td>{{ $service->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Name_English') }}</th>
                                    <td>{{ $service->name_en }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Name_Arabic') }}</th>
                                    <td>{{ $service->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Capacity') }}</th>
                                    <td>{{ $service->capacity }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Pricing Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.Pricing_Details') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ __('messages.Start_Price') }}</h5>
                                    <h2 class="text-primary">{{ $service->start_price }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ __('messages.Price_Per_KM') }}</h5>
                                    <h2 class="text-primary">{{ $service->price_per_km }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            {{ __('messages.Example_Trip_Cost') }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p>{{ __('messages.For_5_KM_Trip') }}:</p>
                                    <h4>{{ $service->start_price + ($service->price_per_km * 5) }}</h4>
                                </div>
                                <div class="col-md-4">
                                    <p>{{ __('messages.For_10_KM_Trip') }}:</p>
                                    <h4>{{ $service->start_price + ($service->price_per_km * 10) }}</h4>
                                </div>
                                <div class="col-md-4">
                                    <p>{{ __('messages.For_15_KM_Trip') }}:</p>
                                    <h4>{{ $service->start_price + ($service->price_per_km * 15) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            {{ __('messages.Admin_Fee_Example') }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($service->type_of_commision == 1)
                                    <p>{{ __('messages.Fixed_Amount_Per_Trip') }}: <strong>{{ $service->admin_commision }}</strong></p>
                                    @else
                                    <p>{{ __('messages.For_100_Trip_Cost') }}:</p>
                                    <h4>{{ ($service->admin_commision / 100) * 100 }}</h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection