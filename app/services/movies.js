(function()
{
    angular.module('moxiemovies').factory('moviesService',
        ['$http', function ($http)
        {
            return {

                get: function( callback, failure )
                {
                    var config = {
                        params: {
                            action: 'get_movies',
                            nonce:  MoxieMovies.nonce
                        }
                    };

                    $http.get( MoxieMovies.ajaxUrl, config ).then( callback, failure );
                }
            }
        }]);
}());