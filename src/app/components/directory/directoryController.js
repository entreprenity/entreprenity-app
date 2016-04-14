(function() {

	angular
		.module('entreprenityApp.directory', [])
	
		.factory('directoryService', function($http) 
		{
			var baseUrl = 'api/';
			return {
				getMembers: function() {
					return $http.get(baseUrl + 'getMembers');
				}
				,
				getCompanies: function() {
					return $http.get(baseUrl + 'getCompanies');
				},
				getEvents: function() {
					return $http.get(baseUrl + 'getEvents');
				},
				
			};
		})
	
		.controller('DirectoryController', function(directoryService) {

			var vm = this;
<<<<<<< HEAD
			/*
			directoryService.getMembers().success(function(data) {
				vm.members = data;
			});
			directoryService.getCompanies().success(function(data) {
				vm.companies = data;
			});
			directoryService.getEvents().success(function(data) {
				vm.events = data;
			});
			*/
			
		
		//Dummy Data
	
		vm.members = [
				{
					"id": "1",
					"firstName": "Ken",
					"lastName": "Sia",
					"avatar": "img-member.jpg",
					"position": "Web Developer",
					"companyName": "cre8.ph",
					"city": "Taguig"
				},
				{
					"id": "2",
					"firstName": "Jaye",
					"lastName": "Atienza",
					"avatar": "img-member.jpg",
					"position": "Designer",
					"companyName": "vOffice",
					"city": "Makati"
				},
				{
					"id": "3",
					"firstName": "Albert",
					"lastName": "Goh",
					"avatar": "img-member.jpg",
					"position": "Entrepreneur",
					"companyName": "Founders Lab",
					"city": "Pasig"
				}];
		
		vm.companies = [
			{
				"id": "1",
				"companyName": "Cre8",
				"description": "Succes by Design. Cre8 is a Web Design and Development Company",
				"avatar": "img-company.jpg",
				"city": "Taguig"
			},
			{
				"id": "2",
				"companyName": "vOffice",
				"description": "vOffice (Virtual Office) is a leading provider of Virtual Office service and business services in Philippines",
				"avatar": "img-company.jpg",
				"city": "Makati"
			},
			{
				"id": "3",
				"companyName": "Founders Lab",
				"description": "Breeding ground for future companies",
				"avatar": "img-company.jpg",
				"city": "Pasig"
			}];
		
		vm.events = [
			{
				"id": "1",
				"eventName": "Coffee One",
				"description": "Lorem ipsum is simply a dummy text",
				"poster": "img-event.jpg",
				"city": "Taguig",
				"date": "Apr 4, 2016",
				"time": "8:00 - 10:00"
			},
			{
				"id": "2",
				"eventName": "Master The Art of Selling",
				"description": "Lorem ipsum is simply a dummy text",
				"poster": "img-event.jpg",
				"city": "Makati",
				"date": "Apr 4, 2016",
				"time": "8:00 - 10:00"
			},
			{
				"id": "3",
				"eventName": "Business Writing MasterClass",
				"description": "Lorem ipsum is simply a dummy text",
				"poster": "img-event.jpg",
				"city": "Pasig",
				"date": "Apr 4, 2016",
				"time": "8:00 - 10:00"
			}];
=======
			
			directoryService.getMembers().success(function(data) {
				vm.members =data;
			});
			
			directoryService.getCompanies().success(function(data) {
				vm.companies = data;
			});
			directoryService.getEvents().success(function(data) {
				vm.events = data;
			});
>>>>>>> refs/remotes/origin/master
			
		});
		
		

})();