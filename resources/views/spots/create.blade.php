@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <div class="container spot_form">
        <div class="row">
            <d class="mx-auto d-block col-6">
                {!! Form::model($spot, ['route' => 'spots.store', "enctype" => "multipart/form-data"]) !!}

                    <input type="text" placeholder="スポットを入力" id="address" class=spot__text>
                    <input onclick="codeAddress()" type="button" value="検索する">
                    <div id="map"></div>

                    {!! Form::hidden('latitude', 35.6594666, ['class' => 'form-control', 'id' => "spot_latitude"]) !!}
                    {!! Form::hidden('longitude', 139.7005536, ['class' => 'form-control', 'id' => "spot_longitude"]) !!}

                    <div class="form-group">
                        {!! Form::label('name', '釣り場名') !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    </div>
                    @if($errors->has('name'))
                        <span class="error_msg">
                            <p>{{ $errors->first('name') }}</p>
                        </span>
                    @endif

                    <div class="form-group">
                        {!! Form::label('explanation', '説明') !!}
                        {!! Form::text('explanation', null, ['class' => 'form-control']) !!}
                    </div>
                    @if($errors->has('explanation'))
                        <span class="error_msg">
                            <p>{{ $errors->first('explanation') }}</p>
                        </span>
                    @endif

                    <div class="form-group">
                        {!! Form::label('address', '所在地') !!}
                        {!! Form::text('address', null, ['class' => 'form-control']) !!}
                    </div>
                    @if($errors->has('address'))
                        <span class="error_msg">
                            <p>{{ $errors->first('address') }}</p>
                        </span>
                    @endif

                    <div class="form-group">
                    {!! Form::label('image', '画像') !!}
                        {{Form::file('image')}}
                    </div>

                    {!! Form::submit('投稿', ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @endif
@endsection