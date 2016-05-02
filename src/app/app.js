( function () {
	
	angular.module('entreprenityApp', [
		'ngRoute',
		'ngAnimate',
		'ngTouch',
		'ui.bootstrap',
		'infinite-scroll',
		'uiGmapgoogle-maps',
		'ngTagsInput',
		'entreprenityApp.login',
		'entreprenityApp.home',
		'entreprenityApp.directory',
		'entreprenityApp.forgotpassword',
		'entreprenityApp.myProfile',
		'entreprenityApp.memberProfile',
		'entreprenityApp.companyProfile',
		'entreprenityApp.eventsPage'
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
		.when('/home', {
			controller: 'HomeController',
			templateUrl: 'app/components/home/homeView.html',
			controllerAs: 'vm'
		})
		.when('/myprofile/:memberId', {
			controller: 'MyProfileController',
			templateUrl: 'app/components/profile/myProfileView.html',
			controllerAs: 'vm'
		})
		.when('/members/:memberId', {
			controller: 'MemberProfileController',
			templateUrl: 'app/components/profile/memberProfileView.html',
			controllerAs: 'vm'
		})
		.when('/companies/:companyId', {
			controller: 'CompanyProfileController',
			templateUrl: 'app/components/profile/companyProfileView.html',
			controllerAs: 'vm'
		})
		.when('/events/:eventId', {
			controller: 'EventsPageController',
			templateUrl: 'app/components/events/eventsPageView.html',
			controllerAs: 'vm'
		})
		.otherwise({
			redirectTo: '/login'
		});
	}]);
	
	angular.module('infinite-scroll').value('THROTTLE_MILLISECONDS', 1000);

})();