<div class="search_group">
    <form action="{{ url('spots/search')}}" method="post" class="spotIndex_search_form">
        @csrf
        @method('get')

        <div class="spot_search_top">
            <input type="text" class="spotIndex_search_text" placeholder="キーワードを入力" name="searchWord" value="{{ $searchData[1] }}">
            <button type="submit" class="spotIndex_search_button"><i class="fas fa-search"></i></button>
        </div>

        <div>
        @foreach ($searchData[0] as $fishingType)
            <input class="search_check" type="checkbox" id="{{ $fishingType->id }}" name="fishing_types[]" value="{{ $fishingType->id }}">
            <label for="{{ $fishingType->id }}">{{ $fishingType->fishing_type_name }}</label>
        @endforeach
        </div>
    </form>

    @foreach($searchData[3] as $tag)
        @if($loop->first)
        <div class="card-body mb-2">
            <div class="card-text line-height">
        @endif
            <a href="{{ route('tags', ['name' => $tag->tag_name]) }}" class="spot_tag">
                {{ $tag->hashtag }}
            </a>
        @if($loop->last)
            </div>
        </div>
        @endif
    @endforeach
</div>