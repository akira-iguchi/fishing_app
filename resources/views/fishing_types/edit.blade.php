@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('fishing_types.update', $fishing_type->id) }}" class="mt-5" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label for="user_name" class="required">名</label>

                                <input id="user_name" type="text" placeholder="お名前" name="fishing_type_name" value="{{ old('fishing_type', $fishing_type['fishing_type_name']) }}">




                                <label for="image">画像</label>
                                <input id="image" type="file" name="fishing_type_image">




                                <div class="user-edit_text">
                                    <textarea rows="5" id="textAreaIntroduction" name="content">{{ old('content', $fishing_type['content']) }}</textarea>
                                </div>

                            <div>
                                <button class="user_edit-button">更新&emsp;<i class="fas fa-angle-right fa-lg"></i></button>
                            </div>
                        </form>
@endsection