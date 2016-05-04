(function() {

	angular
		.module('entreprenityApp.home', [])
		
		.factory('myHomeService', function($http) {
			var baseUrl = 'api/';
			return {
				getUserSessInfo: function() {
					return $http.get(baseUrl+ 'get_user_session');
				}
			};
		})
	
		.controller('HomeController', function($routeParams, myHomeService) {
				var vm = this;		
				
				//To get user session value
				myHomeService.getUserSessInfo().success(function(data) {
					vm.id = data.id;
				});
		});

})();
