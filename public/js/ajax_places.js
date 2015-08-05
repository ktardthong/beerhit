$(function() {
    $( "#beer_search" ).autocomplete({
        source: '/ajax/searchBeer',
        select: function( event, ui ) {
            $("#beer_id").val(ui.item.id);
        }
    });

    $( "#location" ).autocomplete({
        timeout: 8000,
        minLength: 2,
        clearCache:true,
        deferRequestBy: 500, //500ms
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
            $('#location_map').html('<img class=\"img-responsive\" src=\"https://maps.googleapis.com/maps/api/staticmap?center='+ui.item.lat+","+ui.item.lng+'&zoom=16&size=480x140&markers=color:blue%7Clabel:S%7C'+ui.item.lat+","+ui.item.lng+'\">')
        }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<a>" + item.label + "<br>" + item.street + "</a>" )
            .appendTo( ul );
    };

}); //EOF