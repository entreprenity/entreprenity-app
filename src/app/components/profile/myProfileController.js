(function() {

angular
	.module('entreprenityApp.myProfile', [])

	.factory('myProfileService', function($http) {
		var baseUrl = 'api/';
		
		return {
			getMemberProfile: function(id) {
				return $http.get(baseUrl+ 'get_my_details');
			},			
			postMemberProfile: function(userdata) 
			{
				return $http({ method: 'post',
									url: baseUrl+'update_my_profile',
									data: $.param(userdata),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
			}
			/*,
			getUserSessInfo: function() {
					return $http.get(baseUrl+ 'get_user_session');
			}*/
			
		};
	})

	.controller('MyProfileController', function($routeParams, myProfileService, $scope, $uibModal,$http) {
		var vm = this;
	
		vm.open = function () {
			var modalInstance = $uibModal.open({
				animation: $scope.animationsEnabled,
				templateUrl: 'app/components/modal/imageUpload.html',
				controller: 'ImageUploadController',
				resolve: {
					id: function () 
					{
						var myid;
						var baseUrl = 'api/';
						$http.get(baseUrl+ 'get_user_session')
					    .then(function(response) {
					        myid = response.id;
					        return myid;
					    });
					     
					    /*
						//return 1;
						myProfileService.getUserSessInfo().success(function(data) {
							vm.id = data.id;
							
						});
						*/
					}
				}
			});
			
			modalInstance.result.then(function (myCroppedImage) {
				vm.member.avatar = myCroppedImage;
			}, function () {
				$log.info('Modal dismissed at: ' + new Date());
			});
		};
	
		vm.memberId = $routeParams.memberId;
		vm.editState = false;
		
		//get initial data
		myProfileService.getMemberProfile(vm.memberId).success(function(data) {
			vm.member = data;
			console.log(vm.member);
		});	

	
		//when user click save, will post data to update in backend
		vm.updateData = function(userdata) {
			myProfileService.postMemberProfile(userdata).success(function(data) {
				vm.member = data;
				vm.editState = false;
			});	
		};
		
		//when user click cancel, will reload data and cancel all changes to the model
		vm.reloadData = function() {
			myProfileService.getMemberProfile(vm.memberId).success(function(data) {
				vm.member = data;
				vm.editState = false;
			});	
		};
	});
})();
