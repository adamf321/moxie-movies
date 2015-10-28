(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var app = angular.module('moxiemovies', []);
},{}],2:[function(require,module,exports){
(function ()
{
    angular.module('moxiemovies').directive('showMovies',
        ['moviesService', function( moviesService )
        {
            var link = function(scope, element, attrs)
            {
                scope.movies = [];

                moviesService.get(
                    function( response )
                    {
                        scope.movies = response.data;
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
                    '<ol>' +
                        '<li ng-repeat="movie in movies" id="movie-{{movie.id}}">' +
                            '<h2>{{movie.title}}</h2>' +
                            '<div class="description">{{movie.short_description}}</div>' +
                            '<div class="meta">' +
                                '<span class="rating" ng-show="{{movie.rating}}">Rating: {{movie.rating}}</span>' +
                                '<span class="year" ng-show="{{movie.year}}">Year: {{movie.year}}</span>' +
                                '<span class="poster" ng-show="{{movie.poster_url}}"><a ng-href="{{movie.poster_url}}">Poster</a></span>' +
                            '</div>' +
                        '</li>' +
                    '</ol>'
            };
        }]);
}());
},{}],3:[function(require,module,exports){
//App
require('./app');

//Services
require('./services/movies');

//Directives
require('./directives/show-movies');
},{"./app":1,"./directives/show-movies":2,"./services/movies":4}],4:[function(require,module,exports){
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
                            nonce:  ''
                        }
                    };

                    $http.get( MoxieMovies.ajaxUrl, config ).then( callback, failure );
                }
            }
        }]);
}());
},{}]},{},[3]);
