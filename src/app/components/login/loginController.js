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
					//alert('our form is amazing');
				    $http({
				      method: 'post',
				      url: baseUrl+'login',
				      data: $scope.vm,
				      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				    })
				    .success(function(data, status, headers, config) 
				    {
				    	if(data.success)
				    	{
				    		//alert('valid');
				    		alert(data.msg);
				    		$location.path('/directory');
				    	}
				    	else
				    	{
				    		//alert('invalid 1');
				    		alert(data.msg);
				    	}
		    		}).
		    		error(function(data, status, headers, config) 
		    		{
		    			//alert('invalid 2');
		    			alert(data.msg);
		    		});

				    /*
				     .success(function(data) 
				     {
			            if(data.success)
					    	{
					    		alert('valid');
					    		alert(data.message);
					    	}
					    	else
					    	{
					    		alert('invalid 1');
					    		alert(data.message);
					    	}
		          });				    
		    		*/
				}
			};
			
			
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