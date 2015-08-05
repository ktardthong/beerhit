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
        {!! Form::open(array('id' => 'search',
                             'method' => 'POST',
                             'url' => "/checkin_callback",
                             'files' => true)) !!}
            <h1>Check-in</h1>


            <div class="form-group">
                {!! Form::label(' Name','') !!}
                {!! Form::text('name', isset($beer->beer)?$beer->beer:null,  array('placeholder'=>'', 'class' => 'form-control','id'=>'name')) !!}
                {!! Form::hidden('beer_id', isset($beer->id)?$beer->id:null, array('placeholder'=>'', 'class' => 'form-control','id'=>'beer_id')) !!}
                {!! Form::text('description', null, array('placeholder'=>'Angthing to say?', 'class' => 'form-control')) !!}

            </div>

            <div class="form-group">
                {!! Form::label('beer Image') !!}
                {!! Form::file('image', null) !!}
            </div>

            <div class="form-group">
                {!! Form::label('location','Location') !!}
                {!! Form::text('location', isset($place->name)?<0place></0place>->name:null, array( 'placeholder'=>'where are you?',
                                                        'class' => 'form-control')
                                                       )!!}
                {!! \FacebookHelper::htmlPlaceFB($place);

            </div>

            <img id="loading_icon" src="http://s7d2.scene7.com/is/content/ihg/sitefurniture/cp_loading.gif">
            <div id="location_map"></div>


            <div class="form-group">
                {!! Form::submit('Check in!',array('class' => 'form-control btn btn-default')) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>

    <script>
        $(function() {

            $( "#name" ).autocomplete({
              source: '/ajax/searchBeer',
              select: function( event, ui ) {
                      $("#beer_id").val(ui.item.id);
                    }
            });

            $("#loading_icon").hide();
            $( "#location" ).autocomplete({
                timeout: 8000,
                minLength: 2,
                clearCache:true,
                deferRequestBy: 200, //200ms
                source: '/ajax/ajaxCheckin',
                    search:  function () {
                    $("#loading_icon").show()
                },
                focus: function( event, ui ) {
                      $( "#project" ).val( ui.item.label );
                      return false;
                },
                select: function( event, ui ) {
                    $("#loading_icon").hide();
                    $('#fb_place_id').val(ui.item.id);
                    $('#fb_place_name').val(ui.item.label);
                    $('#fb_address').val(ui.item.street);
                    $('#fb_category').val(ui.item.category);
                    $('#fb_lat').val(ui.item.lat);
                    $('#fb_lng').val(ui.item.lng);
                    $('#fb_city').val(ui.item.city);
                    $('#fb_state').val(ui.item.fb_state);
                    $('#fb_country').val(ui.item.country);
                    $('#fb_zip').val(ui.item.zip);
                    $('#location_map').html('<img src=\"https://maps.googleapis.com/maps/api/staticmap?center='+ui.item.lat+","+ui.item.lng+'&zoom=16&size=280x140&markers=color:blue%7Clabel:S%7C'+ui.item.lat+","+ui.item.lng+'\">')
                }
            })
            .autocomplete( "instance" )._renderItem = function( ul, item ) {
                  return $( "<li>" )
                    .append( "<a>" + item.label + "<br>" + item.street + "</a>" )
                    .appendTo( ul );
            };
        });
    </script>
@stop