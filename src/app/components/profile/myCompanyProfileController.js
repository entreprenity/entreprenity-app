(function() {

	angular
		.module('entreprenityApp.myCompanyProfile', [])

		.factory('myCompanyProfileService', function($http) {
		var baseUrl = 'api/';

		return {
			getCompanyProfile: function(id) {
				return $http.get(baseUrl+ 'get_my_company_profile');
			},			
			postCompanyProfile: function(userdata) 
			{
				return $http({ method: 'post',
											url: baseUrl+'update_my_company_profile',
											data: $.param(userdata),
											headers: {'Content-Type': 'application/x-www-form-urlencoded'}
										 });
			}

		};
	})

		.controller('MyCompanyProfileController', function($routeParams, myCompanyProfileService, $scope, $uibModal) {
		var vm = this;

		vm.open = function (size) {
			//alert('modal');
			var modalInstance = $uibModal.open({
				animation: $scope.animationsEnabled,
				templateUrl: 'app/components/modal/imageUpload.html',
				controller: 'ImageUploadController',
				size: size,
			});

			modalInstance.result.then(function (myCroppedImage) {
				vm.company.avatar = myCroppedImage;
			}, function () {
				$log.info('Modal dismissed at: ' + new Date());
			});
		};

		vm.companyId = $routeParams.companyId;
		
		//alert(vm.companyId);
		vm.editState = false;

		//get initial data
		myCompanyProfileService.getCompanyProfile(vm.companyId).success(function(data) {
			//alert(data);
			vm.company = data;
		});	


		//when user click save, will post data to update in backend
		vm.updateData = function(userdata) {
			myCompanyProfileService.postCompanyProfile(userdata).success(function(data) {
				vm.company = data;
				vm.editState = false;
			});	
		};

		//when user click cancel, will reload data and cancel all changes to the model
		vm.reloadData = function() {
			myCompanyProfileService.getCompanyProfile(vm.companyId).success(function(data) {
				vm.company = data;
				vm.editState = false;
			});	
		};
	});
})();

/*

data = {
	"id": 1,
	"profilePhoto": "member01.jpg",
	"coverPhoto": "memberCover01.jpg",
	"companyName": "vOffice",
	"location": "Fort Legend Tower",
	"companyDesc": "We provide businesses superior reach and access to South East Asia markets like Jakarta, Manila, Kuala Lumpur and Singapore.",
	"email": "info@voffice.com",
	"website": "voffice.com.ph",
	"mobile": "6322242000",
	"category": [
		"Virtual Office",
		"Serviced Office",
		"Coworking Space"
	],
	"allCategory" : []
};
*/