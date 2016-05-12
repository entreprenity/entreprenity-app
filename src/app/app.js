( function () {
	
	angular.module('entreprenityApp', [
		'ngRoute',
		'ngAnimate',
		'ngTouch',
		'ui.bootstrap',
		'infinite-scroll',
		'uiGmapgoogle-maps',
		'ngTagsInput',
		'ngImgCrop',
		'entreprenityApp.login',
		'entreprenityApp.home',
		'entreprenityApp.directory',
		'entreprenityApp.forgotpassword',
		'entreprenityApp.myProfile',
		'entreprenityApp.memberProfile',
		'entreprenityApp.myCompanyProfile',
		'entreprenityApp.companyProfile',
		'entreprenityApp.eventsPage',
		'entreprenityApp.imageUpload'
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
		.when('/myprofile/:memberUserName', {
			controller: 'MyProfileController',
			templateUrl: 'app/components/profile/myProfileView.html',
			controllerAs: 'vm'
		})
		.when('/members/:memberUserName', {
			controller: 'MemberProfileController',
			templateUrl: 'app/components/profile/memberProfileView.html',
			controllerAs: 'vm'
		})
		.when('/mycompany/:companyUserName', {
			controller: 'MyCompanyProfileController',
			templateUrl: 'app/components/profile/myCompanyProfileView.html',
			controllerAs: 'vm'
		})
		.when('/companies/:companyUserName', {
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
	/*
	.run(function ($rootScope, $location, Data) 
	{
	     $rootScope.$on("$routeChangeStart", function (event, next, current) 
	     {
	         $rootScope.authenticated = false;
	         Data.get('session').then(function (results) 
	         {
	             if (results.id) 
	             {
	                 $rootScope.authenticated = true;
	                 $rootScope.uid 				= results.id;
	                 $rootScope.firstname 		= results.firstname;
	                 $rootScope.lastname 		= results.lastname;
	                 $rootScope.login_token 	= results.login_token;
	             } 
	             else 
	             {
	                 var nextUrl = next.$$route.originalPath;
	                 if (nextUrl == '/login' || nextUrl == '/register') 
	                 {
	 
	                 } 
	                 else 
	                 {
	                     $location.path("/login");
	                 }
	             }
	         });
	      });
    });
	*/
	angular.module('infinite-scroll').value('THROTTLE_MILLISECONDS', 1000);

})();