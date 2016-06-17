(function() {

    angular
        .module('entreprenityApp.addEvent', [])

        .factory('addEventService', function($http) {
            var baseUrl = 'api/';

            return {
            //services
            };
        })

        .controller('addEventController', function($routeParams, addEventService, $scope, id) {

            // function to submit the form after all validation has occurred
            vm.addEvent = function(isValid)
            {
                // check to make sure the form is completely valid
                if (isValid)
                {
                    //alert('isValid');
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
                            //alert('success');
                        }
                        else
                        {
                            //alert('data error');
                        }
                    }).
                    error(function(data, status, headers, config)
                    {
                        //alert('post error');
                    });
                }
            };

            
        });
})();