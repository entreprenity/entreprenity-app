(function() {

	angular
		.module('entreprenityApp.directory', [])
	
		.factory('directoryService', function($http) 
		{
			var baseUrl = 'api/';
			return {
				getMembers: function() {
					return $http.get(baseUrl + 'getMembers');
				}
				,
				getCompanies: function() {
					return $http.get(baseUrl + 'getCompanies');
				},
				getEvents: function() {
					return $http.get(baseUrl + 'getEvents');
				},
				getLocations:function() {
					return $http.get(baseUrl + 'getLocations');
				}
				
			};
		})
		/*
		.factory('Session', function($http) 
		{   
			 var baseUrl = 'api/'; 
		    return $http.get(baseUrl + 'get_user_session').then(function(result) {       
		        return result.data; 
		    });
		}) 
		*/
		//'$rootScope', 'Session', 
		.controller('DirectoryController',function(directoryService) {

			var vm = this;
			/*
			Session.then(function(response){
       		 $rootScope.session = response;
    		});
    		*/
			directoryService.getMembers().success(function(data) {
				vm.members =data;
			});
			
			directoryService.getCompanies().success(function(data) {
				vm.companies = data;
			});
			directoryService.getEvents().success(function(data) {
				vm.events = data;
			});
			
			directoryService.getLocations().success(function(data) {
				vm.locations = data;
			});
		});
				
})();
