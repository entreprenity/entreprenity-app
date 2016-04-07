(function() {

	angular
		.module('entreprenityApp.directory', [])
	
		.factory('directoryService', function($http) {
			var baseUrl = 'dataServices/';
		
			return {
				getMembers: function() {
					return $http.get(baseUrl + 'getMembers.php');
				},
				getCompanies: function() {
					return $http.get(baseUrl + 'getCompanies.php');
				},
				getEvents: function() {
					return $http.get(baseUrl + 'getEvents.php');
				},
			};
		})
	
		.controller('DirectoryController', function(directoryService) {

			var that = this;
			/*
			countryService.getMembers().success(function(data) {
				that.members = data;
			});
			countryService.getCompanies().success(function(data) {
				that.companies = data;
			});
			countryService.getEvents().success(function(data) {
				that.events = data;
			});
			*/
		
		//Dummy Data
			that.members = [
				{
					"id": "1",
					"firstName": "Ken",
					"lastName": "Sia",
					"avatar": "url.jpg",
					"position": "Web Developer",
					"companyName": "cre8.ph",
					"city": "Taguig"
				},
				{
					"id": "2",
					"firstName": "Jaye",
					"lastName": "Atienza",
					"avatar": "url2.jpg",
					"position": "Designer",
					"companyName": "vOffice",
					"city": "Makati"
				},
				{
					"id": "3",
					"firstName": "Albert",
					"lastName": "Goh",
					"avatar": "url3.jpg",
					"position": "Entrepreneur",
					"companyName": "Founders Lab",
					"city": "Pasig"
				}];
		
		that.companies = [
			{
				"id": "1",
				"companyName": "Cre8",
				"description": "Succes by Design. Cre8 is a Web Design and Development Company",
				"avatar": "url1.jpg",
				"city": "Taguig"
			},
			{
				"id": "2",
				"companyName": "vOffice",
				"description": "vOffice (Virtual Office) is a leading provider of Virtual Office service and business services in Philippines",
				"avatar": "url2.jpg",
				"city": "Makati"
			},
			{
				"id": "3",
				"companyName": "Founders Lab",
				"description": "Breeding ground for future companies",
				"avatar": "url3.jpg",
				"city": "Pasig"
			}];
		
		that.events = [
			{
				"id": "1",
				"eventName": "Coffee One",
				"description": "Lorem ipsum is simply a dummy text",
				"poster": "url1.jpg",
				"city": "Taguig",
				"date": "Apr 4, 2016",
				"time": "8:00 - 10:00"
			},
			{
				"id": "2",
				"eventName": "Master The Art of Selling",
				"description": "Lorem ipsum is simply a dummy text",
				"poster": "url2.jpg",
				"city": "Makati",
				"date": "Apr 4, 2016",
				"time": "8:00 - 10:00"
			},
			{
				"id": "3",
				"eventName": "Business Writing MasterClass",
				"description": "Lorem ipsum is simply a dummy text",
				"poster": "url3.jpg",
				"city": "Pasig",
				"date": "Apr 4, 2016",
				"time": "8:00 - 10:00"
			}];
			
		});
		

})();