@extends('layouts.admin')

@section('title')
    {{ __('messages.ratings') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-light py-3">
            <div class="row align-items-center justify-content-between">
                <!-- Left Section: Buttons -->
                <div class="col-md-6 d-flex align-items-center">
                    <a href="{{ route('ratings.create') }}" class="btn btn-sm btn-primary ml-2">
                        <i class="fa fa-plus"></i> {{ __('messages.New Rating') }}
                    </a>
                </div>

                <!-- Right Section: Search -->
                <div class="col-md-3">
                    <form method="get" action="{{ route('ratings.index') }}" class="d-flex justify-content-end">
                        <input autofocus type="text" 
                               placeholder="{{ __('messages.Search') }}" 
                               name="search" 
                               class="form-control mr-2" 
                               value="{{ request('search') }}">
                        <button class="btn btn-primary btn-sm">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @can('ratings-table')
                    @if (isset($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <tr>
                                    <th>{{ __('messages.User') }}</th>
                                    <th>{{ __('messages.Class') }}</th>
                                    <th>{{ __('messages.Day') }}</th>
                                    <th>{{ __('messages.Date') }}</th>
                                    <th>{{ __('messages.Share Rating') }}</th>
                                    <th>{{ __('messages.Homework Rating') }}</th>
                                    <th>{{ __('messages.Save Rating') }}</th>
                                    <th>{{ __('messages.Recitation Rating') }}</th>
                                    <th>{{ __('messages.Quiz Rating') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <!-- User and Class -->
                                        <td>{{ $info->user->name ?? __('messages.Not Available') }}</td>
                                        <td>{{ $info->class->name ?? __('messages.Not Available') }}</td>

                                        <!-- Day and Date -->
                                        <td>{{ $info->day }}</td>
                                        <td>{{ $info->date_of_rating }}</td>

                                        <!-- Ratings -->
                                        <td>{{ $info->rating_of_share }}</td>
                                        <td>{{ $info->rating_of_homework }}</td>
                                        <td>{{ $info->rating_of_save }}</td>
                                        <td>{{ $info->rating_of_recitation }}</td>
                                        <td>{{ $info->rating_of_quiz }}</td>

                                        <!-- Actions -->
                                        <td>
                                            @can('ratings-edit')
                                                <a href="{{ route('ratings.edit', $info->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fa fa-edit"></i> {{ __('messages.Edit') }}
                                                </a>
                                            @endcan
                                            @can('ratings-delete')
                                                <form action="{{ route('ratings.destroy', $info->id) }}" 
                                                      method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('{{ __('messages.Are you sure?') }}')">
                                                        <i class="fa fa-trash"></i> {{ __('messages.Delete') }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <!-- Pagination -->
                        {{ $data->appends(['search' => request('search')])->links() }}
                    @else
                        <div class="alert alert-danger">
                            {{ __('messages.No_data') }}
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </div>
@endsection
