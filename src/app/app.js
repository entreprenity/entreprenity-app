( function () {
	var onlyLoggedIn = function ($location,$q,AuthService2) 
	{
	    var deferred = $q.defer();
	    
	    if (AuthService2.isLogin()) 
	    {
	        deferred.resolve();
	    } 
	    else 
	    {
	        deferred.reject();
	        $location.url('/login');
	    }
	    return deferred.promise;
	};	
	
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
		'entreprenityApp.callanswering',
		'entreprenityApp.spaces',
		'entreprenityApp.eventPlaced',
		'entreprenityApp.bussOpp',
		//'entreprenityApp.imageUploadPostsCtrl'
	])
	.factory('AuthService2', ["$http", "$location", function($http, $location){
	    //var vm = this;
	    var baseUrl = 'api/';

 		 return {
 		 	
          isLogin : function()
          			  {
						    	  var token;
								  if (localStorage['entrp_token'])
								  {
							    	  token = JSON.parse(localStorage['entrp_token']);
							    	  return true;
								  } 
								  else 
								  {
									  token = "";
									  return false;
								  }	 
						        var data = {token: token};
						        /*
						        $http.post(baseUrl + 'validateUserToken', data).success(function(response)
						        {
						            if (response.msg == "authorized")
						            {
						                //console.log(response.msg);
						                //return {isLogin: response.msg}
						                return localStorage.isLogged === "true";
						                
						            } 
						            else 
						            {
						               return localStorage.isLogged === "false";
						            }
						        });
						        */
                    }
       }	    
	    
	    
	    //var isLogin = function()
	    //{   

	       
	    //}
	    
		 return {isLogin: isLogin} ; 
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
		//.when('/myprofile/:memberUserName', {
		.when('/myprofile', {
			controller: 'MyProfileController',
			templateUrl: 'app/components/profile/myProfileView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		.when('/members/:memberUserName', {
			controller: 'MemberProfileController',
			templateUrl: 'app/components/profile/memberProfileView.html',
			resolve: {loggedIn: onlyLoggedIn},		
			controllerAs: 'vm'
		})
		//.when('/mycompany/:companyUserName', {
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
	
		.otherwise({
			redirectTo: '/login'
		});
	}]);
	
	angular.module('infinite-scroll').value('THROTTLE_MILLISECONDS', 1000);

})();