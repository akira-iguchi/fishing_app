@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row">
                <div class="mx-auto d-block col-lg-4 col-md-6 spot_index">
                    @foreach ($spots as $spot)
                        <div>
                        <a href="{{ url('/') }}" >
                            <img src="{{ asset('storage/'.$spot->image) }}" alt="釣り場の画像">
                            {{ $spot->name }}
                        </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection