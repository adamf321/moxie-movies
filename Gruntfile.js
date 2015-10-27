module.exports = function(grunt) {
    "use strict";

    grunt.initConfig({
        sass: {
            dev: {
                options: {
                    style: 'nested',
                    noCache: true
                },
                files: [{
                    expand: true,
                    src: ['styles/moxie-movies.scss'],
                    ext: '.css'
                }]
            }
        },
        watch: {
            sass: {
                files: [
                    '**/*.scss'
                ],
                tasks: [
                    'sass:dev'
                ]
            },
            browserifydist: {
                files: [
                    'app/**/*.*', '!app/bundle.js'
                ],
                tasks: [
                    'browserify:dist'
                ]
            }
        },
        browserify: {
            dist: {
                files: {
                    'app/bundle.js': ['app/main.js']
                }
            }
        }
    });

    // Load tasks
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-browserify');

    // Default task
    grunt.registerTask('default', []);

    // Dev task
    grunt.registerTask('dev', [
        'sass:dev',
        'browserify',
        'watch'
    ]);
};