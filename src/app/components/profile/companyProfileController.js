(function() {

	angular
		.module('entreprenityApp.companyProfile', [])

		.factory('companyProfileService', function($http) {

		var baseUrl = 'api/';
		return {
			getCompanyProfile: function(id) {
				return $http.get(baseUrl+ 'view_company_profile?id='+id);
			},
			postCompanyFollow: function(companyUserName) 
			{
				var dataContent = {
		            'company' : companyUserName
		        };
		        
				return $http({ method: 'post',
								url: baseUrl+'followThisCompany',
								data: $.param(dataContent),
								headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							});
			},
			postCompanyUnFollow: function(companyUserName) 
			{
				var dataContent = {
		            'company' : companyUserName
		        };
		        
				return $http({ method: 'post',
								url: baseUrl+'unfollowThisCompany',
								data: $.param(dataContent),
								headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							});
			}
		};
	})

		.controller('CompanyProfileController', function($routeParams, companyProfileService) {
		var vm = this;
		vm.companyUserName = $routeParams.companyUserName;
		
		companyProfileService.getCompanyProfile(vm.companyUserName).success(function(data) {
			vm.company = data;
			vm.company.followed = false;
		});
		
		//when user click follow, will post data to follow member and update in backend
		vm.follow_this_company = function(companyUserName) {
			companyProfileService.postCompanyFollow(companyUserName).success(function(data) {
				vm.company = data; //return company info, with updated followers and followed status
			});	
		};

		//when user click unfollow, will post data to unfollow member and update in backend
		vm.unFollow_this_company = function(companyUserName) {
			companyProfileService.postCompanyUnFollow(companyUserName).success(function(data) {
				vm.company = data; //return company info, with updated followers and followed status
			});	
		};
	});			
})();
