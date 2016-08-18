(function() {

	angular
		.module('entreprenityApp.common', [])
		
		.factory('myCommonService', function($http) {
			var baseUrl = 'api/';
			return {
				getBasicUserInfo:function() {
					return $http.get(baseUrl + 'getBasicUserInformation');
				}
			};
		})
		
		.controller('CommonController', function($scope, $routeParams, myCommonService, AuthService) {
				var vm = this;		
			    //If user is not logged in
				var token;
				if (localStorage['entrp_token'])
				{
			    	token = JSON.parse(localStorage['entrp_token']);
				} 
				else 
				{
					token = "";
				}
				
				if(token)
				{
					AuthService.checkToken(token);
					//to get basic user information
					myCommonService.getBasicUserInfo().success(function(data) {
						vm.id 			= data.id;
						vm.firstName 	= data.firstName;
						vm.lastName 	= data.lastName;
						vm.position 	= data.position;
						vm.myOffice 	= data.myOffice;
						vm.avatar 		= data.avatar;
						vm.userName 	= data.userName;
						vm.companyUserName 	= data.companyUserName;
						
						$scope.userName 			= data.userName;
						$scope.companyUserName 	= data.companyUserName;
					});
				}
				
				
				$scope.logout = function(){
					var data = {
						token: token
					}
					AuthService.logOut(token);
				}
					
				$scope.numOfNotifications = 5;// need a service that will store the number of unred notif to this variable
				$scope.readNotifications = function() {
					//service to update notif read number to 0
					//add ng-click="vm.readNotifications()"
				}
				
		});
	
		

})();
