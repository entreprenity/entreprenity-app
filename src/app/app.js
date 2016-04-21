( function () {
	
	angular.module('entreprenityApp', [
		'ngRoute',
		'ngAnimate',
		'ngTouch',
		'ui.bootstrap',
		'infinite-scroll',
		'entreprenityApp.login',
		'entreprenityApp.directory',
		'entreprenityApp.forgotpassword'
	])
	
	.config(['$routeProvider', function($routeProvider) {
		$routeProvider
		.when('/login', {
			controller: 'LoginController',
			templateUrl: 'app/components/login/loginView.html',
			controllerAs: 'vm'
		})
		.when('/register', {
			controller: 'RegisterController',
			templateUrl: 'app/components/register/registerView.html',
			controllerAs: 'vm'
		})
		.when('/forgotpassword', {
			controller: 'ForgotpasswordController',
			templateUrl: 'app/components/forgotpassword/forgotpasswordView.html',
			controllerAs: 'vm'
		})
		.when('/directory', {
			controller: 'DirectoryController',
			templateUrl: 'app/components/directory/directoryView.html',
			controllerAs: 'vm'
		})
		.otherwise({
			redirectTo: '/login'
		});
	}]);
	
	angular.module('infinite-scroll').value('THROTTLE_MILLISECONDS', 1000);

})();