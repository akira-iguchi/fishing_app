@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row spot_body">
                <div class="mx-auto d-block col-lg-8 spot_container">
                    <p class="spot_created_at">{{ $spot->created_at->format('Y/m/d') }}</p>
                    <h1 class="spot_name">{{ $spot->spot_name }}</h1>

                    @foreach($spot->tags as $tag)
                        @if($loop->first)
                        <div class="card-body pt-0 pb-4 pl-3">
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

                    <div class="swiper-container mb-2">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div id="show_map"></div>
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/'.$spot->spot_image) }}" alt="釣り場の画像">
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>

                    <!-- お気に入りボタン -->
                    <spot-favorite
                        :initial-is-liked-by='@json($spot->isLikedBy(Auth::user()))'
                        :initial-count-spot-favorites='@json($spot->count_spot_favorites)'
                        :authorized='@json(Auth::check())'
                        endpoint="{{ route('spots.favorite', ['spot' => $spot]) }}"
                    >
                    </spot-favorite>

                    <table>
                        <tbody>
                            @if(isset( $spot->address ))
                                <tr>
                                    <th>所在地</th>
                                    <td>{{ $spot->address }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>説明</th>
                                <td>{{ $spot->explanation }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if (\Auth::id() === $spot->user_id)
                        <div class="spot_user_private">
                            <a href="{{ route('spots.edit', $spot->id)}}" class="spot_edit_link_button">編集</a>
                            <a data-toggle="modal" data-target="#modal-delete-{{ $spot->id }}" class="spot_delete_button">削除</a>

                            <!-- modal -->
                                <div id="modal-delete-{{ $spot->id }}" class="modal fade" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <form method="POST" action="{{ route('spots.destroy', $spot->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-body text-center">
                                            本当に削除しますか？
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="submit" class="btn btn-danger">削除する</button>
                                            <a class="btn btn-outline-grey text-dark" data-dismiss="modal">キャンセル</a>
                                        </div>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                            <!-- modal -->
                        </div>
                    @endif

                    <h2 class="mt-3">コメント一覧</h2>
                    <i class="fa fa-comment mr-1"></i>{{ $comments->count() }}

                    <div class="comments">
                        @foreach ($comments as $comment)
                            <div class="comment">
                                <div class="mt-2">
                                    <div class="comment_created_at">{{ $comment->created_at->format('Y/m/d') }}</div>
                                    <br>
                                    <a href="{{ route('users.show', $comment->user_id)}}">
                                        <img src="{{ asset('storage/'.$comment->user->user_image) }}" alt="釣り場投稿者の画像">
                                        <span class="comment_creater_name">{{ $comment->user->user_name }}</span>
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
                                    <img src="{{ asset('storage/'.$spot->spot_image) }}" alt="釣り場投稿者の画像">
                                </div>
                            </a>

                            <div class="spot_card-show_content">
                                <div class="spotName_userImage">
                                    <p>{{ $spot->spot_name }}</p>
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