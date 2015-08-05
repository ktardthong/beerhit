@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')

    <div class="table-bordered img-rounded" style="margin-top:20px">
        {{--<div class="container-fluid">--}}
            <div class="container">
            {!! Form::open(array('id' => 'search',
                                 'method' => 'POST',
                                 'url' => '/beer/'.$beer->slug.'/upload_callback',
                                 'files' => true)) !!}
                <h1>Upload image</h1>
                <div class="form-group">
                    {{--{!! Form::label(' Name') !!}--}}
                    {{--{!! Form::label(' Name',$beer->beer) !!}--}}
                    {!! Form::text('name', $beer->beer, array('placeholder'=>'', 'class' => 'form-control disabled')) !!}
                    {!! Form::hidden('beer_id', $beer->id, array('placeholder'=>'', 'class' => 'form-control')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Comment') !!}
                    {!! Form::text('beer_description', null, array('placeholder'=>'Picture description?', 'class' => 'form-control')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('beer Image') !!}
                    {!! Form::file('image', null) !!}
                </div>


                <div class="form-group">
                    <input type="checkbox" checked>Post on Facebook
                </div>


                <div class="form-group">
                    {!! Form::submit('Upload!',array('class' => 'form-control btn btn-default')) !!}
                </div>
            {!! Form::close() !!}
            </div>
        {{--</div>--}}
	</div>
@stop