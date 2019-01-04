		var map;

function initialize(long,lat) {

	var myLatlng = new google.maps.LatLng(lat,long);
	var mapOptions = {
		zoom: 14,
		scrollwheel: false,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);

	var marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		animation: google.maps.Animation.DROP,
		title: "Adonai Location"
	});

	var contentString = "";
	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});
}

function mygps(lat, long) {
	initialize(lat,long);
}
