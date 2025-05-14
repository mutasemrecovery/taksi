@extends('layouts.admin')

@section('title')
    {{ __('messages.Add_New') }} {{ __('messages.Rating') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.Add_New') }} {{ __('messages.Rating') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('ratings.store') }}" method="post">
                @csrf
                <div class="row">

                    <!-- Day -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Day') }}</label>
                            <input type="text" name="day" class="form-control" value="{{ old('day') }}">
                            @error('day') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Date') }}</label>
                            <input type="date" name="date_of_rating" class="form-control" value="{{ old('date_of_rating') }}">
                            @error('date_of_rating') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Ratings -->
                    @foreach(['share', 'homework', 'save', 'recitation', 'quiz'] as $field)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('messages.rating_of_'.$field) }}</label>
                                <input type="number" step="0.1" name="rating_of_{{ $field }}" class="form-control" value="{{ old('rating_of_'.$field) }}">
                                @error("rating_of_$field") <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach

                    <!-- User -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.User') }}</label>
                            <select name="user_id" class="form-control">
                                <option value="">{{ __('messages.Select') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Class -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Class') }}</label>
                            <select name="clas_id" class="form-control">
                                <option value="">{{ __('messages.Select') }}</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('clas_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">{{ __('messages.Submit') }}</button>
                        <a href="{{ route('ratings.index') }}" class="btn btn-danger">{{ __('messages.Cancel') }}</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
