@if (Auth::user()->is_favorite($spot->id))
    {{-- アンフォローボタンのフォーム --}}
    <form method="POST" action="{{ route('favorites.unfavorite', $spot->id) }}">
        @csrf
        @method('DELETE')
        <button class="unlike-btn"><i class="fa fa-heart"></i></button>
    </form>
@else
    {{-- フォローボタンのフォーム --}}
    <form method="POST" action="{{ route('favorites.favorite', $spot->id) }}">
        @csrf
        <button class="like-btn"><i class="fa fa-heart"></i></button>
    </form>
@endif