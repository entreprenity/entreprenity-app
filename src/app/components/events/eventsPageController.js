(function() {

	angular
		.module('entreprenityApp.eventsPage', [])

		.factory('eventsPageService', function($http) {

		var baseUrl = 'api/';
		return {
			getEventProfile: function(id) {
				return $http.get(baseUrl+ 'view_event_detail?id='+id);
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
			vm.event.joining = false;
		});
		
		vm.joinEvent = function() {
			//post service
			var attendees = [{
				"id": 1,
				"avatar": "member01.jpg",
				"coverPhoto": "memberCover01.jpg",
				"firstName": "Ken",
				"lastName": "Sia",
				"position": "Front-end Web Developer",
				"city": "Taguig",
			}];
			
			vm.event.joining = true;
			vm.event.attendees = attendees;
		}
		
		
		vm.unJoinEvent = function() {
			//post service
			var attendees = [];

			vm.event.joining = false;
			vm.event.attendees = attendees;
		}
	});			
})();
