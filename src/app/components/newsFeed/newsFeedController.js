(function() {
	angular
		.module('entreprenityApp.newsFeed', [])

		.factory('newsFeedService', function($http) {
			var baseUrl = 'api/';
			
			return {
				//getAllPosts,
				getMemberPosts: function(username) { // change to getMembersPost with username as param
					return $http.get(baseUrl+ 'getMyNewsFeed');
				},	
				//getFollowedMembersPosts with username as param,
				postCurrentPost: function(newPost) 
				{
					var dataPost = {newPost: newPost};														
					return $http({ method: 'post',
										url: baseUrl+'postCurrentPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				getBasicUserInfo:function() {
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
		.directive('newsFeed', function() {
			var controller = function($routeParams, newsFeedService) {
				var vm = this;
				var userObject;
				console.log('postsType is ' + vm.poststype);
				console.log('username is ' + vm.username);

				vm.getPosts = function () {
						var postsType = vm.poststype;
						var username = vm.username;

						switch(postsType){
							case '1':
								console.log('run getAllPosts service');
								/*
								newsFeedService.getAllPosts().success(function(data) {
									vm.posts = data;
								});	
								*/
							break
							case '2':
								console.log('run getMemberPosts service');
								newsFeedService.getMemberPosts(username).success(function(data) {
									vm.posts = data;
									console.log(vm.posts);
								});
								/*
								newsFeedService.getMemberPosts(username).success(function(data) {
									vm.posts = data;
								});	
								*/
							break
							case '3':
								console.log('run getFollowedPosts service');
								/*
								newsFeedService.getFollowedPosts(username).success(function(data) {
									vm.posts = data;
								});	
								*/
						};
						
				};
				
				vm.getPosts();
					
				/*
				newsFeedService.getPosts().success(function(data) {
					vm.posts = data;
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

				vm.currentPost = myPost;


				//to get basic user information
				vm.basicInfo = function () {
					newsFeedService.getBasicUserInfo().success(function(data) {
						vm.currentPost.post_author.id 	= data.id;
						vm.currentPost.post_author.firstName 	= data.firstName;
						vm.currentPost.post_author.lastName 	= data.lastName;
						vm.currentPost.post_author.position 	= data.position;
						vm.currentPost.post_author.companyName 	= data.companyName;
						vm.currentPost.post_author.avatar 		= data.avatar;
						vm.currentPost.post_author.userName 	= data.userName;
						vm.currentPost.post_author.companyUserName 	= data.companyUserName;
						userObject = data;
					});	
				};
				vm.basicInfo();


				// Add a time-line post
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

				//Like a time-line post
				vm.likePost = function(post) {
					var likedPost = post;

					//this will come from the session userobject
					vm.basicInfo();
					likedPost.likers.push(userObject);
					newsFeedService.postLike(likedPost).success(function(data) {
						likedPost.isLiked = true;
						likedPost.likes_count++;
						vm.posts = data;
					});	
					vm.getPosts();
				};

				//unlike a time-line post
				vm.unLikePost = function(post) {
					var unLikedPost = post;

					//this will come from the session userobject
					vm.basicInfo();
					newsFeedService.postUnLike(unLikedPost).success(function(data) {
						unLikedPost.isLiked = false;
						unLikedPost.likes_count--;
						unLikedPost.likers.pop();
						vm.posts = data;
					});
					vm.getPosts();	
				};

				//Add a comment to time-line post
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

					newsFeedService.postComment(commentedPost,newComment).success(function(data) {
						vm.posts = data;
					});	
					vm.getPosts();
				};
			};
		
			var template = '<button>{{vm.poststype}}</button>';

			return {
				restrict: 'E',
				scope: {
					poststype: '@',
					username: '='
				},
				controller: controller,
				controllerAs: 'vm',
				bindToController: true, //required in 1.3+ with controllerAs
				templateUrl: 'app/components/newsFeed/newsFeed.html'
				//template: template
			};
		});		
})();
