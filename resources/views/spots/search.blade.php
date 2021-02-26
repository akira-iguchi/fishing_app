@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <form action="{{ url('spots/search')}}" method="post" class="spotIndex_search_form">
                {{ csrf_field()}}
                {{method_field('get')}}
                <input type="text" class="spotIndex_search_text" placeholder="キーワードを入力" name="name">
                <button type="submit" class="spotIndex_search_button"><i class="fas fa-search"></i></button>
            </form>

            @include('spots.card')
        </div>
    @endif
@endsection