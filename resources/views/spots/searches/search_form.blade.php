<div class="search_group">
    <form action="{{ url('spots/search')}}" method="post" class="spotIndex_search_form">
        {{ csrf_field()}}
        {{method_field('get')}}
        <input type="text" class="spotIndex_search_text" placeholder="キーワードを入力" name="name">
        <button type="submit" class="spotIndex_search_button"><i class="fas fa-search"></i></button>
    </form>

    <form action="{{ url('spots/search')}}" method="post">
                @csrf
                    @method('get')
            <div class="form-group row">
              <!--入力-->
              <div class="col-sm-5">
                <input type="text" class="form-control" name="searchWord" value="{{ $searchWord }}">
              </div>
              <div class="col-sm-auto">
                <button type="submit" class="btn btn-primary ">検索</button>
              </div>
            </div>
            <!--プルダウンカテゴリ選択-->
            <div class="form-group">

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