/* jshint node:true */
module.exports = function (grunt) {
	'use strict';

	grunt.initConfig({
						 pkg: grunt.file.readJSON('package.json'),
						 // setting folder templates
						 dirs: {
							 css : 'assets/css/',
							 js  : 'assets/js',
							 lang: 'languages'
						 },
						 // Minify all .css files.
						 cssmin: {
							 minify: {
								 expand: true,
								 cwd   : '<%= dirs.css %>/',
								 src   : ['*.css','**/*.css'],
								 dest  : '<%= dirs.css %>/',
								 ext   : '.min.css'
							 }
						 },

						 // Minify .js files.
						 uglify: {
							 options: {
								 preserveComments: 'some'
							 },
							 jsfiles: {
								 files: [{
									 expand: true,
									 cwd   : '<%= dirs.js %>/',
									 src   : [
										 '**/*.js',
										 '*.js',
										 '!*.min.js',
										 '!Gruntfile.js',
									 ],
									 dest  : '<%= dirs.js %>/',
									 ext   : '.min.js'
								 }]
							 }
						 },
						 // Generate POT files.
						 makepot: {
							 options : {
								 type      : 'wp-plugin',
								 domainPath: 'languages',
							 },
							 frontend: {
								 options: {
									 potFilename: 'wc-promoted-product.pot',
									 exclude    : [
										 'node_modules/.*',
										 'tests/.*',
										 'tmp/.*'
									 ],
									 processPot : function (pot) {
										 return pot;
									 }
								 }
							 }
						 },

						 po2mo: {
							 files: {
								 src   : '<%= dirs.lang %>/*.po',
								 expand: true
							 }
						 },

						 compress: {
							 build: {
								 options: {
									 pretty : true,                           // Pretty print file sizes when logging.
									 archive: '<%= pkg.name %>-<%= pkg.version %>.zip'
								 },
								 expand : true,
								 cwd    : '',
								 src    : [
									 '**',
									 '!node_modules/**',
									 '!.github/**',
									 '!**.zip',
									 '!.git/**',
									 '!build/**',
									 '!readme.md',
									 '!README.md',
									 '!phpcs.ruleset.xml',
									 '!package-lock.json',
									 '!svn-ignore.txt',
									 '!Gruntfile.js',
									 '!package.json',
									 '!composer.json',
									 '!composer.lock',
									 '!postcss.config.js',
									 '!webpack.config.js',
									 '!set_tags.sh',
									 '!*.zip',
									 '!old/**',
									 '!bin/**',
									 '!tests/**',
									 '!codeception.dist.yml',
									 '!regconfig.json',
									 '!nbproject/**'],
								 dest   : '<%= pkg.name %>'
							 }
						 },
						// Check the text domain
						checktextdomain: {
							standard: {
								options: {
									text_domain: [ 'wc-promoted-product' ], //Specify allowed domain(s)
									create_report_file: 'true',
									keywords: [ //List keyword specifications
										'__:1,2d',
										'_e:1,2d',
										'_x:1,2c,3d',
										'esc_html__:1,2d',
										'esc_html_e:1,2d',
										'esc_html_x:1,2c,3d',
										'esc_attr__:1,2d',
										'esc_attr_e:1,2d',
										'esc_attr_x:1,2c,3d',
										'_ex:1,2c,3d',
										'_n:1,2,4d',
										'_nx:1,2,4c,5d',
										'_n_noop:1,2,3d',
										'_nx_noop:1,2,3c,4d'
									]
								},
								files: [
									{
										src: [
											'**/*.php',
											'!**/node_modules/**',
										], //all php
										expand: true
									}
								]
							}
						},

					 });

	// Load NPM tasks to be used here
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-wp-i18n');
	grunt.loadNpmTasks('grunt-checktextdomain');
	grunt.loadNpmTasks('grunt-contrib-compress');

	// Register tasks
	grunt.registerTask('default', [
		'cssmin',
		'uglify'
	]);

	// Just an alias for pot file generation
	grunt.registerTask('pot', [
		'makepot'
	]);

	// Build task
	grunt.registerTask('build-archive', [
		'compress:build',
	]);
};