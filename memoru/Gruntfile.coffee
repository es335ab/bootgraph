module.exports = (grunt)->
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json')

    concat:
      dist:
        src: [
          'src/js/jquery.min.js',
          'src/js/create_global-object.js',
          'src/js/a.js'
        ]
        dest: 'public/js/memoru.js'

    uglify:
      dist:
        src: 'public/js/memoru.js',
        dest: 'public/js/memoru.min.js'

    compass:
      dist:
        options:
          config: 'config.rb'

    watch:
      css:
        files: ['src/sass/*.scss']
        tasks: ['compass']
        options:
          atBegin: true
      js:
        files: ['src/js/*.js']
        tasks: ['concat', 'uglify']
        options:
          atBegin: true

  })

  grunt.loadNpmTasks('grunt-contrib-compass')
  grunt.loadNpmTasks('grunt-contrib-concat')
  grunt.loadNpmTasks('grunt-contrib-uglify')
  grunt.loadNpmTasks('grunt-contrib-watch')
  grunt.loadNpmTasks('grunt-contrib-clean')

  grunt.registerTask('default', [ 'concat', 'uglify', 'compass'])