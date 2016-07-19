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
                getmyMemberPosts: function(username) 
				{
					return $http.get(baseUrl+ 'getmyTimeLinePost');
				},
				getFollowedMembersPosts: function(username) 
				{
					return $http.get(baseUrl+ 'getFollowedMembersPosts');
				},
				getBasicUserInfo:function()
				{
					return $http.get(baseUrl + 'getBasicUserInformation');
				},
				getCompanyPosts: function(username) 
				{
					return $http.get(baseUrl+ 'getCompanyPosts?company='+username);
				},
            getMyCompanyPosts: function(username) 
				{
					return $http.get(baseUrl+ 'getmyCompanyPosts');
				},
				getTagCategories:function() 
				{
					return $http.get(baseUrl + 'getTagCategories');
				},
				getAllBusinessOpportunities:function() 
				{
					return $http.get(baseUrl + 'getAllBusinessOpportunities');
				},
				getBusinessOpportunitiesForMe:function() 
				{
					return $http.get(baseUrl + 'getBusinessOpportunitiesForMe');
				},
				postCurrentPost: function(newPost,imgString,timelineId,ucUsername) 
				{
					var dataPost = {newPost: newPost,imgString: imgString,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'postCurrentPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
                postBusoppPost: function(content,imgString,timelineId,ucUsername) 
				{
					var dataPost = {postContent: content,imgString: imgString,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'postABusinessOpportunity',
										data: $.param(dataPost),
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				postComment: function(commentedPost,newComment,timelineId,ucUsername) 
				{
					var dataPost = {postId: commentedPost.post_id,postComment:newComment,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'postThisComment',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},				
				postLike: function(likedPost,timelineId,ucUsername) 
				{
					var dataPost = {likedPostId: likedPost.post_id,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'likeThisPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				postUnLike: function(unLikedPost,timelineId,ucUsername) 
				{
					var dataPost = {unlikedPostId: unLikedPost.post_id,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'unlikeThisPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				}
				
			};
		})
        .factory('focus', function($timeout, $window) {
            return function(id) {
            // timeout makes sure that is invoked after any other event has been triggered.
            // e.g. click events that need to run before the focus or
            // inputs elements that are in a disabled state but are enabled when those events
            // are triggered.
            $timeout(function() {
                var element = $window.document.getElementById(id);
                if(element)
                element.focus();
                });
            };
        })
        .directive('eventFocus', function(focus) {
            return function(scope, elem, attr) {
                elem.on(attr.eventFocus, function() {
                    focus(attr.eventFocusId);
                });
      
            // Removes bound events in the element itself
            // when the scope is destroyed
            scope.$on('$destroy', function() {
                element.off(attr.eventFocus);
            });
            };
        })
		.directive('newsFeed', function() {
			var controller = function($routeParams, newsFeedService, $scope, focus, $uibModal) {
				var vm = this;
				var userObject;
				//console.log(vm);

				vm.busoppPost = false; // initial state is false, set to true if a business opportunity post
				vm.isAnImagePost = false; // initial state is false, set to true if image upload is clicked
				vm.editState = false; // initial state is false, set to true if edit post is clicked

				$scope.loadTags = function(query) { //load tag Categories
					 var categories = [];
					 newsFeedService.getTagCategories().success(function(data) {
					 	vm.categories = data;						
											 	
					 });
					 return vm.categories;

				};

				vm.keypressEventListener = function($event, post){
					var keyCode = $event.which || $event.keyCode;
					if (keyCode === 13) {
						alert('Enter pressed');
						console.log(post);
						vm.editPost(post);

					}

				};
				
				vm.getPosts = function () {
					var postsType = vm.poststype;
					var username = vm.username;

					switch(postsType)
					{
						//home page all posts
						case '1':
							newsFeedService.getAllPosts().success(function(data) {
								vm.posts = data;
							});
						break;

						//member profile timeline posts
						case '2':
							newsFeedService.getMemberPosts(username).success(function(data) {
								vm.posts = data;
							});
						break;

						//home page followed posts
						case '3':
							newsFeedService.getFollowedMembersPosts(username).success(function(data) {
								vm.posts = data;
							});
						break;

						//company profile timeline posts
						case '4':
							newsFeedService.getCompanyPosts(username).success(function(data) {
								vm.posts = data;
							});
						break;

						//business all opportunities page
						case '5':
							newsFeedService.getAllBusinessOpportunities().success(function(data) {
								vm.posts = data;
								vm.busoppPost = true;
								//console.log(vm.busoppPost);
								//console.log('busopp', vm.posts);
							});
						break;
						
						//my company profile timeline posts
						case '6':
							newsFeedService.getMyCompanyPosts(username).success(function(data) {
								vm.posts = data;
							});
						break;
						
						//home page my posts timeline
						case '7':
							newsFeedService.getmyMemberPosts(username).success(function(data) {
								vm.posts = data;
							});
						break;
						
						//matched business opportunities
						case '8':
							newsFeedService.getBusinessOpportunitiesForMe().success(function(data) {
								vm.posts = data;
								vm.busoppPost = true;
								//console.log(vm.busoppPost);
								//console.log('busopp', vm.posts);
							});
						break;
					}

				};
				
				
				vm.getPosts();

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
				vm.addPost = function (newPost,newImg) {
					var currentPost = vm.currentPost;
					currentPost.created_at = new Date();
					//vm.posts.unshift(currentPost);
					currentPost.content = ""; //clear post textarea
					currentPost.image = ""; //clear post image
					if(newImg)
					{
					 	var base64ImgString= newImg;
					}
					else
					{
						var base64ImgString= '';
					}
					
					if(newPost)
					{
						newsFeedService.postCurrentPost(newPost,base64ImgString,vm.poststype,vm.username).success(function(data) {
							vm.posts = data;
                            vm.isAnImagePost = false; //hide image div
						});
					}	
				};

				// Add a time-line Business Opportunity post
				vm.addBusoppPost = function (content,newImg) {
                    var currentPost = vm.currentPost;
					if(content.content && content.categories)
					{
                        if(newImg)
                        {
                            var base64ImgString= newImg;
                        }
                        else
                        {
                            var base64ImgString= '';
                        }
						newsFeedService.postBusoppPost(content,base64ImgString,vm.poststype,vm.username).success(function(data) {
							vm.posts = data;
                            currentPost.image = ""; //clear post image
                            currentPost.content = ""; //clear post textarea
                            currentPost.categories = ""; //clear tags
                            vm.isAnImagePost = false; //hide image div
						});
					}								
				};

				//Like a time-line post
				vm.likePost = function(post) {
					var likedPost = post;
					//this will come from the session userobject
					vm.basicInfo();
					likedPost.likers.push(userObject);
					newsFeedService.postLike(likedPost,vm.poststype,vm.username).success(function(data) {
						likedPost.isLiked = true;
						likedPost.likes_count++;
						vm.posts = data;						
					});	
				};

				//unlike a time-line post
				vm.unLikePost = function(post) {
					var unLikedPost = post;
					//this will come from the session userobject
					vm.basicInfo();
					newsFeedService.postUnLike(unLikedPost,vm.poststype,vm.username).success(function(data) {
						unLikedPost.isLiked = false;
						unLikedPost.likes_count--;
						unLikedPost.likers.pop();
						vm.posts = data;							
					});	
				};
				

				//Add a comment to time-line post
				vm.addComment = function(post,newComment) {
					var commentedPost = post;
					//this will come from the session userobject
					vm.basicInfo();
					var currentComment = {};
					//currentComment.content = vm.currentComment.content;
					currentComment.content = newComment;
					currentComment.created_at = new Date();
					currentComment.comment_author = userObject;

					commentedPost.comments_count++;
					commentedPost.comments.push(currentComment);
					post.comment.content = ""; //clear comment textarea
					//vm.currentComment.content = ""; //clear comment textarea

					newsFeedService.postComment(commentedPost,newComment,vm.poststype,vm.username).success(function(data) {
						vm.posts = data;						
					});	
				};
                
				 //This is to set focus on the comment box upon clicking the comment link
				 vm.focusCommentBox = function(newCommentBox) {
					 // do something awesome
					 focus(newCommentBox);
				 };

				//Image upload function
				vm.addImageToPost = function () {
					//alert('addImageToPost');
					vm.isAnImagePost = true;
					var modalInstance = $uibModal.open({
						animation: $scope.animationsEnabled,
						templateUrl: 'app/components/modal/imageUploadPostsView.html',
						controller: 'ImageUploadController',
						resolve: {
							id: function ()
							{
								return 1;
							}
						}
					});

					modalInstance.result.then(function (imageToPost) {
						vm.currentPost.image = imageToPost;

					}, function () {
						$log.info('Modal dismissed at: ' + new Date());
					});

				}

				//delete Post
				vm.deletePost = function(postsArray, postIndex) {
					var deletedPost = postsArray[postIndex];
					console.log(deletedPost);
					postsArray.splice(postIndex, 1);
					//service to send deleted post to backend
				};

				//edit Post
				vm.editPost = function(post) {
					var editedPost = post;
					//service to send edited post, still work in progress
					alert('service to send edited post');
					vm.editState = false;
				};
			};
		
			var template = '<button>{{vm.poststype}}</button>';

			return {
				restrict: 'E',
				scope: {
					poststype: '@',
					istextareahidden: '=',
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
