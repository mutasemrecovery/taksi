@extends('layouts.admin')

@section('title')
    {{ __('messages.Edit') }} {{ __('messages.Rating') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.Edit') }} {{ __('messages.Rating') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('ratings.update', $rating->id) }}" method="post">
                @csrf
                @method('PUT')

                <div class="row">

                    <!-- Pre-filled fields -->
                    @foreach(['day', 'date_of_rating'] as $field)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.'.$field) }}</label>
                                <input type="{{ $field === 'date_of_rating' ? 'date' : 'text' }}" 
                                       name="{{ $field }}" 
                                       class="form-control" 
                                       value="{{ old($field, $rating->$field) }}">
                                @error($field) <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach

                    <!-- Ratings -->
                    @foreach(['share', 'homework', 'save', 'recitation', 'quiz'] as $field)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('messages.rating_of_'.$field) }}</label>
                                <input type="number" step="0.1" name="rating_of_{{ $field }}" class="form-control" value="{{ old('rating_of_'.$field, $rating->{'rating_of_'.$field}) }}">
                                @error("rating_of_$field") <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach

                    <!-- User and Class dropdowns -->
                    <x-dropdown name="user_id" :data="$users" selected="{{ $rating->user_id }}" />
                    <x-dropdown name="clas_id" :data="$classes" selected="{{ $rating->clas_id }}" />

                    <!-- Submit -->
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">{{ __('messages.Update') }}</button>
                        <a href="{{ route('ratings.index') }}" class="btn btn-danger">{{ __('messages.Cancel') }}</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
