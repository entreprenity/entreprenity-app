(function() {

	angular
		.module('entreprenityApp.home', [])
		
		.factory('myHomeService', function($http) {
			var baseUrl = 'api/';
			return {
				getUserSessInfo: function() {
					return $http.get(baseUrl+ 'get_user_session');
				},
				getNewMembers:function() {
					return $http.get(baseUrl + 'getNewMembers');
				},
				getLatestEvents:function() {
					return $http.get(baseUrl + 'getLatestEvents');
				},
				getBasicUserInfo:function() {
					return $http.get(baseUrl + 'getBasicUserInformation');
				}
			};
		})
		
		.controller('HomeController', function($scope, $routeParams, myHomeService, AuthService) {
				var vm = this;		

			    //If user is not logged in
				var token;
				if (localStorage['entrp_token'])
				{
			    	token = JSON.parse(localStorage['entrp_token']);
				} 
				else 
				{
					token = "something stupid";
				}
				AuthService.checkToken(token);
				
				$scope.logout = function(){
					var data = {
						token: token
					}
					AuthService.logOut(token);
				}

				
				//To get user session value
				myHomeService.getUserSessInfo().success(function(data) {
					vm.id 			= data.id;
				});
				
				//To get new members
				myHomeService.getNewMembers().success(function(data) {
					vm.newMembers = data;
				});
				
				//to get latest events
				myHomeService.getLatestEvents().success(function(data) {
					vm.latestEvents = data;
				});
				
				//to get basic user information
				myHomeService.getBasicUserInfo().success(function(data) {
					vm.firstName 	= data.firstName;
					vm.lastName 	= data.lastName;
					vm.position 	= data.position;
					vm.myOffice 	= data.myOffice;
					vm.avatar 		= data.avatar;
					vm.userName 	= data.userName;
					vm.companyUserName 	= data.companyUserName;
				});

		});
	
		

})();
