(function() {

	angular
		.module('entreprenityApp.imageUpload', [])
		.controller('ImageUploadController', function($scope, $uibModalInstance, id) {
			$scope.id = id;
			$scope.myImage='';
			$scope.myCroppedImage='';

			$scope.handleFileSelect = function(evt){
				alert('test');
				var file = evt.files[0];
				var reader = new FileReader();
				reader.onload = function (evt) {
					$scope.$apply(function($scope){
						$scope.myImage = evt.target.result;
					});
				};
				reader.readAsDataURL(file);
			}
			
			
			$scope.ok = function () {
				//create a service to update the profile photo using $scope.id
				alert($scope.id);
				$uibModalInstance.close($scope.myCroppedImage);
			};

			$scope.cancel = function () {
				$uibModalInstance.dismiss('cancel');
			};
		});
})();