module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		concat: {
			css: {
				files: {
					'assets/css/admin.css': [
						'resources/assets/css/admin.css'
					],
					'assets/css/public.css': [
						'resources/assets/bower_components/owlcarousel/owl-carousel/owl.carousel.css',
						'resources/assets/bower_components/owlcarousel/owl-carousel/owl.transitions.css',
						'resources/assets/css/public.css'
					]
				}
			},
			js: {
				files: {
					'assets/js/admin.js': [
						'resources/assets/js/admin.js'
					],
					'assets/js/public.js': [
						'resources/assets/bower_components/owlcarousel/owl-carousel/owl.carousel.js',
						'resources/assets/js/public.js'
					]
				}
			}
		},
		copy: {
			images: {
				files: [{
					expand: true,
					flatten: true,
					src: ['resources/assets/images/*'],
					dest: 'assets/images'
				}]
			}
		},
		cssmin: {
			main: {
				files: {
					'assets/css/admin.min.css': [
						'assets/css/admin.css'
					],
					'assets/css/public.min.css': [
						'assets/css/public.css'
					]
				}
			},
		},
		makepot: {
			target: {
				options: {
					include: [
						'src/.*'
					],
					type: 'wp-plugin'
				}
			}
		},
		replace: {
			js: {
				files: {
					'assets/js/public.js': [
						'assets/js/public.js'
					],
					'assets/js/public.min.js': [
						'assets/js/public.min.js'
					]
				},
				options: {
					patterns: [{
						match: 'owlCarousel',
						replacement: 'easingSlider'
					}, {
						match: 'owl-carousel',
						replacement: 'easingslider'
					}, {
						match: 'lazyOwl',
						replacement: 'easingslider-lazy'
					}, {
						match: 'owl',
						replacement: 'easingslider'
					}],
					usePrefix: false
				}
			},
			css: {
				files: {
					'assets/css/public.css': [
						'assets/css/public.css'
					],
					'assets/css/public.min.css': [
						'assets/css/public.min.css'
					],
				},
				options: {
					patterns: [{
						match: 'owl-carousel',
						replacement: 'easingslider-container'
					}, {
						match: 'owl',
						replacement: 'easingslider'
					}],
					usePrefix: false
				}
			},
		},
		uglify: {
			main: {
				files: {
					'assets/js/admin.min.js': [
						'assets/js/admin.js'
					],
					'assets/js/public.min.js': [
						'assets/js/public.js'
					]
				}
			}
		},
		watch: {
			js: {
				files: [
					'resources/assets/js/*.js'
				],
				tasks: ['js']
			},
			css: {
				files: [
					'resources/assets/css/*.css'
				],
				tasks: ['css']
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-replace');
	grunt.loadNpmTasks('grunt-wp-i18n');

	grunt.registerTask('js',      ['concat:js', 'uglify:main', 'replace:js']);
	grunt.registerTask('css',     ['concat:css', 'cssmin:main', 'replace:css']);
	grunt.registerTask('default', ['makepot', 'css', 'js', 'copy']);
};
