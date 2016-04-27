(function() {

	angular
		.module('entreprenityApp.companyProfile', [])

		.factory('companyProfileService', function($http) {

		var baseUrl = 'api/';
		return {
			getCompanyProfile: function(id) {
				return $http.get(baseUrl+ 'view_user_profile?id='+id);
			}
		};
	})

		.controller('companyProfileController', function($routeParams, companyProfileService) {
		var vm = this;
		vm.companyId = $routeParams.memberId;

		data = {
			"id": 1,
			"name": "vOffice",
			"location": "Fort Legend Tower",
			"coverPhoto": "cover.jpg",
			"profilePhoto": "company-default.jpg",
			"website": "voffice.com.ph",
			"email": "sales@voffice.com",
			"mobile": "639175296299",
			"tel": "6322931533",
			"fax": "6329165745",
			"desc": "We provide businesses superior reach and access to South East Asia markets like Jakarta, Manila, Kuala Lumpur and Singapore.",
			"followers": "10",
			"categories": [
				"virtual office",
				"serviced office",
				"co-working spaces"
			],
			"employees": [
				{
					"id": "1",
					"firstName": "Ken",
					"lastName": "Sia",
					"profilePhoto": "member01.jpg"
				},
				{
					"id": "2",
					"firstName": "Jaye",
					"lastName": "Atienza",
					"profilePhoto": "member02.jpg"
				}
			]
		};

			vm.company = data;
			
			/*
		  memberProfileService.getCompanyProfile(vm.memberId).success(function(data) {
			vm.member = data;
			*/
		});			
})();
