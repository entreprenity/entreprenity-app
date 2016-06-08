(function() {

	angular
		.module('entreprenityApp.notifications', [])

		.factory('notificationsService', function($http) {
			var baseUrl = 'api/';

			return {	
				getUserSessInfo: function() {
					return $http.get(baseUrl+ 'get_user_session');
				},
				getNotifications: function(userName) 
				{
					return $http.get(baseUrl+ 'getMyNotifications?user='+userName);
				}
			};
		})

		.controller('NotificationsController', function($routeParams, notificationsService) {
			var vm = this;
			
			//To get user session value
			notificationsService.getUserSessInfo().success(function(data) {
				vm.id 			= data.id;
				vm.userName 	= data.username;
				
				notificationsService.getNotifications(vm.userName).success(function(data) {
					vm.notifications = data;
				});	
			});
			
			
		});
})();
