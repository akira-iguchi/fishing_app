@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row spot_body">
                <div class="mx-auto d-block col-lg-8 spot_container">
                    <p class="spot_created_at">{{ $spot->created_at->format('Y/m/d') }}</p>
                    <h1 class="spot_name">{{ $spot->name }}</h1>
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div id="show_map"></div>
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/'.$spot->image) }}" alt="釣り場の画像">
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>

                    <div id="app">
                        <favorite-component :spot-id="{{ $spot->id }}" :favorite-data="{{ $favoriteSpots }}"></favorite-component>
                    </div>

                    <table>
                        <tbody>
                            <tr>
                                <th>所在地</th>
                                <td>{{ $spot->address }}</td>
                            </tr>
                            <tr>
                                <th>説明</th>
                                <td>{{ $spot->explanation }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if (\Auth::id() === $spot->user_id)
                        <div class="spot_user_private">
                            <a href="{{ route('spots.edit', $spot->id)}}" class="spot_edit_link_button">編集</a>
                            <form action="{{route('spots.destroy', $spot->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="削除" class="spot_delete_button" onclick="return confirm('本当に削除しますか？')">
                            </form>
                        </div>
                    @endif

                    <h2 class="mt-3">コメント一覧</h2>

                    <div class="comments">
                        @foreach ($comments as $comment)
                            <div class="comment">
                                <div class="mt-2">
                                    <div class="comment_created_at">{{ $comment->created_at->format('Y/m/d') }}</div>
                                    <br>
                                    <a href="{{ route('users.show', $comment->user_id)}}">
                                        <img src="{{ asset('storage/'.$comment->user->user_image) }}" alt="釣り場投稿者の画像">
                                        <span class="comment_creater_name">{{ $comment->user->name }}</span>
                                    </a>
                                </div>

                                <div class="comment_content">
                                    {{ $comment->comment }}
                                </div>

                                @if(isset( $comment->comment_image ))
                                    <div class="comment_img">
                                        <img src="{{ asset('storage/'.$comment->comment_image) }}" alt="釣り場コメントの画像">
                                    </div>
                                @endif
                            </div>

                            @if (\Auth::id() === $comment->user_id)
                                <div class="comment_delete">
                                    <form action="{{route('comments.destroy', [$comment->spot_id, $comment->id])}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('本当に削除しますか？')"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    {{ $comments->links() }}

                    <form method="POST" action="{{ route('comments.store', $spot->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <textarea rows="4" id="textAreaComment" class="form-control mt-4" name="comment" placeholder="コメントしよう！"></textarea>
                            残り<span id="textLestComment">150</span>文字
                            <p id="textAttentionComment" style="display:none; color:red;">入力文字数が多すぎます。</p>

                            @if($errors->has('comment'))
                                <span class="error_msg">
                                    <p>{{ $errors->first('comment') }}</p>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>画像</label>
                            <input type="file" name="comment_image">
                        </div>

                        <button class="spot-create-edit-button"><i class="fas fa-pencil-alt"></i>&thinsp;コメント</button>

                    </form>
                </div>

                <!-- <div class="mx-auto d-block col-lg-4 spot_creater">
                    <span>作成者</span><br>
                    <a href="{{ route('users.show', $spot->user_id)}}">
                        <img src="{{ asset('storage/'.$spot->user->user_image) }}" alt="釣り場投稿者の画像">
                        <p class="spot_creater_name">{{ $spot->user->name }}</p>
                    </a><br>
                </div> -->
                <div class="other-spot">
                    @foreach ($spots as $spot)
                        <div class="spot_card-show">
                            <a href="{{ route('spots.show', $spot->id)}}">
                                <div class="spot_card_img">
                                    <!-- <img src="{{ $spot->image }}" alt="釣り場の画像"> -->
                                    <img src="{{ asset('storage/'.$spot->image) }}" alt="釣り場投稿者の画像">
                                </div>
                            </a>

                            <div class="spot_card-show_content">
                                <div class="spotName_userImage">
                                    <p>{{ $spot->name }}</p>
                                    <a href="{{ route('users.show', $spot->user_id)}}">
                                        <img src="{{ asset('storage/'.$spot->user->user_image) }}" alt="釣り場投稿者の画像">
                                    </a>
                                </div>

                                <p>{{ $spot->address }}</p>
                                <p>{{ $spot->explanation }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection

<script>
    function initMap(){
        map = new google.maps.Map(document.getElementById('show_map'), { //'show_map'というidを取得してマップを表示
            center: {lat: {{ $spot->latitude }}, lng: {{ $spot->longitude }}},
            zoom: 15,
        });

        marker = new google.maps.Marker({ //GoogleMapにマーカーを落とす
            position:  {lat: {{ $spot->latitude }}, lng: {{ $spot->longitude }}}, //マーカーを落とす位置を決める（値はDBに入っている）
            map: map //マーカーを落とすマップを指定
        });
    }
</script>