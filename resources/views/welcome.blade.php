@extends('layouts.app')

@section('content')
    @if (Auth::check())
        {{ Auth::user()->name }}
    @else
    <div id="js-loading">
        <div class="js-spinner"></div>
    </div>
    @endif
@endsection

<script>
window.onload = function() {
    const spinner = document.getElementById('js-loading');
    spinner.classList.add('js-loaded');
}
</script>