(function() {

	angular
		.module('entreprenityApp.home', [])
		
		.factory('myHomeService', function($http) {
			var baseUrl = 'api/';
			return {
				getUserSessInfo: function() {
					return $http.get(baseUrl+ 'get_user_session');
				}
			};
		})
	
		.controller('HomeController', function($routeParams, myHomeService) {
				var vm = this;		
				
				//To get user session value
				myHomeService.getUserSessInfo().success(function(data) {
					vm.id = data.id;
				});
		
		
				vm.latestEvents = data = {
						"profilePhoto": "member01.jpg",
						"coverPhoto": "memberCover01.jpg",
						"companyName": "vOffice",
						"location": "Fort Legend Tower",
						"companyDesc": "We provide businesses superior reach and access to South East Asia markets like Jakarta, Manila, Kuala Lumpur and Singapore.",
						"email": "info@voffice.com",
						"website": "voffice.com.ph",
						"mobile": "6322242000",
						"category": [
							"Virtual Office",
							"Serviced Office",
							"Coworking Space"
						],
						"allCategory" : []
				};
		
				vm.newMembers = data = [
					{
						"id": "1",
						"avatar": "member01.jpg",
						"firstName": "Kurt",
						"lastName": "Megan",
						"position": "Office Assistant",
						"company": "Pet Studio.com",
					},
					{
						"id": "2",
						"avatar": "member02.jpg",
						"firstName": "Will",
						"lastName": "Ferrel",
						"position": "CEO",
						"company": "Clever Sheep",
					},
					{
						"id": "3",
						"avatar": "member03.jpg",
						"firstName": "Will",
						"lastName": "Ferrel",
						"position": "CEO",
						"company": "Clever Sheep",
					},
				];
		});
	
		

})();
