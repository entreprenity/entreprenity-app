(function() {

	angular
		.module('entreprenityApp.imageUpload', [])
		.controller('ImageUploadController', function($scope, $uibModalInstance, id) {
			$scope.text = '12312312312';
			$scope.myImage='';
			$scope.myCroppedImage='';
			$scope.imageSize={'width':'100%', 'height': 'auto'};	
			
			$scope.handleFileSelect = function(evt){
				alert('test');
				var file = evt.files[0];
				var reader = new FileReader();
				reader.onload = function (evt) {
					$scope.$apply(function($scope){
						$scope.myImage = evt.target.result;
						console.log($scope.myCroppedImage);
					});
				};
				reader.readAsDataURL(file);
			}
			
			$scope.ok = function () {
				$uibModalInstance.close($scope.myCroppedImage);
			};

			$scope.cancel = function () {
				$uibModalInstance.dismiss('cancel');
			};

			//angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);
		});
})();