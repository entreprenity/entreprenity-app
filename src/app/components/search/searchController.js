(function() {
	angular
		.module('entreprenityApp.search', [])

		.factory('searchService', function($http) {
			var baseUrl = 'api/';
			
			return {
				getSearchResults: function(searchQuery)
				{ 
					return $http({
						method: 'post',
						url: baseUrl+'searchFor',
						data: $.param(searchQuery),
						headers: {'Content-Type': 'application/x-www-form-urlencoded'}
					})
				}
			};
		})
		.directive('search', function() {
			var controller = function($routeParams, searchService, $scope) {
				var vm = this;
				vm.isBusy =  true;

				vm.searchOnKeyup = function() {
					var searchQuery = {query: vm.searchQuery};
					vm.isBusy = true;

					console.log(searchQuery);
					console.log(vm.searchQuery.length);

					if(vm.searchQuery.length > 0) {
						//Service to get search results
						searchService.getSearchResults(searchQuery).success(function(data) {
							vm.searchResults = data;
							console.log(vm);
							vm.isBusy =  false;
						});
					} else {
						vm.isBusy =  true;
					}

				}
				
			};
		
			var template = '<button>{{vm.poststype}}</button>';

			return {
				restrict: 'E',
				scope: {

				},
				controller: controller,
				controllerAs: 'vm',
				bindToController: true, //required in 1.3+ with controllerAs
				templateUrl: 'app/components/search/search.html'
				//template: template
			};
		});		
})();
