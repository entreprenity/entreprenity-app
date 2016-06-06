(function() {

	angular
		.module('entreprenityApp.notifications', [])

		.factory('notificationsService', function($http) {
			var baseUrl = 'api/';

			return {	
				getNotifications: function(userName) 
				{
					return $http.get(baseUrl+ 'getMyPreferences');
				}
			};
		})

		.controller('NotificationsController', function($routeParams, notificationsService) {
			var vm = this;
			vm.memberUserName = $routeParams.memberUserName;
		
			//dummy data (sample json)
			
			data = [
				{
					"notif_type": "follow",
					"notif_author": {						
						"id": "2",
						"firstName": "Will",
						"lastName": "Ferrel",
						"avatar": "member-default.jpg",
						"position": "CEO",
						"companyName": "Clever Sheep",
						"userName": "will"
					},
					"post_id": "1234",
					"created_at": "2016-05-26 14:29:00"
				},
				{
					"notif_type": "comment",
					"notif_author": {						
						"id": "2",
						"firstName": "Will",
						"lastName": "Ferrel",
						"avatar": "member-default.jpg",
						"position": "CEO",
						"companyName": "Clever Sheep",
						"userName": "will"
					},
					"post_id": "1234",
					"created_at": "2016-05-26 14:29:00"
				},
				{
					"notif_type": "like",
					"notif_author": {						
						"id": "2",
						"firstName": "Will",
						"lastName": "Ferrel",
						"avatar": "member-default.jpg",
						"position": "CEO",
						"companyName": "Clever Sheep",
						"userName": "will"
					},
					"post_id": "1234",
					"created_at": "2016-05-26 14:29:00"
				},
			]
			
		
			vm.notifications = data;
			/*
			notificationsService.getNotifications(vm.memberUserName).success(function(data) {
				vm.notifications = data;
			});	
			*/
		});
})();
