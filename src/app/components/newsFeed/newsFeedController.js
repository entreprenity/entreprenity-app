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
			myHomeService.getBasicUserInfo().success(function(data) {
				vm.currentPost.post_author.firstName 	= data.firstName;
				vm.currentPost.post_author.lastName 	= data.lastName;
				vm.currentPost.post_author.position 	= data.position;
				vm.currentPost.post_author.myOffice 	= data.myOffice;
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
				vm.currentPost.created_at = new Date();
				//vm.posts.unshift(currentPost);
				vm.currentPost.content = ""; //clear textarea
				
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
			

		});			
})();
