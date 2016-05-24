(function() {

	angular
		.module('entreprenityApp.settings', [])

		.factory('settingsService', function($http) {
			var baseUrl = 'api/';

			return {	
				getSettings: function(userName) 
				{
					//insert service here
				},
				postSettings: function(userSettings) 
				{
					//insert service here
				}
			};
		})

		.controller('SettingsPageController', function($routeParams, settingsService) {
			var vm = this;
			vm.memberUserName = $routeParams.memberUserName;
		
			//dummy data (sample json)
			data = {
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
				"mentions": true
			}
			vm.settings = data;
		
			//sample get service, this will replace the dummy data once the settings data is available in the db already
			/*
			settingsService.getSettings(vm.memberUserName).success(function(data) {
				vm.settings = data;
			});	
			*/
		
			//when user clicks SAVE SETTINGS, vm.settings object will be passed in a service to update settings in the db
			vm.submitSettings = function() {
				alert('Submit Settings');
				console.log(vm.settings);
				//sample post service
				/*
				settingsService.postSettings(vm.settings).success(function(data) {
					vm.settings = data;
				});	
				*/
			};
		});
})();
