(function ()
{
    angular.module('moxiemovies').directive('showMovies',
        ['moviesService', function( moviesService )
        {
            var link = function(scope, element, attrs)
            {
                scope.movies = [];

                scope.loading = true;

                moviesService.get(
                    function( response )
                    {
                        scope.movies = response.data;

                        scope.loading = false;
                    },
                    function()
                    {
                        console.log('An error occured whilst retrieving the movies');
                    }
                )
            };

            return {
                link: link,
                restrict: 'E',
                replace: true,
                template:
                    '<div class="movies-list">' +
                        '<div class="loader" ng-show="loading"></div>' +
                        '<div class="no-movies" ng-show="!loading && !movies.length">No movies found.</div>' +
                        '<ol>' +
                            '<li ng-repeat="movie in movies" id="movie-{{movie.id}}" class="movie">' +
                                '<h2>{{movie.title}}</h2>' +
                                '<div class="description">{{movie.short_description}}</div>' +
                                '<div class="meta">' +
                                    '<span class="rating" ng-show="{{movie.rating}}">Rating: {{movie.rating}}</span>' +
                                    '<span class="year" ng-show="{{movie.year}}">Year: {{movie.year}}</span>' +
                                    '<span class="poster" ng-show="{{movie.poster_url}}"><a ng-href="{{movie.poster_url}}">Poster</a></span>' +
                                '</div>' +
                            '</li>' +
                        '</ol>' +
                    '</div>'
            };
        }]);
}());