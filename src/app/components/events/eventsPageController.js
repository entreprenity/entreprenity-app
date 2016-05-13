(function() {

	angular
		.module('entreprenityApp.eventsPage', [])

		.factory('eventsPageService', function($http) {

		var baseUrl = 'api/';
		return {
			getEventProfile: function(id) {
				return $http.get(baseUrl+ 'view_event_detail?id='+id);
			},
			postGoingtoEvent: function(eventId) {
				var dataContent = {
		            'event' : eventId
		        };
		        
				return $http({ method: 'post',
								url: baseUrl+'goingForEvent',
								data: $.param(dataContent),
								headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							});
			},
			postNotGoingtoEvent: function(eventId) {
				var dataContent = {
		            'event' : eventId
		        };
		        
				return $http({ method: 'post',
								url: baseUrl+'notGoingForEvent',
								data: $.param(dataContent),
								headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							});
			}	
			//post service for joinEvent()
			//post service for unJoinEvent()
		};
	})

		.controller('EventsPageController', function($routeParams, eventsPageService) {
		var vm = this;

		vm.eventId = $routeParams.eventId;
		

		eventsPageService.getEventProfile(vm.eventId).success(function(data) {
			vm.event = data;
		});
		
		vm.joinEvent = function(eventId) {
			//post service
			eventsPageService.postGoingtoEvent(eventId).success(function(data) {
					vm.event = data;
				});
		};
		vm.unJoinEvent = function(eventId) {
			//post service
			eventsPageService.postNotGoingtoEvent(eventId).success(function(data) {
					vm.event = data;
				});
		};
	});			
})();
