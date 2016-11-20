window.onload=init;

function init() {
    document.getElementById('hiddenForm').style.display = 'none';  
    getLocation();
}

function getLocation() {
    if (navigator.geolocation) {   //geolocation is supported by browser
        navigator.geolocation.getCurrentPosition(getPosition, getError);
    } else {   //geolocation is not supported by browser
        document.forms['hiddenForm'].elements['error'].value = '1';
        treatError();
    }
}

//set coordinates in hidden fields and submit
function getPosition(position) { 
    //1 km for better results on desktops
    if (position.coords.accuracy > 1000) { 
        document.forms['hiddenForm'].elements['error'].value = 6;
        treatError();
    }
    else {
        document.forms['hiddenForm'].elements['latitude'].value = position.coords.latitude;
        document.forms['hiddenForm'].elements['longitude'].value = position.coords.longitude;
        document.forms['hiddenForm'].elements['error'].value = 0; //no error
        document.forms['hiddenForm'].submit(); //submit form with geolocation info 
    } 
}

//geolocation was not successful
function getError(error) {   
    switch(error.code) {
        case error.PERMISSION_DENIED:
            document.forms['hiddenForm'].elements['error'].value = 2;
            break;
        case error.POSITION_UNAVAILABLE:
            document.forms['hiddenForm'].elements['error'].value = 3;
            break;
        case error.TIMEOUT:
            document.forms['hiddenForm'].elements['error'].value = 4;
            break;
        case error.UNKNOWN_ERROR:
            document.forms['hiddenForm'].elements['error'].value = 5;
            break;
    }
    treatError();
}

//geolocation was not successful, ask user for postal code instead
function treatError() {   
    document.getElementById('hiddenForm').style.display='block';  //unhide div 
}

