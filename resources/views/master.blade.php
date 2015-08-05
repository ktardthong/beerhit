<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="@yield('description')">
    <link rel="icon" href="../../favicon.ico">

    <title>@yield('title')</title>


    <!-- Bootstrap core CSS-->
    <link href="/css/bootstrap.css" rel="stylesheet">



	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>

    <link href="/css/jumbotron-narrow.css" rel="stylesheet">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <link href="//fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="/css/custom.css" rel="stylesheet">


  </head>

<?php
$home  = url('/', $parameters = array(), $secure = null);
$login = url('auth/login', $parameters = array(), $secure = null);
$register = url('auth/register', $parameters = array(), $secure = null);
?>

<body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- LOGO HERE -->
          <a class="navbar-brand" href="/">
            <img src="/img/home_logo_100.jpg" class="img-responsive" width="60px">
          </a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li>
                <a>
                {!! Form::open(array('id' => 'search', 'url' => 'search')) !!}
                    {!! Form::text('global_query', '' , array('placeholder' => 'Search for beer..',
                                                              'class'=>'form-control',
                                                              'autocomplete'=>'off',
                                                              'id'=>'global_query')) !!}
                {!! Form::close() !!}
                </a>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            @if(Auth::user())
                 <li>
                    {!!\GlobalUrl::user_profile_pic(Auth::user()->username, Auth::user()->avatar,'img-thumbnail') !!}
                 </li>
            @else
            <li><a href="/login/fb" class="navbar-btn btn btn-default"> Login with <i class="fa fa-facebook-square fa-2"></i></a></li>
            @endif
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>


	@yield('content')


</body>
	
</html>