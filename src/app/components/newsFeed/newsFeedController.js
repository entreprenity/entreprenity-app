(function() {
	angular
		.module('entreprenityApp.newsFeed', [])

		.factory('newsFeedService', function($http) {
			var baseUrl = 'api/';
			
			return {
				
				getAllPosts: function() 
				{ 
					return $http.get(baseUrl+ 'getAllPosts');
				},
				getMemberPosts: function(username) 
				{
					return $http.get(baseUrl+ 'getMembersPost?user='+username);
				},	
				getFollowedMembersPosts: function(username) 
				{
					return $http.get(baseUrl+ 'getFollowedMembersPosts?user='+username);
				},
				getBasicUserInfo:function()
				{
					return $http.get(baseUrl + 'getBasicUserInformation');
				},
				getCompanyPosts: function(username) 
				{
					return $http.get(baseUrl+ 'getCompanyPosts?company='+username);
				},
				getTagCategories:function() 
				{
					return $http.get(baseUrl + 'getTagCategories');
				},
				getAllBusinessOpportunities:function() 
				{
					return $http.get(baseUrl + 'getAllBusinessOpportunities');
				},
				
				postCurrentPost: function(newPost) 
				{
					var dataPost = {newPost: newPost};														
					return $http({ method: 'post',
										url: baseUrl+'postCurrentPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
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
				},
				postBusoppPost: function(content) 
				{
					var dataPost = {postContent: content};														
					return $http({ method: 'post',
										url: baseUrl+'postABusinessOpportunity',
										data: $.param(dataPost),
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				commentLike: function(commentID) 
				{
					var dataPost = {likedCommentId: commentID};														
					return $http({ method: 'post',
										url: baseUrl+'likeThisComment',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				commentUnLike: function(commentID) 
				{
					var dataPost = {unlikedCommentId: commentID};														
					return $http({ method: 'post',
										url: baseUrl+'unlikeThisComment',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				}
			};
		})
		.directive('newsFeed', function() {
			var controller = function($routeParams, newsFeedService, $scope) {
				var vm = this;
				var userObject;

				vm.busoppPost = false; // initial state is false, set to true on checkbox

				$scope.loadTags = function(query) { //load tag Categories
					 var categories = [];
					 newsFeedService.getTagCategories().success(function(data) {
					 	vm.categories = data;						
						/*
					 	vm.categories = [
						"Programming",
						"Design",
						"Development",
						"Community",
						"Petshop",
						"Sales",
						"Coworking",
						"Serviced Office",
						"Bakery",
						"Virtual Office"
						];
						*/
											 	
					 });
					 return vm.categories;

				};
				
				vm.getPosts = function () {
						var postsType = vm.poststype;
						var username = vm.username;

						switch(postsType){
							case '1':
								newsFeedService.getAllPosts().success(function(data) {
									vm.posts = data;
								});
							break;
							case '2':
								newsFeedService.getMemberPosts(username).success(function(data) {
									vm.posts = data;
								});
							break;
							case '3':
								newsFeedService.getFollowedMembersPosts(username).success(function(data) {
									vm.posts = data;
								});
							break;
							case '4':
								newsFeedService.getCompanyPosts(username).success(function(data) {
									vm.posts = data;
								});
							break;
							case '5':
								newsFeedService.getAllBusinessOpportunities().success(function(data) { //change to own service newsFeedService.getBusOppPosts().
									vm.posts = data;
								});
						}
				};
				
				 /*
			    vm.getPosts = function() 
			    {
				     var postsType = vm.poststype;
				     var username = vm.username;
				     console.log('postsType is ' + vm.poststype);
						console.log('username is ' + vm.username);
				
				     if (typeof username != 'undefined') 
				     {
					      switch (postsType) {
					       case '1':
					        			newsFeedService.getAllPosts().success(function(data) {
														vm.posts = data;
										});
					       break
					       case '2':
					        			newsFeedService.getMemberPosts(username).success(function(data) {
														vm.posts = data;
														console.log(vm.posts);
										});
					       break
					       case '3':
					        			newsFeedService.getFollowedMembersPosts(username).success(function(data) {
														vm.posts = data;
										});
					      };
				     } 
				     else 
				     {
				      window.setTimeout(vm.getPosts, 100); 
				     }
			    };
			    */
				
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
						"id": "",
						"firstName": "",
						"lastName": "",
						"avatar": "",
						"position": "",
						"companyName": "",
						"userName": ""
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
				newsFeedService.post(vm.npostID).success(function(data) {
					vm.npost = data;
				});
				*/

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
					if(newPost)
					{
						newsFeedService.postCurrentPost(newPost).success(function(data) {
							vm.posts = data;							
						});
						vm.getPosts();
					}	
				};

				// Add a time-line Business Opportunity post
				vm.addBusoppPost = function (content) {
					if(content.content && content.categories)
					{
						newsFeedService.postBusoppPost(content).success(function(data) {
							vm.posts = data;
						});
						vm.getPosts(); 
					}								
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
				
				//Like a time-line comment
				vm.likeComment = function(commentID) {
					var likedComment = commentID;

					//this will come from the session userobject
					vm.basicInfo();
					likedComment.likers.push(userObject);
					newsFeedService.commentLike(likedComment).success(function(data) {
						likedComment.isLiked = true;
						likedComment.likes_count++;
						vm.posts = data;						
					});
					vm.getPosts(); 	
				};
				
				//unlike a time-line comment
				vm.unLikeComment = function(commentID) {
					var unLikedComment = post;

					//this will come from the session userobject
					vm.basicInfo();
					newsFeedService.commentUnLike(commentID).success(function(data) {
						unLikedComment.isLiked = false;
						unLikedComment.likes_count--;
						unLikedComment.likers.pop();
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
