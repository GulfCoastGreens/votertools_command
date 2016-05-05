// Generated on 2015-02-03 using generator-angular-php 0.6.2
'use strict';

// # Globbing
// for performance reasons we're only matching one level down:
// 'test/spec/{,*/}*.js'
// use this if you want to recursively match all subfolders:
// 'test/spec/**/*.js'

module.exports = function (grunt) {

  // Load grunt tasks automatically
  require('load-grunt-tasks')(grunt);

  // Configurable paths for the application
  var appConfig = {
    name: require('./package.json').name,
    version: require('./package.json').version
  };

  // Define the configuration for all the tasks
  grunt.initConfig({

    // Project settings
    appEnv: appConfig,
    
    copy: {
        pharrename: {
            files: [{
                expand: true,
                dot: true,
                cwd: 'bin',
                dest: 'bin/',
                src: [
                    'votertools.phar'
                ],
                rename: function(dest, src) {
                    return dest + src.replace('.phar','');
                }
              }]
        }
    },
    clean: {
        pharrename: {
            src: ['bin/votertools.phar']
        },
        deploy: {
            src: ['deploy/*.rpm','deploy/*.deb']
        }
    },
    chmod: {
        options: {
            mode: '755'
        },
        pharbits: {
            // Target-specific file/dir lists and/or options go here.
            src: ['bin/votertools']
        }
    },    
    shell: {
        options: {
            stdout: true,
            stderr: true,
            failOnError: true
        },
        buildcmd: {
            command: './build.sh'
        },
        mkdeploy: {
            command: 'mkdir -p deploy'
        },
        fpmrpm: {
            "command": [
                [ 
                  '/usr/local/bin/fpm -s dir -t rpm -n \'<%= appEnv.name %>\' -v <%= appEnv.version %> --prefix /usr/local',
                  '"php"', 
                  '"php-common"', 
                  '"php-mysqlnd"', 
                  '"php-pdo"', 
                  '"php-devel"', 
                  '"php-pear"', 
                  '"php-gd"',
                  '"php-mcrypt"',
                  '"php-xml"',
                  '"php-mbstring"',
                  '"php-xml"',
                  '"php-cli"'
                ].join(' -d '), 
                '--after-install app/setupconfig.sh -p deploy bin'
            ].join(' ')
        }
    }
});
  
  grunt.registerTask('build', [
      "shell:buildcmd",
      "copy:pharrename",
      "clean:pharrename",
      "chmod:pharbits",
      "clean:deploy",
      "shell:fpmrpm"
  ]);
  
};