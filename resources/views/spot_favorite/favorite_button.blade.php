@if (Auth::user()->is_favorite($spot->id))
    {{-- アンフォローボタンのフォーム --}}
    {!! Form::open(['route' => ['favorites.unfavorite', $spot->id], 'method' => 'delete']) !!}
        {!! Form::submit('Unfavorite', ['class' => "btn btn-danger"]) !!}
    {!! Form::close() !!}
@else
    {{-- フォローボタンのフォーム --}}
    {!! Form::open(['route' => ['favorites.favorite', $spot->id]]) !!}
        {!! Form::submit('Favorite', ['class' => "btn btn-primary"]) !!}
    {!! Form::close() !!}
@endif