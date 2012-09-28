window.Map = {
	map: null,
	geocoder: null,
	markerImage: '',
	markers: [],
	zoom: 12,
	idCounter:1,
	calculateRadius: function(center){
		var ne;
		if(Map.map.getBounds())
		{
			ne = Map.map.getBounds().getNorthEast();
		}
		else
		{
			return 5000;
		}
		
		// r = radius of the earth in statute km
		var r = 6371.0; 
		
		// Convert lat or lng from decimal degrees into radians (divide by 57.2958)
		var lat1 = center.lat() / 57.2958; 
		var lon1 = center.lng() / 57.2958;
		var lat2 = ne.lat() / 57.2958;
		var lon2 = ne.lng() / 57.2958;
		
		// distance = circle radius from center to Northeast corner of bounds
		return r * Math.acos(Math.sin(lat1) * Math.sin(lat2) + 
			Math.cos(lat1) * Math.cos(lat2) * Math.cos(lon2 - lon1)) * 1000;
	},
	markerClick: function() {
		Feed.cobject = this.object;
		var newId = "imagecont_"+Map.idCounter;
		var no = $("<div></div>").attr("id",newId);
		Map.idCounter++;
		var button = '<button class="btn btn-primary btn-large" type="button" onclick="Map.ihaveBeenThere(this,\''+this.object.name+'\',\''+this.object.latitude+'\',\''+this.object.longitude+'\')">I have been there</button>';
		no.append($("<div></div>").addClass("placeHeader").html($('<h1>'+this.object.name+'</h1><p>'+button+'</p>')));
		no.append($("<ul></ul>").addClass("thumbnails").html(""));
		$("#images").prepend(no);
		Feed.getPhotos(this.object.id,"#"+newId+" .thumbnails");
				window.location.hash = "images";
	},
	showPlacesByPosition: function()
	{
		var center = Map.map.getCenter();

		var dis = Map.calculateRadius(center);
		
		App.ajaxCall('search',
			{lat:center.lat(),lng:center.lng(),radius:dis},Map.drawMarkers);
		
	},
	drawMarkers: function(response)
	{
		if(response.data.length > 0)
		{
			Map.map.clearOverlays();
			Map.markers = [];
			
			var bounds = new google.maps.LatLngBounds();
			var Icon = new google.maps.MarkerImage(Map.markerImage, null, null, null, new google.maps.Size(26,24));

			for(var i = 0; i < response.data.length; i++)
			{
				position = new google.maps.LatLng(response.data[i].latitude,response.data[i].longitude);
				marker = new google.maps.Marker({
					position: position,
					map: Map.map,
					icon: Icon,
					animation: google.maps.Animation.DROP,
					object: response.data[i]
				});
				google.maps.event.addListener(marker, 'click', Map.markerClick);



				Map.markers.push(marker);
				
				bounds.extend(position);
			}

			Map.map.fitBounds(bounds);
			
		}
	},
	gotoCurrentPos: function(){
		navigator.geolocation.getCurrentPosition(function(position){
			App.ajaxCall('search',
			{lat:position.coords.latitude,lng:position.coords.longitude,radius:1000},Map.drawMarkers);
		});
	},
	search: function()
	{
		var address = $.trim($("#mapSearch").val());
		if(address !== "")
		{
			Map.geocoder.geocode( { 'address': address}, function(results, status) {
			if (status === google.maps.GeocoderStatus.OK) {
				Map.map.setCenter(results[0].geometry.location);
				Map.showPlacesByPosition();
			}
			});
		}
	},
	ihaveBeenThere: function(btn,name,lat,lng)
	{
		$(btn).remove();
		FB.api('search',{type:'place',center:lat+','+lng,distance:10},function(response){
			var place = response.data[0];
			console.log(response);
			FB.api('me/checkins','POST',{
				place:place.id,
				coordinates:
				{
					latitude:place.location.latitude,
					longitude:place.location.longitude
				}
			});
		});
	},
	discover: function(id)
	{
		FB.api('me/fbhack-kriek:discover','POST',{venue:'http://development.kriek.hu/pixplore/objects.php?objectId='+id});
	},
	init: function(){
		Map.markers = [];
		Map.geocoder = new google.maps.Geocoder();
		Map.markerImage = "img/pin.png";
		var params = {
			center: new google.maps.LatLng('47.494291','19.05393'),
			zoom: Map.zoom,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			disableDefaultUI: true, width: '100%', height: '100%'
		};
		Map.map = new google.maps.Map(document.getElementById('map'), params);
		google.maps.Map.prototype.clearOverlays = function() {
			for (var i = 0; i < Map.markers.length; i++ ) {
				Map.markers[i].setMap(null);
			}
			Map.markers = [];
		};
		Map.showPlacesByPosition();
	}
};