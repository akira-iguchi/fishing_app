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