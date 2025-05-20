@extends('layouts.admin')

@section('title', __('messages.Drivers'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('messages.Drivers') }}</h1>
        <a href="{{ route('drivers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.Add_New_Driver') }}
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.Driver_List') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('messages.ID') }}</th>
                            <th>{{ __('messages.Photo') }}</th>
                            <th>{{ __('messages.Name') }}</th>
                            <th>{{ __('messages.Phone') }}</th>
                            <th>{{ __('messages.Car') }}</th>
                            <th>{{ __('messages.Option') }}</th>
                            <th>{{ __('messages.Balance') }}</th>
                            <th>{{ __('messages.Status') }}</th>
                            <th>{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drivers as $driver)
                        <tr>
                            <td>{{ $driver->id }}</td>
                            <td>
                                @if($driver->photo)
                                <img src="{{ asset('assets/admin/uploads/' . $driver->photo) }}" alt="{{ $driver->name }}" width="50">
                                @else
                                <img src="{{ asset('assets/admin/img/no-image.png') }}" alt="No Image" width="50">
                                @endif
                            </td>
                            <td>{{ $driver->name }}</td>
                            <td>{{ $driver->country_code }} {{ $driver->phone }}</td>
                            <td>
                                {{ $driver->model ?? 'N/A' }}
                                @if($driver->color)
                                <span class="badge badge-info">{{ $driver->color }}</span>
                                @endif
                            </td>
                            <td>{{ $driver->option->name ?? 'N/A' }}</td>
                            <td>{{ $driver->balance }}</td>
                            <td>
                                @if($driver->activate == 1)
                                <span class="badge badge-success">{{ __('messages.Active') }}</span>
                                @else
                                <span class="badge badge-danger">{{ __('messages.Inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('drivers.show', $driver->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-warning btn-sm" onclick="event.preventDefault(); document.getElementById('toggle-form-{{ $driver->id }}').submit();">
                                        @if($driver->activate == 1)
                                        <i class="fas fa-ban"></i>
                                        @else
                                        <i class="fas fa-check"></i>
                                        @endif
                                    </a>
                                    <form id="toggle-form-{{ $driver->id }}" action="{{ route('drivers.toggleActivation', $driver->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('GET')
                                    </form>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); if(confirm('{{ __('messages.Delete_Confirm') }}')) document.getElementById('delete-form-{{ $driver->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-form-{{ $driver->id }}" action="{{ route('drivers.destroy', $driver->id) }}" method="POST" style="display: none;">
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