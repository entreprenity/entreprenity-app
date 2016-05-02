(function() {

	angular
		.module('entreprenityApp.eventsPage', [])

		.factory('eventsPageService', function($http) {

		var baseUrl = 'api/';
		return {
			getEventProfile: function(id) {
				return $http.get(baseUrl+ 'view_event_detail?id='+id);
			}
		};
	})

		.controller('eventsPageController', function($routeParams, eventsPageService) {
		var vm = this;
		
		vm.eventId = $routeParams.eventId;

		eventsPageService.getEventProfile(vm.eventId).success(function(data) {
		vm.event = data;
		});
	});			
})();
