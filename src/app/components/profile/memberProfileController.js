(function() {

	angular
		.module('entreprenityApp.memberProfile', [])

		.factory('memberProfileService', function($http) {
		
			var baseUrl = 'api/';
			return {
				getMemberProfile: function(id) {
					return $http.get(baseUrl+ 'view_user_profile?id='+id);
				}
			};
		})

		.controller('memberProfileController', function($routeParams, memberProfileService) {
			var vm = this;
			vm.memberId = $routeParams.memberId;
			
			/*
			data = {
				"id": 1,
				"avatar": "member01.jpg",
				"coverPhoto": "memberCover01.jpg",
				"firstName": "Ken",
				"lastName": "Sia",
				"position": "Front-end Web Developer",
				"city": "Taguig",
				"followers": "2",
				"following": "10",
				"aboutMe": "Front-end Web Developer who loves listening to music, surfing, and traveling",
				"email": "ken.voffice@gmail.com",
				"website": "ken.com.ph",
				"mobile": "09175296299",
				"company": {
					"companyName": "voffice",
					"companyDesc": "We provide businesses superior reach and access to South East Asia markets like Jakarta, Manila, Kuala Lumpur and Singapore"
				},
				"skills": [
					{"item": "Programming"},
					{"item": "Public Speaking"}
				],
				"interests": [
					{"item": "Design"},
					{"item": "Surf"},
					{"item": "Basketball"}
				]
			};

		
			vm.member = data;
			*/
			
			memberProfileService.getMemberProfile(vm.memberId).success(function(data) {
				vm.member = data;
			});	
			
		});			
})();
