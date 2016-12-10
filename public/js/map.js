/*
 * The methods responsible for displaying Google Maps with 
 * nearest restos on the index page.
 */

//global scope for the variable
var g = {};

/**
 * Gets the latitude and longitude from database restos.
 * @param json with restos data.
 */
function init(json) {
    g.coords = [];
    g.restos = [];
    for(var i = 0; i < json.length; i++) {
        g.coords[i] = {lat: parseFloat(json[i].latitude), 
                       lng: parseFloat(json[i].longitude)};
        g.restos[i] = json[i];
    }
}

/**
 * Creates a map with restos markers.
 * When the marker is clicked, the resto information is displayed.
 */
function initMap() {
    var markers = [];
    var infowindow = new google.maps.InfoWindow();
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 9,
      center: g.coords[0]
    });    
    for(var i = 0; i < g.coords.length; i++) {
        markers[i] = new google.maps.Marker({
          position: g.coords[i],
          map: map,
          title: g.restos[i].name
        });
        setInfoMarker(markers[i], i, infowindow);
    }
}

/**
 * Sets the infowindow for the marker with all the information about this resto.
 * @param marker the marker on the map.
 * @param i the index for this resto.
 * @param infowindow to set.
 */
function setInfoMarker(marker, i, infowindow) {
    var content = "<div id='popup'><p class='center-text'><b>"+g.restos[i].name
        +"</b></p><p><b>Genre: </b>"+g.restos[i].genre
        +"</p><p><b>Price: </b>"+g.restos[i].price
        +"</p><p><b>Address: </b>"+g.restos[i].address
        +"</p><p class='center-text'><a href='/resto/view/"+g.restos[i].id
        +"'><i>View details..</i></a></p></div>";

    google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
        return function() {
            infowindow.setContent(content);
            infowindow.open(map,marker);
        };
    })(marker,content,infowindow));
}


