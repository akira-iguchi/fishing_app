@extends('layouts.app')

@section('content')
    <div class="container">
        <div id="calendar"></div>
        <div id="modal-show" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <a class="btn btn-outline-grey text-dark" data-dismiss="modal">キャンセル</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="/js/ajax-setup.js" defer></script>
    <script src='/js/fullcalendar/core/main.js' defer></script>
    <script src='/js/fullcalendar/daygrid/main.js' defer></script>
    <script src='/js/fullcalendar/interaction/main.js' defer></script>
    <script src='/js/fullcalendar.js' defer></script>
    <script src='/js/event-control.js' defer></script>
@endpush