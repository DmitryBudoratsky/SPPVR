$(document).ready(function () {		 

	latitude = $('#latitude').val();
	longitude = $('#longitude').val();
	
	latLng = new google.maps.LatLng(latitude, longitude);
	
	map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
        center: latLng
    });
	
	var marker = new google.maps.Marker({
	    position: latLng
	});
	
	marker.setMap(map);	
})