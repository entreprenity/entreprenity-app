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
	
	/*
		.factory('Location', function(directoryService) {
			var Location = function() {
				this.items = [];
			};
			
			directoryService.getLocations().success(function(data) {
				this.items = data;
				console.log(this.items);
			}.bind(this));

			return Location;
		})
		*/

		.controller('DirectoryController', function(Members, Companies, Events, directoryService) {
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
			
			/*
			vm.follow = function(memberIndex) {
				alert(memberIndex);
				var followedmember = vm.members.items[memberIndex];
				alert(followedmember);
				memberProfileService.postMemberUnFollow(sessionId, memberId).success(function(data) {
					vm.member.items[memberIndex].followed = data; //boolean = false
				});	
			}
			*/
		});
	
	$(function() {
		$('.item').matchHeight();
	});
})();
