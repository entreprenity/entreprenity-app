(function() {

	angular
		.module('entreprenityApp.directory', [])
		
		.factory('directoryService', function($http) 
		{
			var baseUrl = 'api/';
			return {
				getMembers: function(pageNumber) {
					return $http.get(baseUrl + 'getMembers?page='+pageNumber);
				},
				getCompanies: function(pageNumber) {
					return $http.get(baseUrl + 'getCompanies?page='+pageNumber);
				},
				getEvents: function(pageNumber) {
					return $http.get(baseUrl + 'getEvents?page='+pageNumber);
				},	
				getLocations:function() {
					return $http.get(baseUrl + 'getLocations');
				},
				postMemberFollow: function(memberId) 
				{
					var dataContent = {
			            'user' : memberId
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'followUser',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				},
				postMemberUnFollow: function(memberId) 
				{
					var dataContent = {
			            'user' : memberId
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'unfollowUser',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				},
				postCompanyFollow: function(companyId) 
				{
					var dataContent = {
			            'company' : companyId
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'followCompany',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				},
				postCompanyUnFollow: function(companyId) 
				{
					var dataContent = {
			            'company' : companyId
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'unfollowCompany',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				},
				postGoingEvent: function(eventId) 
				{
					var dataContent = {
			            'event' : eventId
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'goingToEvent',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				},
				postNotGoingEvent: function(eventId) 
				{
					var dataContent = {
			            'event' : eventId
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'notGoingToEvent',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				}
			};
		})
		
		/*
		.factory('Session', function($http) 
		{   
			 var baseUrl = 'api/'; 
		    return $http.get(baseUrl + 'get_user_session').then(function(result) {       
		        return result.data; 
		    });
		}) 
		*/
	
		.factory('Members', function(directoryService) {
			var Members = function() {
				this.items = [];
				this.busy = false;
				this.pageNumber = 1;
			};

			Members.prototype.nextPage = function() {
				if (this.busy) return;
				this.busy = true;
				
				directoryService.getMembers(this.pageNumber).success(function(data) {
					var itemData = data;
					
					for (var i = 0; i < itemData.length; i++) {
						//itemData[i].followed = false;
						this.items.push(itemData[i]);
					}
					
					this.pageNumber++;
					this.busy = false;
				}.bind(this));
			};
			return Members;
			console.log(vm.members);
		})
	
		.factory('Companies', function(directoryService) {
			var Companies = function() {
				this.items = [];
				this.busy = false;
				this.pageNumber = 1;
			};

			Companies.prototype.nextPage = function() {
				if (this.busy) return;
				this.busy = true;

				directoryService.getCompanies(this.pageNumber).success(function(data) {
					var itemData = data;
					
					for (var i = 0; i < itemData.length; i++) {
						this.items.push(itemData[i]);
					}
					
					this.pageNumber++;
					this.busy = false;
				}.bind(this));
			};
			return Companies;
		})
	
		.factory('Events', function(directoryService) {
			var Events = function() {
				this.items = [];
				this.busy = false;
				this.pageNumber = 1;
			};

			Events.prototype.nextPage = function() {
				if (this.busy) return;
				this.busy = true;

				directoryService.getEvents(this.pageNumber).success(function(data) {
					var itemData = data;

					for (var i = 0; i < itemData.length; i++) {
						this.items.push(itemData[i]);
					}
					
					this.pageNumber++;
					this.busy = false;
				}.bind(this));
			};
			return Events;
		})

		.controller('DirectoryController', function(Members, Companies, Events, directoryService, $filter) {
			/*, 
			Session.then(function(response){
				$rootScope.session = response;
			});
			*/
			var vm = this;
		
			vm.members = new Members();
			vm.companies = new Companies();
			vm.events = new Events();
			//vm.location = new Location();
			console.log(vm.members);
		
			directoryService.getLocations().success(function(data) {
				vm.locations = data;
				//console.log(vm.location);
			});
			
			
			vm.follow_member = function(memberId) {
				var index = returnIndexOfCLicked(vm.members.items, memberId);				
				directoryService.postMemberFollow(memberId).success(function(data) {
					vm.members.items[index].followed = data.followed; //return user_info, with updated followers and followed status
				});
			}

			
			vm.unFollow_member = function(memberId) {
				var index = returnIndexOfCLicked(vm.members.items, memberId);
				directoryService.postMemberUnFollow(memberId).success(function(data) {
					vm.members.items[index].followed = data.followed; //return user_info, with updated followers and followed status
				});
			}
			
			vm.follow_company = function(companyId) {
				var index = returnIndexOfCLicked(vm.companies.items, companyId);
				directoryService.postCompanyFollow(companyId).success(function(data) {
					vm.companies.items[index].followed = data.followed; //return user_info, with updated followers and followed status
				});
			}

			vm.unFollow_company = function(companyId) {
				var index = returnIndexOfCLicked(vm.companies.items, companyId);
				directoryService.postCompanyUnFollow(companyId).success(function(data) {
					vm.companies.items[index].followed = data.followed; //return user_info, with updated followers and followed status
				});
			}
			
			vm.going_event = function(eventId) {
				var index = returnIndexOfCLicked(vm.events.items, eventId);
				directoryService.postGoingEvent(eventId).success(function(data) {
					vm.events.items[index].joining = data.joining; //return user_info, with updated followers and followed status
				});
			}

			vm.notGoing_event = function(eventId) {
				var index = returnIndexOfCLicked(vm.events.items, eventId);
				directoryService.postNotGoingEvent(eventId).success(function(data) {
					vm.events.items[index].joining = data.joining; //return user_info, with updated followers and followed status
				});
			}
			
			function returnIndexOfCLicked(itemsArray,id ) {
				var items = itemsArray;
				var clickedObject = $filter('filter')(items, { id: id  }, true)[0];
				var index = items.indexOf(clickedObject);
				return index;
			}
			
		});
	
	$(function() {
		$('.item').matchHeight();
	});
})();
