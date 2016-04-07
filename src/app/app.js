( function () {
	
	angular.module('entreprenityApp', [
		'ngRoute',
		'ngAnimate',
		'ngTouch',
		'ui.bootstrap',
		'entreprenityApp.directory'
	])
	
	.config(['$routeProvider', function($routeProvider) {
		$routeProvider.
		when('/directory', {
			templateUrl: 'app/components/directory/directoryView.html',
		}).
		otherwise({
			redirectTo: '/directory'
		});
	}]);

})();