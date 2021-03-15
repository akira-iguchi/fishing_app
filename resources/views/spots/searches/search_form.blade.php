<div class="search_group">
    <form action="{{ url('spots/search')}}" method="post" class="spotIndex_search_form">
        @csrf
        @method('get')

        キーワード複数入力させる
        <div class="spot_search_top">
            <input type="text" class="spotIndex_search_text" placeholder="キーワードを入力" name="searchWord" value="{{ $searchWord }}">
            <button type="submit" class="spotIndex_search_button"><i class="fas fa-search"></i></button>
        </div>

        <div>
        @foreach ($allFishingTypeNames as $fishingType)
            <input class="search_check" type="checkbox" id="{{ $fishingType->id }}" name="fishing_types[]" value="{{ $fishingType->id }}">
            <label for="{{ $fishingType->id }}">{{ $fishingType->fishing_type_name }}</label>
        @endforeach
        </div>
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