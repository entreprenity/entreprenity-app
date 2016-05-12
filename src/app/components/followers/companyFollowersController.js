(function() {
	angular
		.module('entreprenityApp.companyFollowers', [])

		.factory('companyFollowersService', function($http) {
		var baseUrl = 'api/';
		return {
			getCompanyFollowers: function(userName) 
			{
				return $http.get(baseUrl+ 'view_user_profile?id='+userName);
			}			
		};
	})

		.controller('CompanyFollowersController', function($routeParams, companyFollowersService) {
		var vm = this;
		vm.companyUserName = $routeParams.companyUserName;

		data = {
			"id": 1,
			"companyUserName": "nbbit",
			"profilePhoto": "company01.jpg",
			"coverPhoto": "memberCover01.jpg",
			"name": "Pet Studio.com",
			"location": "Fort Legend Tower",
			"followed": true,
			"followers": "1",
			"following": "1",
			"followersObjects": [
				{
					"id": 1,
					"username": "jordan",
					"avatar": "member01.jpg",
					"coverPhoto": "memberCover01.jpg",
					"firstName": "Jordan",
					"lastName": "Rains",
					"position": "Office Assistant",
					"company": [{
						"companyName": "Pet Studio.com",
						"companyDesc": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
					}]
				}
			]
		};

		vm.company = data;
		/*
			memberFollowersService.getMemberFollowers(vm.memberUserName).success(function(data) {
				vm.member = data;
			});
			*/
	});			
})();


