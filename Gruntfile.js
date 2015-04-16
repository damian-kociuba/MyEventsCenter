// Gruntfile.js

module.exports = function (grunt) {
    grunt.initConfig({
        shell: {
            tests: {
                command: 'phpunit -c app/',
                options: {
                    stdout: true
                }
            }
        },
        watch: {
            files: ['app/**/*.*', 'src/**/*.*', '!app/cache/**/*', '!app/logs/**/*'],
            tasks: ['shell:tests']
        }
    });
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-shell');
};