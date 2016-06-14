(function() {

	angular
		.module('entreprenityApp.notifications', [])

		.factory('notificationsService', function($http) {
			var baseUrl = 'api/';

			return {	
				getUserSessInfo: function() {
					return $http.get(baseUrl+ 'get_user_session');
				},
				getNotifications: function(userName) 
				{
					return $http.get(baseUrl+ 'getMyNotifications?user='+userName);
				},
				post: function(postId)
				{
					return $http.get(baseUrl+ 'getThisPost?post='+postId);				
				}
			};
		})

		.controller('NotificationsController', function($routeParams, notificationsService) {
			var vm = this;
			
			vm.npostID = $routeParams.postID;			
			
			//To get user session value
			notificationsService.getUserSessInfo().success(function(data) {
				vm.id 			= data.id;
				vm.userName 	= data.username;
				
				notificationsService.getNotifications(vm.userName).success(function(data) {
					vm.notifications = data;
				});	
			});
			
			var myPost = {
					"post_id": "",
					"content": "",
					"image": "",
					"created_at": "",
					"post_author": {
						"id": "2",
						"firstName": "Will",
						"lastName": "Ferrel",
						"avatar": "member-default.jpg",
						"position": "CEO",
						"companyName": "Clever Sheep",
						"userName": "will"
					},
					"isLiked": false,
					"likes_count": 0,
					"likers": [],
					"comments_count": 0,
					"commenters": [],
					"comments": []
				};

				vm.currentPost = myPost;

				/*
				var nPost = {
					"post_id": "12",
					"content": "Content is the content that contains the content",
					"image": "member-default.jpg",
					"created_at": "2016-05-26 14:29:00",
					"post_author": {
						"id": "2",
						"firstName": "Will",
						"lastName": "Ferrel",
						"avatar": "assets/img/members/member-default.jpg",
						"position": "CEO",
						"companyName": "Clever Sheep",
						"userName": "will"
					},
					"isLiked": false,
					"likes_count": 1,
					"likers": [
						{
							"id": "3",
							"firstName": "John",
							"lastName": "Smith",
							"avatar": "assets/img/members/member-default.jpg",
							"position": "Creative Director",
							"companyName": "Wendy Skelton",
							"userName": "John"
						}					
					],
					"comments_count": 1,
					"commenters": [
						{
							"id": "3",
							"firstName": "John",
							"lastName": "Smith",
							"avatar": "assets/img/members/member-default.jpg",
							"position": "Creative Director",
							"companyName": "Wendy Skelton",
							"userName": "John"
						}					
					],
					"comments": [
						{
							"content": "congrats Albert!",
							"created_at": "2015-05-12T15:06:51.457Z",
							"likes_count": 0,
							"likers": [],
							"comment_author": {
								"id": "3",
								"firstName": "John",
								"lastName": "Smith",
								"avatar": "ssets/img/members/member-default.jpg",
								"position": "Creative Director",
								"companyName": "Wendy Skelton",
								"userName": "John"
							}
						}					
					]
				};
				vm.npost = nPost;
				*/
				
				notificationsService.post(vm.npostID).success(function(data) {
					vm.npost = data;
				});
				
			
			
		});
})();
