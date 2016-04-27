(function() {

	angular
		.module('entreprenityApp.eventsPage', [])

		.factory('eventsPageService', function($http) {

		var baseUrl = 'api/';
		return {
			getEventProfile: function(id) {
				return $http.get(baseUrl+ 'view_user_profile?id='+id);
			}
		};
	})

		.controller('eventsPageController', function($routeParams, eventsPageService) {
		var vm = this;
		//vm.eventId = $routeParams.eventId;

		data = {
			"id": 1,
			"name": "Master The Art Of Selling",
			"location": "Fort Legend Tower",
			"gmapLong": 121.04692,
			"gmapLat": 14.55408,
			"date": "4-25-2016",
			"startTime": "10:00",
			"endTime": "19:00",
			"eventPhoto": "event.jpg",
			"about": "We will teach you on how to master selling and generate more sales for your brand or company",
			"attendees": [
				{
					"id": "1",
					"firstName": "Ken",
					"lastName": "Sia",
					"profilePhoto": "emp1.jpg"
				},
				{
					"id": "2",
					"firstName": "Jaye",
					"lastName": "Atienza",
					"profilePhoto": "emp2.jpg"
				}
			]
		};

		vm.event = data;

		/*
		  memberProfileService.getEventProfile(vm.memberId).success(function(data) {
			vm.member = data;
			*/
	});			
})();
