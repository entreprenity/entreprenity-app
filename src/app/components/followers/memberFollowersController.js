(function() {
	angular
		.module('entreprenityApp.memberFollowers', [])

		.factory('memberFollowersService', function($http) {
			var baseUrl = 'api/';
			return {
				getMemberFollowers: function(userName) 
				{
					return $http.get(baseUrl+ 'view_user_profile?id='+userName);
				}			
			};
		})

		.controller('MemberFollowersController', function($routeParams, memberFollowersService) {
			var vm = this;
			vm.memberUserName = $routeParams.memberUserName;
		
			data = {
				"id": 1,
				"userName": "jordan",
				"avatar": "member01.jpg",
				"coverPhoto": "memberCover01.jpg",
				"firstName": "Jordan",
				"lastName": "Rains",
				"position": "Office Assistant",
				"company": [
					{
						"companyName": "Pet Studio.com",
						"companyDesc": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
					}
				],
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
		
			vm.member = data;
		
		console.log(vm.member);
		
			/*
			memberFollowersService.getMemberFollowers(vm.memberUserName).success(function(data) {
				vm.member = data;
			});
			*/
		});			
})();

