@extends('layouts.admin')

@section('title', __('messages.Services'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('messages.Services') }}</h1>
        <a href="{{ route('services.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.Add_New_Service') }}
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Services Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.Services_List') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('messages.ID') }}</th>
                            <th>{{ __('messages.Photo') }}</th>
                            <th>{{ __('messages.Name') }}</th>
                            <th>{{ __('messages.Start_Price') }}</th>
                            <th>{{ __('messages.Price_Per_KM') }}</th>
                            <th>{{ __('messages.Commission') }}</th>
                            <th>{{ __('messages.Payment_Method') }}</th>
                            <th>{{ __('messages.Capacity') }}</th>
                            <th>{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>
                                @if($service->photo)
                                <img src="{{ asset('assets/admin/uploads/' . $service->photo) }}" alt="{{ $service->getName() }}" width="50" height="50" class="img-thumbnail">
                                @else
                                <img src="{{ asset('assets/admin/img/no-image.png') }}" alt="No Image" width="50" height="50" class="img-thumbnail">
                                @endif
                            </td>
                            <td>
                                <div>{{ $service->name_en }}</div>
                                <div class="text-muted">{{ $service->name_ar }}</div>
                            </td>
                            <td>{{ $service->start_price }}</td>
                            <td>{{ $service->price_per_km }}</td>
                            <td>
                                {{ $service->admin_commision }}
                                <span class="badge badge-info">{{ $service->getCommisionTypeText() }}</span>
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $service->getPaymentMethodText() }}</span>
                            </td>
                            <td>{{ $service->capacity }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); if(confirm('{{ __('messages.Delete_Confirm') }}')) document.getElementById('delete-form-{{ $service->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-form-{{ $service->id }}" action="{{ route('services.destroy', $service->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endsection