(function() {

	angular
		.module('entreprenityApp.settings', [])

		.factory('settingsService', function($http) {
			var baseUrl = 'api/';

			return {	
				getSettings: function(userName) 
				{
					return $http.get(baseUrl+ 'getMyPreferences');
				},
				postSettings: function(userSettings) 
				{
					var dataContent = userSettings;
			        
					return $http({ 
										method: 'post',
										url: baseUrl+'updateMyPreferences',
										data: $.param(dataContent),
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							 });
				},
				linkFacebookAccount: function(response,response2) 
				{
					var dataContent = {
			            'fid' 			: response.id,
			            'first_name' 	: response.first_name,
			            'last_name' 	: response.last_name,
			            'gender' 		: response.gender,
			            'email' 			: response.email,
			            'fbImage' 		: response2.data.url
			        };
			        
					return $http({ 
										method: 'post',
										url: baseUrl+'saveFacebookAuthData',
										data: $.param(dataContent),
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							 });
				}
			};
		})

		.controller('SettingsPageController', function($routeParams, settingsService,$scope) {
			var vm = this;
			vm.memberUserName = $routeParams.memberUserName;
		
			//dummy data (sample json)
			/*data = {
				"languages": [
					{"id": "0", "text": "EN", "image": "en.jpg"},
					{"id": "1", "text": "ID", "image": "id.jpg"},
					{"id": "2", "text": "MS", "image": "ms.jpg"},
					{"id": "3", "text": "ZH", "image": "zh.jpg"}
				],
				"selectedLanguage": {"id": "0", "text": "EN", "image": "en.jpg"},
				"timezones": [
					{"id": "0", "text": "SGT"},
					{"id": "1", "text": "IDT"},
					{"id": "2", "text": "EST"},
					{"id": "3", "text": "CET"}
				],
				"selectedTimezone": {"id": "0", "text": "SGT"},
				"followers": true,	
				"comments": true,
				"likes": true,
				"mentions": false,
				"businessOpportunities": false
			}
			vm.settings = data;*/
		
			//sample get service, this will replace the dummy data once the settings data is available in the db already
			
			settingsService.getSettings(vm.memberUserName).success(function(data) {
				vm.settings = data;
			});	
			
		
			//when user clicks SAVE SETTINGS, vm.settings object will be passed in a service to update settings in the db
			vm.submitSettings = function() {
				console.log(vm.settings);
				//sample post service
				
				settingsService.postSettings(vm.settings).success(function(data) {
					vm.settings = data;
				});	
				
			};
				
			$scope.FBConnect= function()
			{
				FB.login(function(response) 
				{
				    if (response.authResponse) 
				    {
				     //console.log('Welcome!  Fetching your information.... ');
				     FB.api('/me?fields=email,first_name,last_name,gender', function(response) {
				       console.log('Good to see you, ' + response.name + '.');
				       console.log(response.first_name);
				       console.log(response.last_name);
				       console.log(response.gender);
				       console.log(response.email);
				       
				       
				       if(response.id)
				       {
				       	var accessToken=FB.getAuthResponse();
				       	//console.log(accessToken);
				       	
				       	FB.api(
							    "/"+response.id+"/picture?type=large",
							    function (response2) 
							    {
							      if (response2 && !response2.error) 
							      {
							        var imageUrl=response2.data.url;
							        //console.log(imageUrl);
							      }
							      settingsService.linkFacebookAccount(response,response2).success(function(data) {
										vm.FBConnect = data;
									});
							    }
							);
							
							
				       }				       
				       
				     });
				    } 
				    else 
				    {
				     console.log('User cancelled login or did not fully authorize.');
				    }
				});
							
			};
			
		});
})();
