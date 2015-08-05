@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')
	<div class="container">
		<div class="container-fluid table-bordered" align="center" style="max-width: 400px">
		    <form method="POST" action="/auth/register">
                {!! csrf_field() !!}

                <h1>Register</h1>

                <div class="container-fluid">
                    <div class="input-group input-group-lg">
                      <span class="input-group-addon" id="sizing-addon1">Username</span>
                      <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Username" aria-describedby="sizing-addon1">
                    </div>
                </div>

                <br>

                <div class="container-fluid">
                    <div class="input-group input-group-lg">
                      <span class="input-group-addon" id="sizing-addon1">email</span>
                      <input type="email"
                             name="email"
                             value="{{ old('email') }}" class="form-control"
                             placeholder="email"
                             aria-describedby="sizing-addon1">
                    </div>
                </div>

                <br>

                <div class="container-fluid">
                    <div class="input-group input-group-lg">
                      <span class="input-group-addon" id="sizing-addon1">password</span>
                      <input type="password"
                             name="password"
                             value="{{ old('email') }}" class="form-control"
                             placeholder="email"
                             aria-describedby="sizing-addon1">
                    </div>
                </div>

                <br>

                <div class="container-fluid">
                    <div class="input-group input-group-lg">
                      <span class="input-group-addon" id="sizing-addon1">Confirm Password</span>
                      <input type="password"
                             name="password_confirmation"
                             value="{{ old('email') }}" class="form-control"
                             placeholder="email"
                             aria-describedby="sizing-addon1">
                    </div>
                </div>

                <br>

                <div>
                    <button type="submit"
                            class="form-control"
                            >Register</button>
                </div>

                <br>

                 OR

                <a href="/login/fb"> <i class="fa fa-facebook-square fa-2"></i></a>

            </form>
        </div>
    </div>


@stop