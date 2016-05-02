(function() {

angular
	.module('entreprenityApp.myProfile', [])

	.factory('myProfileService', function($http) {
		var baseUrl = 'api/';
		return {
			getMemberProfile: function(id) {
				return $http.get(baseUrl+ 'view_company_profile?id='+id);
			},
			postMemberProfile: function(id) {
				return $http.post(baseUrl+ 'view_company_profile?id='+id);
			}
		};
	})

	.controller('MyProfileController', function($routeParams, myProfileService) {
		var vm = this;
		vm.id = $routeParams.memberId;
		vm.editState = true;
	
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
				"Programming",
				"Public Speaking"
			],
			"interests": [
				"Design",
				"Surf",
				"Basketball"
			]
		};

		vm.member = data;
	
		/*
		
		//get initial data
		myProfileService.getMemberProfile(vm.memberId).success(function(data) {
			vm.member = data;
		});	
		
		//when user click save, will post data to update in backend
		vm.updateData = function() {
			myProfileService.postMemberProfile(vm.memberId).success(function(data) {
				vm.member = data;
				vm.editState = false;
			});	
		};
		
		//when user click cancel, will reload data and cancel all changes to the model
		vm.reloadData = function() {
			myProfileService.getMemberProfile(vm.memberId).success(function(data) {
				vm.member = data;
				vm.editState = false;
			});	
		};
		
		*/
	

	});
		
})();
