@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')
    <div class="container">
    		<div class="container" style="max-width: 600px">

                {!! Form::open(array('id' => 'edit',
                                                 'method' => 'POST',
                                                 'url' => '/register_callback',
                                                 'files' => true)) !!}
                <div>

                <div class="container-fluid">
                    <div class="col-xs-12 padding_box">
                        <h1>Register</h1>
                        <img src="{!! $user->avatar !!}" name="avatar">
                        {!! Form::file('image', null) !!}
                    </div>
                </div>

                <div class="container-fluid page-header">
                    <div class="col-xs-12 padding_box">
                        @if($user->username_flg ==0)
                            <div class="alert alert-info" role="alert">You can only change your username once</div>
                            {!! Form::text('username', $user->username, array('class' => 'form-control disabled ')) !!}
                        @else
                        @endif
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="col-xs-12 col-sm-6 padding_box">
                        {!! Form::text('firstname', $user->firstname, array('class' => 'form-control','placeholder'=>'firstname')) !!}
                    </div>

                    <div class="col-xs-12 col-sm-6 padding_box">
                        {!! Form::text('lastname', $user->lastname, array('class' => 'form-control','placeholder'=>'lastname')) !!}
                    </div>
                </div>


                <div class="container-fluid">
                    <div class="col-xs-12 padding_box">
                        {!! Form::text('email', $user->email, array('class' => 'form-control','placeholder'=>'Email')) !!}
                    </div>
                </div>


                <div class="container-fluid">
                    <div class="col-xs-12 col-sm-12">
                        Other info:
                        {!! Form::label($user->gender) !!} &bull;
                        {!! Form::label($user->city) !!} &bull;
                        {!! Form::label($user->country) !!}
                    </div>

                </div>


                <div class="container-fluid">
                    <div class="col-xs-12 padding_box ">
                        <input type="submit" class="form-control btn btn-default">
                    </div>
                </div>
                        {!! Form::hidden('fb_id'        ,   $user->fb_id)  !!}
                        {!! Form::hidden('access_token' ,   $user->access_token)  !!}
                        {!! Form::hidden('gender'       ,   $user->gender)  !!}
                        {!! Form::hidden('city'         ,   $user->city)    !!}
                        {!! Form::hidden('country'      ,   $user->country) !!}

                {!! Form::close() !!}
    		</div>
    	</div>
@stop