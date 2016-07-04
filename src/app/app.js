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
		'angularMoment',
		'vr.directives.slider',
		'rgkevin.datetimeRangePicker',
		'entreprenityApp.login',
		'entreprenityApp.home',
		'entreprenityApp.directory',
		'entreprenityApp.forgotpassword',
		'entreprenityApp.myProfile',
		'entreprenityApp.memberProfile',
		'entreprenityApp.myCompanyProfile',
		'entreprenityApp.companyProfile',
		'entreprenityApp.eventsPage',
		'entreprenityApp.imageUpload',
		'entreprenityApp.memberFollowers',
		'entreprenityApp.memberFollowing',
		'entreprenityApp.companyFollowers',
		'entreprenityApp.newsFeed',
		'entreprenityApp.AuthenticationService',
		'entreprenityApp.logout',
		'entreprenityApp.settings',
		'entreprenityApp.notifications',
		'entreprenityApp.addEvent',
		'entreprenityApp.common',
		'entreprenityApp.imgEventPoster',
		'entreprenityApp.callanswering'
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
		.when('/members/:memberUserName/followers', {
			controller: 'MemberFollowersController',
			templateUrl: 'app/components/followers/memberFollowers.html',
			controllerAs: 'vm'
		})
		.when('/members/:memberUserName/following', {
			controller: 'MemberFollowingController',
			templateUrl: 'app/components/followers/memberFollowing.html',
			controllerAs: 'vm'
		})
		.when('/companies/:companyUserName/followers', {
			controller: 'CompanyFollowersController',
			templateUrl: 'app/components/followers/companyFollowers.html',
			controllerAs: 'vm'
		})
		.when('/logout', {
			controller: 'LogoutController',
			templateUrl: 'app/components/login/loginView.html',
			controllerAs: 'vm'
		})
		.when('/newsfeed', {
			controller: 'NewsFeedController',
			templateUrl: 'app/components/newsFeed/newsFeed.html',
			controllerAs: 'vm'
		})
		.when('/settings', {
			controller: 'SettingsPageController',
			templateUrl: 'app/components/settings/settingsPage.html',
			controllerAs: 'vm'
		})
		.when('/notifications', {
			controller: 'NotificationsController',
			templateUrl: 'app/components/notifications/notificationsView.html',
			controllerAs: 'vm'
		})
		.when('/posts/:postID', {
			controller: 'NotificationsController',
			templateUrl: 'app/components/notifications/notificationPost.html',
			controllerAs: 'vm'
		})
		.when('/business-opportunities', {
			controller: 'NotificationsController',
			templateUrl: 'app/components/busopp/busoppView.html',
			controllerAs: 'vm'
		})
		.when('/add-event', {
			controller: 'addEventController',
			templateUrl: 'app/components/events/addEventView.html',
			controllerAs: 'vm'
		})
		.when('/addEventPoster/:eventTag', {
			controller: 'eventPosterController',
			templateUrl: 'app/components/events/addEventPosterView.html',
			controllerAs: 'vm'
		})
		.when('/callanswering', {
			controller: 'CallAnswerController',
			templateUrl: 'app/components/externalservices/externalServiceRedirectView.html',
			controllerAs: 'vm'
		})
		.otherwise({
			redirectTo: '/login'
		});
	}]);
	
	angular.module('infinite-scroll').value('THROTTLE_MILLISECONDS', 1000);

})();