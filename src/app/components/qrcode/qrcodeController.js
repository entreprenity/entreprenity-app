(function() {

	angular
		.module('entreprenityApp.qrcode', [])
		.factory('qrCodeService', function($http) {
			var baseUrl = 'api/';

			return {	
				getMyQRCode: function() {
					return $http.get(baseUrl+ 'getMyQRCode');
				}
			};
		})
		.controller('qrcodeController', function($scope, $http, $location,qrCodeService) {
			
		 	var vm = this;		
		   //To get user session value
			qrCodeService.getMyQRCode().success(function(data) {
				vm.qrCode 			= data;
			 });
		});
	
})();