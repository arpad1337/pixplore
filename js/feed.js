var Feed = {
	pictures : [],
	places: [],
	init : function(){
		//$(document).ready(function(){
			//App.compile_tmpls();
			//App.uploadify();

			Feed.Instajam = new Instajam({
		      //ccess_token: 'ACCESS TOKEN',
		      client_id: '7642d05bb0564e7a90969e327daebe5b'
		    });


		//});
	},
	getPlaces : function(){
		App.ajaxCall('getPlaces',{},function(response){
			$.each(response,function(key, val){
				console.log(this.name);
				Feed.places.push(this);
			});
			Feed.showPlaces('#places');
		});
	},
	showPlaces : function(target){
		$(target).html('');
		$.each(Feed.places, function(key, value){
			$(target).append('<li><div class="thumbnail span2"><a href="explore.php?id='+this.id+'" target="_blank"><img src="'+this.cover_url+'" /><p>'+ this.name +'</p></div></li>');
		});
	},
	getPhotos : function(id,target){
		var options = {};
		Feed.Instajam.location.get(id, options, function(response){
			Feed.pictures = [];
			$.each(response.data,function(key,val){
				Feed.pictures.push(this);
			});

			var image = (response.data.length > 0)?response.data[0].images.thumbnail.url:"";

			App.ajaxCall('registerPlace',{id:Feed.cobject.id,name:Feed.cobject.name,cover_url:image,location:
				{
					latitude:Feed.cobject.latitude,
					longitude:Feed.cobject.longitude
				}
			},function(response){
				Map.discover(response.id);
			});

			console.log(response);

			if(response.pagination !== undefined){
				Feed.pagination_url = response.pagination.next_url;
			}

			Feed.showPhotos(target);
		});
		App.ajaxCall('addViewCount',{ place_id : id, user_id : App.uid},function(response){
			console.log(response);
		});
	},
	showPhotos : function(target){
		$(target).html('');
		$.each(Feed.pictures, function(key, value){
			$(target).append('<li><a href="'+this.link+'" target="_blank"><img src="'+this.images.thumbnail.url+'" class="thumbnail" /><p>'+ this.created_time +'</p></li>');
		});

		if(Feed.pagination_url !== undefined){
			$('#more_btn').remove();
			$('#photo-container').append('<button onclick="Feed.getMore(\''+target+'\')" class="btn" id="more_btn">More</button>');
		} else {
			$('#more_btn').remove();
		}
	},
	getMore : function(target){
		Feed.target = target;
		Feed.Instajam.nextPage(Feed.pagination_url, function(response){
			$.each(response.data,function(key,val){
				Feed.pictures.push(this);
			});

			if(response.pagination !== undefined){
				Feed.pagination_url = response.pagination.next_url;
			} else {
				Feed.pagination_url = "";
			}

			Feed.showPhotos(Feed.target);
		});
	}
};

$(document).ready(function(){
	Feed.init();
});