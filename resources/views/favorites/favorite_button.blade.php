<spot-favorite
    :initial-is-liked-by='@json($spot->isLikedBy(Auth::user()))'
    :initial-count-spot-favorites='@json($spot->count_spot_favorites)'
    :authorized='@json(Auth::check())'
    endpoint="{{ route('spots.favorite', ['spot' => $spot]) }}"
>
</spot-favorite>