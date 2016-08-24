(function() {

	angular
		.module('entreprenityApp.login', [])
		
		.controller('LoginController', function($scope, $http, $location) {
			var vm = this;
			
			$scope.post = {};
			$scope.post.login = [];
			$scope.vm = {};
			$scope.index = '';
			
			var baseUrl = 'api/';

			// function to submit the form after all validation has occurred            
			vm.login = function(isValid) 
			{
				// check to make sure the form is completely valid
				if (isValid) 
				{
				    $http({
				      method: 'post',
				      url: baseUrl+'login',
				      data: $.param($scope.vm),
				      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				    })
				    .success(function(data, status, headers, config) 
				    {
				    	if(data.success)
				    	{
					    	if (localStorage['entrp_token'])
					    	{
					    		localStorage.removeItem('entrp_token');
					    	}					    		
					    	localStorage.setItem("entrp_token", JSON.stringify(data.login_token));
				    		localStorage.isLogged = 'true';
				    		$location.path('/home');
				    	}
				    	else
				    	{
					    	if (localStorage['entrp_token'])
					    	{
					    		localStorage.removeItem('entrp_token');
					    	}
							vm.errorMessage = data.msg;
				    	}
		    		}).
		    		error(function(data, status, headers, config) 
		    		{
			    		if (localStorage['entrp_token'])
				    	{
				    		localStorage.removeItem('entrp_token');
				    	}
						vm.errorMessage = data.msg;
		    		});
				}
			};
			
	
			//Login with facebook profile				
			$scope.FBLogin= function()
			{
				FB.login(function(response) 
				{
				    if (response.authResponse) 
				    {

				     FB.api('/me?fields=email,first_name,last_name,gender', function(response) {
				       
				       if(response.id)
				       {
				       	//var accessToken=FB.getAuthResponse();
				       	//console.log(accessToken);
				       	var dataContent = {
			            'fid' 			: response.id
			            //'accessToken' 	: accessToken
			        		};
			        		$http({
						      method: 'post',
						      url: baseUrl+'loginWithFaceBook',
						      data: $.param(dataContent),
						      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
						    })
						    .success(function(data, status, headers, config) 
						    {
						    	if(data.success)
						    	{
							    	if (localStorage['entrp_token'])
							    	{
							    		localStorage.removeItem('entrp_token');
							    	}					    		
							    	localStorage.setItem("entrp_token", JSON.stringify(data.login_token));
						    		localStorage.isLogged = 'true';
						    		$location.path('/home');
						    	}
						    	else
						    	{
							    	if (localStorage['entrp_token'])
							    	{
							    		localStorage.removeItem('entrp_token');
							    	}
									vm.errorMessage = data.msg;
						    	}
				    		}).
				    		error(function(data, status, headers, config) 
				    		{
					    		if (localStorage['entrp_token'])
						    	{
						    		localStorage.removeItem('entrp_token');
						    	}
								vm.errorMessage = data.msg;
				    		});
							
				      }				       
				       
				 		});
					}				
				});
			} 
			/*
	      else 
	      {
	        console.log('User cancelled login with facebook.');
	      }	
	      */
	
			
			//Function to validate login form
			//April 15,2016
			$scope.getError = function(error, name)
			{
				if(angular.isDefined(error))
				{
					if(error.email && name == 'username')
					{
						return "Please enter a valid email";
					}
					else if(error.required && name == 'username')
					{
						return "This field is required";
					}
					else if(error.required && name == 'password')
					{
						return "Please enter your password";
					}
					else if(error.minlength && name == 'password')
					{
						return "Name must be 4 characters long";
					}
			  }
		  }
		  
			
			
			
		});
	
})();