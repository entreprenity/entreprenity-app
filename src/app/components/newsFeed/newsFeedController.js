(function() {
	angular
		.module('entreprenityApp.newsFeed', [])

		.factory('newsFeedService', function($http) {
			var baseUrl = 'api/';
			
			return {
				getPosts: function() {
					return $http.get(baseUrl+ 'getMyNewsFeed');
				},			
				postCurrentPost: function(newPost) 
				{
					var dataPost = {newPost: newPost};														
					return $http({ method: 'post',
										url: baseUrl+'postCurrentPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				}
				
			};
		})

		.controller('NewsFeedController', function($routeParams,newsFeedService) {
			var vm = this;

			/*
			//to get basic user information
			newsFeedService.getBasicUserInfo().success(function(data) {
				vm.currentPost.post_author.id 	= data.id;
				vm.currentPost.post_author.firstName 	= data.firstName;
				vm.currentPost.post_author.lastName 	= data.lastName;
				vm.currentPost.post_author.position 	= data.position;
				vm.currentPost.post_author.companyName 	= data.companyName;
				vm.currentPost.post_author.avatar 		= data.avatar;
				vm.currentPost.post_author.userName 	= data.userName;
				vm.currentPost.post_author.companyUserName 	= data.companyUserName;
			});
			*/
			
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
			
		
			//vm.currentPost = myPost;
			vm.currentPost = myPost;
		
			//console.log(vm.currentPost);
			
			// Add TODO
			vm.addPost = function (newPost) {
				var currentPost = vm.currentPost;
				currentPost.created_at = new Date();
				//vm.posts.unshift(currentPost);
				currentPost.content = ""; //clear post textarea
				
				newsFeedService.postCurrentPost(newPost).success(function(data) {
					vm.posts = data;
				});	
				
				vm.getPosts();
			};
			
			vm.getPosts = function () {
					newsFeedService.getPosts().success(function(data) {
						vm.posts = data;
					});	
			};

			vm.likePost = function(post) {
				alert('LIKE POST');
				var likedPost = post;
				likedPost.isLiked = true;
				likedPost.likes_count++;
				//this will come from the session userobject
				var userObject = {
					"id": "2",
					"firstName": "Will",
					"lastName": "Ferrel",
					"avatar": "member-default.jpg",
					"position": "CEO",
					"companyName": "Clever Sheep",
					"userName": "will"
				};
				likedPost.likers.push(userObject);
				/*
				newsFeedService.postLike(likedPost).success(function(data) {
					alert(Post Like Success);
				});	
				*/
			};
		
			vm.unLikePost = function(post) {
				alert('UNLIKE POST');
				var unLikedPost = post;
				unLikedPost.isLiked = false;
				unLikedPost.likes_count--;
				unLikedPost.likers.pop();
				/*
				newsFeedService.postLike(unLikedPost).success(function(data) {
					alert(Post Unlike Success);
					unLikedPost.likes_count--;
					unLikedPost.likers.pop();
				});	
				*/
			};
		
			vm.addComment = function(post) {
				alert('ADD COMMENT');
				var commentedPost = post;
				//this will come from the session userobject
				var userObject = {
					"id": "2",
					"firstName": "Will",
					"lastName": "Ferrel",
					"avatar": "member-default.jpg",
					"position": "CEO",
					"companyName": "Clever Sheep",
					"userName": "will"
				};
				
				var currentComment = {};
				currentComment.content = vm.currentComment.content;
				currentComment.created_at = new Date();
				currentComment.comment_author = userObject;
				
				commentedPost.comments_count++;
				commentedPost.comments.push(currentComment);
				vm.currentComment.content = ""; //clear comment textarea
				/*
				newsFeedService.postComment(commentedPost).success(function(data) {
					alert(Post Commnent Success);
				});	
				*/
			};
			
			//dummy data
			var posts = [
				{
					"post_id": "123456",
					"content": "Hi, we recently noticed an increased sign up for our eVoiceMail.net service particularly from users from US. Anyone know why and is interested to help us to market our service to even more peeps?",
					"image": "jpg01.jpg",
					"created_at": "2015-05-12T14:54:31.566Z",
					"post_author": {
						"id": "1",
						"firstName": "Jordan",
						"lastName": "Rains",
						"avatar": "member-default.jpg",
						"position": "Office Assistant",
						"companyName": "Pet Studio.com",
						"userName": "jordan"
					},
					"isLiked": false,
					"likes_count": 2,
					"likers": [
						{
							"id": "3",
							"firstName": "John",
							"lastName": "Smith",
							"avatar": "member-default.jpg",
							"position": "Creative Director",
							"companyName": "Wendy Skelton",
							"userName": "John"
						},
						{
							"id": "1",
							"firstName": "Jordan",
							"lastName": "Rains",
							"avatar": "member-default.jpg",
							"position": "Office Assistant",
							"companyName": "Pet Studio.com",
							"userName": "jordan"
						}
					],
					"comments_count": 1,
					"commenters": [
						{
							"id": "3",
							"firstName": "John",
							"lastName": "Smith",
							"avatar": "member-default.jpg",
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
								"avatar": "member-default.jpg",
								"position": "Creative Director",
								"companyName": "Wendy Skelton",
								"userName": "John"
							}
						}
					]
				},
				{
					"post_id": "123456",
					"content": "Hi, we recently noticed an increased sign up for our eVoiceMail.net service particularly from users from US. Anyone know why and is interested to help us to market our service to even more peeps?",
					"image": "jpg01.jpg",
					"created_at": "2015-05-12T14:54:31.566Z",
					"post_author": {
						"id": "1",
						"firstName": "Jordan",
						"lastName": "Rains",
						"avatar": "member-default.jpg",
						"position": "Office Assistant",
						"companyName": "Pet Studio.com",
						"userName": "jordan"
					},
					"likes_count": 1,
					"likers": [
						{
							"id": "3",
							"firstName": "John",
							"lastName": "Smith",
							"avatar": "member-default.jpg",
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
							"avatar": "member-default.jpg",
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
								"avatar": "member-default.jpg",
								"position": "Creative Director",
								"companyName": "Wendy Skelton",
								"userName": "John"
							}
						},
						{
							"content": "congrats Albert!",
							"created_at": "2015-05-12T15:06:51.457Z",
							"likes_count": 0,
							"likers": [],
							"comment_author": {
								"id": "3",
								"firstName": "John",
								"lastName": "Smith",
								"avatar": "member-default.jpg",
								"position": "Creative Director",
								"companyName": "Wendy Skelton",
								"userName": "John"
							}
						},
					]
				}
			];
		
			vm.posts = posts;


		});			
})();
