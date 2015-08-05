@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')

    <div class="table-bordered img-rounded" style="margin-top:20px">
            <div class="container">
            {!! Form::open(array('id' => 'add',
                                 'method' => 'POST',
                                 'url' => '/add_beer/upload_callback',
                                 'files' => true)) !!}
                <h1>Add Beer</h1>
                <div class="form-group">
                    {!! Form::text('name', NULL, array('placeholder'=>'new beer', 'class' => 'form-control disabled')) !!}
                    {!! Form::hidden('beer_id', NULL, array('placeholder'=>'', 'class' => 'form-control')) !!}
                </div>


                <div class="form-group">
                    {!! Form::text('abv', NULL, array('placeholder'=>'ABV', 'class' => 'form-control disabled','type'=>'number')) !!}
                    {!! Form::hidden('beer_id', NULL, array('placeholder'=>'', 'class' => 'form-control')) !!}
                </div>

                <div class="form-group">
                    {!! Form::text('style', 'beer style', array('placeholder'=>'Picture description?', 'class' => 'form-control')) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('beer Image') !!}
                    {!! Form::file('image', null) !!}
                </div>


                <div class="form-group">
                    {!! Form::submit('Upload!',array('class' => 'form-control btn btn-default')) !!}
                </div>
            {!! Form::close() !!}
            </div>
	</div>
@stop