module.exports = function(grunt) {
  "use strict";
  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),
    banner: "/*! <%= pkg.name %> v<%= pkg.version %> | " +
    "<%= grunt.template.today('yyyy') %> <%= pkg.author %> | " +
    "<%= pkg.license %> License */\n",

    jshint: {
      src: {
        options: {
          jshintrc: ".jshintrc"
        },
        src: ["jquery.spoiler.js", "Gruntfile.js"],
      },
    },

    uglify: {
      options: {
        banner: "<%= banner %>",
        compress: true,
        mangle: true,
        report: "gzip"
      },
      build: {
        src: "jquery.spoiler.js",
        dest: "jquery.spoiler.min.js"
      }
    }
  });

  // Load the plugins
  grunt.loadNpmTasks("grunt-contrib-jshint");
  grunt.loadNpmTasks("grunt-contrib-uglify");

  grunt.registerTask("default", "List of commands", function() {
    grunt.log.writeln("");
    grunt.log.writeln("Run 'grunt lint' to lint the source files");
    grunt.log.writeln("Run 'grunt build' to minify the source files");
    grunt.log.writeln("Run 'grunt all' to run all tasks");
  });

  // Define the tasks
  grunt.registerTask("lint", "jshint");
  grunt.registerTask("build", "uglify");
  grunt.registerTask("all", ["lint", "build"]);
};
