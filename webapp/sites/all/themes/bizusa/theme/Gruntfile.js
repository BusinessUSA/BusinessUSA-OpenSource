/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),
    banner: '/*! Business USA - v<%= pkg.version %> - ' +
      '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
      '<%= pkg.homepage ? "* " + pkg.homepage + "\\n" : "" %>' +
      '* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
      ' Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %> */\n',

    jqueryCheck: 'if (typeof jQuery === \'undefined\') { throw new Error(\'Bootstrap\\\'s JavaScript requires jQuery\') }\n\n',

    clean: {
      dist: ['dist']
    },

    copy: {
      fonts: {
        expand: true,
        flatten: true,
        src: 'bootstrap/fonts/*',
        dest: 'dist/fonts/'
      }
    },

    less: {
      compileCore: {
        options: {
          strictMath: true,
          sourceMap: true,
          outputSourceFiles: true,
          sourceMapURL: 'bootstrap.css.map',
          sourceMapFilename: 'dist/css/bootstrap.css.map'
        },
        files: {
          'dist/css/bootstrap.css': 'bootstrap/less/bootstrap.less'
        }
      },
      compileTheme: {
        options: {
          strictMath: true,
          sourceMap: true,
          outputSourceFiles: true,
          sourceMapURL: 'theme.css.map',
          sourceMapFilename: 'dist/css/theme.css.map'
        },
        files: {
          'dist/css/theme.css': 'less/theme.less'
        }
      }
    },

    autoprefixer: {
      options: {
        browsers: [
          'Android 2.3',
          'Android >= 4',
          'Chrome >= 20',
          'Firefox >= 24', // Firefox 24 is the latest ESR
          'Explorer >= 8',
          'iOS >= 6',
          'Opera >= 12',
          'Safari >= 6'
        ]
      },
      core: {
        options: {
          map: true
        },
        src: 'dist/css/bootstrap.css'
      },
      theme: {
        options: {
          map: true
        },
        src: 'dist/css/theme.css'
      }
    },

    usebanner: {
      options: {
        position: 'top',
        banner: '<%= banner %>'
      },
      files: {
        src: 'dist/css/*.css'
      }
    },

    csscomb: {
      options: {
        config: 'bootstrap/less/.csscomb.json'
      },
      dist: {
        expand: true,
        cwd: 'dist/css/',
        src: ['*.css', '!*.min.css'],
        dest: 'dist/css/'
      }
    },

    cssmin: {
      options: {
        compatibility: 'ie8',
        keepSpecialComments: '*',
        noAdvanced: true
      },
      core: {
        files: {
          'dist/css/bootstrap.min.css': 'dist/css/bootstrap.css',
          'dist/css/theme.min.css': 'dist/css/theme.css'
        }
      }
    },

    concat: {
      options: {
        banner: '<%= banner %>\n<%= jqueryCheck %>',
        stripBanners: false
      },
      bootstrap: {
        src: [
          'bootstrap/js/transition.js',
          'bootstrap/js/alert.js',
          'bootstrap/js/button.js',
          'bootstrap/js/carousel.js',
          'bootstrap/js/collapse.js',
          'bootstrap/js/dropdown.js',
          'bootstrap/js/modal.js',
          'bootstrap/js/tooltip.js',
          'bootstrap/js/popover.js',
          'bootstrap/js/scrollspy.js',
          'bootstrap/js/tab.js',
          'bootstrap/js/affix.js'
        ],
        dest: 'dist/js/bootstrap.js'
      }
    },

    uglify: {
      options: {
        preserveComments: 'some'
      },
      bootstrap: {
        src: '<%= concat.bootstrap.dest %>',
        dest: 'dist/js/bootstrap.min.js'
      }
    },

    jshint: {
      options: {
        jshintrc: 'bootstrap/js/.jshintrc'
      },
      grunt: {
        options: {
          jshintrc: 'grunt/.jshintrc'
        },
        src: ['Gruntfile.js']
      },
      src: {
        src: 'bootstrap/js/*.js'
      },
      test: {
        options: {
          jshintrc: 'bootstrap/js/tests/unit/.jshintrc'
        },
        src: 'bootstrap/js/tests/unit/*.js'
      }
    },

    qunit: {
      options: {
        inject: 'bootstrap/js/tests/unit/phantom.js'
      },
      files: 'bootstrap/js/tests/index.html'
    },

    watch: {
      less: {
        files: ['less/*.less'],
        tasks: ['less:compileTheme']
      }
    }

  });

  // These plugins provide necessary tasks.
  require('load-grunt-tasks')(grunt, { scope: 'devDependencies' });

  // JS distribution task.
  grunt.registerTask('dist-js', ['concat', 'uglify']);

  // CSS distribution task.
  grunt.registerTask('less-compile', ['less:compileCore', 'less:compileTheme']);
  grunt.registerTask('dist-css', ['less-compile', 'autoprefixer', 'usebanner', 'csscomb', 'cssmin']);

  // Full distribution task.
  grunt.registerTask('dist', ['clean', 'dist-css', 'copy:fonts', 'dist-js']);

  // Default task.
  grunt.registerTask('default', ['dist']);

};
