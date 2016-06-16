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
				},
				getBasicUserInfo:function() 
				{
					return $http.get(baseUrl + 'getBasicUserInformation');
				},
				postComment: function(commentedPost,newComment) 
				{
					var dataPost = {postId: commentedPost.post_id,postComment:newComment};														
					return $http({ method: 'post',
										url: baseUrl+'postThisComment',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},				
				postLike: function(likedPost) 
				{
					var dataPost = {likedPostId: likedPost.post_id};														
					return $http({ method: 'post',
										url: baseUrl+'likeThisPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				postUnLike: function(unLikedPost) 
				{
					var dataPost = {unlikedPostId: unLikedPost.post_id};														
					return $http({ method: 'post',
										url: baseUrl+'unlikeThisPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				}
			};
		})

		.controller('NotificationsController', function($routeParams, notificationsService) {
				var vm = this;
				
				var cPost = {
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

				vm.currentPost = cPost;

				vm.npostID = $routeParams.postID;			
				
				//To get user session value
				notificationsService.getUserSessInfo().success(function(data) {
					vm.id 			= data.id;
					vm.userName 	= data.username;
					
					notificationsService.getNotifications(vm.userName).success(function(data) {
						vm.notifications = data;
					});	
				});
				
				//to get basic user information
				vm.basicInfo = function () {
					notificationsService.getBasicUserInfo().success(function(data) {
						vm.currentPost.post_author.id 					= data.id;
						vm.currentPost.post_author.firstName 			= data.firstName;
						vm.currentPost.post_author.lastName 			= data.lastName;
						vm.currentPost.post_author.position 			= data.position;
						vm.currentPost.post_author.companyName 		= data.companyName;
						vm.currentPost.post_author.avatar 				= data.avatar;
						vm.currentPost.post_author.userName 			= data.userName;
						vm.currentPost.post_author.companyUserName 	= data.companyUserName;
						userObject = data;
				
					});	
				};
				vm.basicInfo();
	
				//fetch this timeline post
				vm.thisPost = function () {
						notificationsService.post(vm.npostID).success(function(data) {
							vm.npost = data;
						});
				};
				
				vm.thisPost();
				
				
				//Like this time-line post
				vm.likePost = function(post) {
						vm.basicInfo();
						var likedPost = post;
						//console.log(likedPost);
						//likedPost.likers = likedPost.likers || [];
						likedPost.likers.push(userObject);
						notificationsService.postLike(likedPost).success(function(data) {
							likedPost.isLiked = true;
							likedPost.likes_count++;
							vm.npost = data;
						});	
						vm.thisPost();				
				};

				//unlike this time-line post
				vm.unLikePost = function(post) {
					var unLikedPost = post;
						vm.basicInfo();
						notificationsService.postUnLike(unLikedPost).success(function(data) {
							unLikedPost.isLiked = false;
							unLikedPost.likes_count--;
							unLikedPost.likers.pop();
							vm.npost = data;
						});
						vm.thisPost();	
				};

				//Add a comment to this time-line post
				vm.addComment = function(post,newComment) {
					var commentedPost = post;
					//this will come from the session userobject
					vm.basicInfo();
					var currentComment = {};
					currentComment.content = vm.currentComment.content;
					currentComment.created_at = new Date();
					currentComment.comment_author = userObject;

					commentedPost.comments_count++;
					commentedPost.comments.push(currentComment);
					vm.currentComment.content = ""; //clear comment textarea

					notificationsService.postComment(commentedPost,newComment).success(function(data) {
						vm.posts = data;
					});	
					vm.thisPost();
				};
	
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
				
				
				
			
			
		});
})();
