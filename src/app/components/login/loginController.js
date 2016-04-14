(function() {

	angular
		.module('entreprenityApp.login', [])

		.controller('LoginController', function() {
			var vm = this;

			// function to submit the form after all validation has occurred            
			vm.login = function(isValid) {

				// check to make sure the form is completely valid
				if (isValid) {
					alert('our form is amazing');
				}
			};
		});
	
})();