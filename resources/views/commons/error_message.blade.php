@if (session('error_message'))
    <div class="error_message">
        {{ session('error_message') }}
    </div>
@endif