@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <div id="map"></div>
    <img src="{{ asset('/storage/'.$spot->image)}}" style="width: 100px;"><br>
    {{ $spot->name }}
    @endif
@endsection