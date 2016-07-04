(function() {

	angular
		.module('entreprenityApp.eventsPage', [])

		.factory('eventsPageService', function($http) {

		var baseUrl = 'api/';
		return {
			getEventProfile: function(eventTagId) {
				return $http.get(baseUrl+ 'view_event_detail?id='+eventTagId);
			},
			postGoingtoEvent: function(eventTagId) {
				var dataContent = {
		            'event' : eventTagId
		        };
		        
				return $http({ method: 'post',
								url: baseUrl+'goingForEvent',
								data: $.param(dataContent),
								headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							});
			},
			postNotGoingtoEvent: function(eventTagId) {
				var dataContent = {
		            'event' : eventTagId
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

		.controller('EventsPageController', function($routeParams, eventsPageService,$scope) {
		var vm = this;

		vm.eventId = $routeParams.eventId;
		

		eventsPageService.getEventProfile(vm.eventId).success(function(data) {
			vm.event = data;
	  		
	  		$scope.map = 
	  		{ 
    			center: { latitude: data.map.center.latitude, longitude: data.map.center.longitude }, 
    			zoom: data.map.zoom 
  			};
  			
  			$scope.marker = { 
		    	coords: { latitude: data.map.center.latitude, longitude: data.map.center.longitude }, 
		    	id: data.id ,
		    	window: { title: data.address }
		   };
  
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
