(function() {

	angular
		.module('entreprenityApp.companyProfile', [])

		.factory('companyProfileService', function($http) {

		var baseUrl = 'api/';
		return {
			getCompanyProfile: function(id) {
				return $http.get(baseUrl+ 'view_company_profile?id='+id);
			}
		};
	})

		.controller('CompanyProfileController', function($routeParams, companyProfileService) {
		var vm = this;
		vm.companyId = $routeParams.companyId;
		
		companyProfileService.getCompanyProfile(vm.companyId).success(function(data) {
			vm.company = data;
		});
	});			
})();
