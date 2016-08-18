( function () {
	
	var onlyLoggedIn = function($location, $q, AuthService2) 
	{
	  var deferred = $q.defer();
	  AuthService2.isLogin().then(function (res)
	  {
	  		if(res.data.msg == "authorized")
	  		{
	      	//console.log("Logged In");
	         deferred.resolve();	         
	      }
	      else
	      {
	      	//console.log("Not Logged In");
	         deferred.reject();
	         $location.url('/login');
	      }
	  });
	  return deferred.promise;
	};	
	
	var countAllUnreads = function($location, $q, AuthService3) 
	{
		var deferred = $q.defer();
		AuthService3.fetchUnreadNotifications().then(function (res)
      {
      	//console.log(res.data.totalUnread);
      	localStorage.notifications = res.data.totalUnread;
      	//$scope.numOfNotifications =res.data.totalUnread;
      	deferred.resolve(res.data.totalUnread);
      });
      return deferred.promise;
	}
	
	
	angular.module('entreprenityApp', [
		'ngRoute',
		'ngMessages',
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
		'entreprenityApp.callanswering',
		'entreprenityApp.spaces',
		'entreprenityApp.eventPlaced',
		'entreprenityApp.bussOpp',
		'entreprenityApp.resetpassword'
	])
	.factory('AuthService2', ["$http", "$location", function($http, $location){
	   //var vm = this;
	   var baseUrl = 'api/';
      var isLogin = function()
 	   {
    	  var token;
		  if (localStorage['entrp_token'])
		  {
	    	  token = JSON.parse(localStorage['entrp_token']);
		  } 
		  else 
		  {
			  token = "";
		  }	 
        var data = {token: token};	
        
        return	$http.post(baseUrl + 'validateUserToken', {token: token });	      
      }    
	    
		return {isLogin: isLogin} ; 
	}])
	.factory('AuthService3', ["$http", "$location", function($http, $location){
	   //var vm = this;
	   var baseUrl = 'api/';
      var fetchUnreadNotifications = function()
 	   {
        
        return	$http.post(baseUrl + 'getAllUnreadNotifications');	     
        
                
      }    
	    
		return {fetchUnreadNotifications: fetchUnreadNotifications} ; 
	}])
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
		.when('/directory/:directoryType', {
			controller: 'DirectoryController',
			templateUrl: 'app/components/directory/directoryView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/home', {
			controller: 'HomeController',
			templateUrl: 'app/components/home/homeView.html',
			resolve: {loggedIn: onlyLoggedIn},			
			controllerAs: 'vm'			
		})
		.when('/myprofile', {
			controller: 'MyProfileController',
			templateUrl: 'app/components/profile/myProfileView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			//resolve: {loggedIn: onlyLoggedIn,notificationCount:countAllUnreads},		
			controllerAs: 'vm'
		})
		.when('/members/:memberUserName', {
			controller: 'MemberProfileController',
			templateUrl: 'app/components/profile/memberProfileView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/mycompany', {
			controller: 'MyCompanyProfileController',
			templateUrl: 'app/components/profile/myCompanyProfileView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/companies/:companyUserName', {
			controller: 'CompanyProfileController',
			templateUrl: 'app/components/profile/companyProfileView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/events/:eventId', {
			controller: 'EventsPageController',
			templateUrl: 'app/components/events/eventsPageView.html',
			resolve: {loggedIn: onlyLoggedIn},
			controllerAs: 'vm'
		})
		.when('/members/:memberUserName/followers', {
			controller: 'MemberFollowersController',
			templateUrl: 'app/components/followers/memberFollowers.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/members/:memberUserName/following', {
			controller: 'MemberFollowingController',
			templateUrl: 'app/components/followers/memberFollowing.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/companies/:companyUserName/followers', {
			controller: 'CompanyFollowersController',
			templateUrl: 'app/components/followers/companyFollowers.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/logout', {
			controller: 'LogoutController',
			templateUrl: 'app/components/login/loginView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/newsfeed', {
			controller: 'NewsFeedController',
			templateUrl: 'app/components/newsFeed/newsFeed.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/settings', {
			controller: 'SettingsPageController',
			templateUrl: 'app/components/settings/settingsPage.html',
			resolve: {loggedIn: onlyLoggedIn},
			controllerAs: 'vm'
		})
		.when('/notifications', {
			controller: 'NotificationsController',
			templateUrl: 'app/components/notifications/notificationsView.html',
			resolve: {loggedIn: onlyLoggedIn},
			controllerAs: 'vm'
		})
		.when('/posts/:postID', {
			controller: 'NotificationsController',
			templateUrl: 'app/components/notifications/notificationPost.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/business-opportunities', {
			controller: 'BusinessOpportunityController',
			templateUrl: 'app/components/busopp/busoppView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/add-event', {
			controller: 'addEventController',
			templateUrl: 'app/components/events/addEventView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/addEventPoster/:eventTag', {
			controller: 'eventPosterController',
			templateUrl: 'app/components/events/addEventPosterView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/callanswering', {
			controller: 'CallAnswerController',
			templateUrl: 'app/components/externalservices/externalServiceRedirectView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/spaces', {
			controller: 'SpacesController',
			templateUrl: 'app/components/externalservices/externalServiceRedirectView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
      .when('/eventPlaced', {
			controller: 'eventPlacedController',
			templateUrl: 'app/components/events/eventPlaced.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/add-image-to-post', {
			controller: 'imageUploadPostsCtrl',
			templateUrl: 'app/components/modal/imageUploadPostsView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/resetpassword', {
			controller: 'ResetpasswordController',
			templateUrl: 'app/components/resetpassword/resetpasswordView.html',
			resolve: {loggedIn: onlyLoggedIn},	
			controllerAs: 'vm'
		})
		.otherwise({
			redirectTo: '/login'
		});
	}]);

	angular.module('infinite-scroll').value('THROTTLE_MILLISECONDS', 5000);

})();