window.App = {
	uid: null,
	accessToken: null,
	login: function(){
		FB.login(function(response) {
		if (response.authResponse) {
			App.accessToken = response.authResponse.accessToken;
			App.ajaxCall('loginCallback',{},function(response){
				App.uid = response.userId;
				console.log(response);
				window.location.href = 'explore.php';
			});
			} else {
				console.log('User cancelled login or did not fully authorize.');
			}
		},{scope:'publish_actions,publish_checkins,user_status'});
	},
	redirectCheck: function(){
		FB.getLoginStatus(function(response) {
		  if (response.status === 'connected') {
		  	App.uid = response.authResponse.userID;
		    App.accessToken = response.authResponse.accessToken;
		  } else {
		    window.location.href = 'index.php';
		  }
		 });
	},
	ajaxCall: function(action,params,callback){
		var url = 'ajax.php?action='+action; 

		if(App.accessToken)
		{
			url += "&access_token="+App.accessToken;
		}
		return $.ajax({
			url: url,
			type: 'POST',
			cache: false,
			dataType: 'json',
			data: $.param(params),
			context: this,
			success: function(response)
			{
				if($.isFunction(callback)){
					callback(response);
				}
			},
			error:function (xhr){
				console.log(xhr);
			}
		});
	},
	init: function(){
				  (function(d){
		     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement('script'); js.id = id; js.async = true;
		     js.src = "//connect.facebook.net/en_US/all.js";
		     ref.parentNode.insertBefore(js, ref);
		   }(document));

	}
};

App.init();
