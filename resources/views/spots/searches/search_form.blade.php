<div class="search_group">
    <form action="{{ url('spots/search')}}" method="post" class="spotIndex_search_form">
        {{ csrf_field()}}
        {{method_field('get')}}
        <input type="text" class="spotIndex_search_text" placeholder="キーワードを入力" name="name">
        <button type="submit" class="spotIndex_search_button"><i class="fas fa-search"></i></button>
    </form>

    @foreach($tags as $tag)
        @if($loop->first)
        <div class="card-body mb-2">
            <div class="card-text line-height">
        @endif
            <a href="{{ route('tags.show', ['name' => $tag->name]) }}" class="spot_tag">
                {{ $tag->hashtag }}
            </a>
        @if($loop->last)
            </div>
        </div>
        @endif
    @endforeach
</div>