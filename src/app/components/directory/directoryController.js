(function() {

	angular
		.module('entreprenityApp.directory', [])
	
		.factory('directoryService', function($http) 
		{
			var baseUrl = 'api/';
			return {
				getMembers: function() {
					return $http.get(baseUrl + 'getMembers?page=');
				},
				getCompanies: function() {
					return $http.get(baseUrl + 'getCompanies');
				},
				getEvents: function() {
					return $http.get(baseUrl + 'getEvents');
				},	
			};
		})
	
		.factory('Members', function(directoryService) {
			var Members = function() {
				this.items = [];
				this.busy = false;
				this.pageNumber = 1;
			};

			Members.prototype.nextPage = function() {
				if (this.busy) return;
				this.busy = true;
				
				directoryService.getMembers().success(function(data) {
					var itemData = data;
					
					for (var i = 0; i < itemData.length; i++) {
						this.items.push(itemData[i]);
					}
					
					this.pageNumber++;
					this.busy = false;
				}.bind(this));
			};
			return Members;
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

				directoryService.getCompanies().success(function(data) {
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

				directoryService.getEvents().success(function(data) {
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
	
		.controller('DirectoryController', function($scope, Members, Companies, Events) {
		
			$scope.members = new Members();
			$scope.companies = new Companies();
			$scope.events = new Events();

		});
})();
