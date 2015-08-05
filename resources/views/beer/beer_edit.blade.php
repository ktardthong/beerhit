@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')
    <div class="row">
        <div class="container">
            {!! Form::open(array('id' => 'edit',
                                             'method' => 'POST',
                                             'url' => '/beer/'.$beer->slug.'/edit_callback',
                                             'files' => true)) !!}
            <div class="form-group">
                {!! Form::label('beer','Beer name') !!}
                {!! Form::text('name', $beer->beer, array('placeholder'=>'', 'class' => 'form-control')) !!}
                {!! Form::hidden('beer_id', $beer->id, array('placeholder'=>'', 'class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('beer Logo') !!}
                {!! Form::file('image', null) !!}
            </div>
            <div class="form-group">
                {!! Form::submit('submit!',array('class' => 'form-control btn btn-default')) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>

@stop