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
				
			};
		})
	
		.controller('DirectoryController', function(directoryService) {

			var vm = this;
			
			directoryService.getMembers().success(function(data) {
				vm.members =data;
			});
			
			directoryService.getCompanies().success(function(data) {
				vm.companies = data;
			});
			directoryService.getEvents().success(function(data) {
				vm.events = data;
			});
			
		});
		

})();