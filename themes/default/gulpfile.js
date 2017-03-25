'use strict';

var gulp = require('gulp');
var bower = require('gulp-bower');
var concat = require('gulp-concat');
var cssmin = require('gulp-clean-css');
var uglify = require('gulp-uglify');

// ----------------------------
// Files
// ----------------------------
var files = {
  js: [
    'bower_components/jquery/dist/jquery.min.js',
    'bower_components/bootstrap/dist/js/bootstrap.min.js',
    'bower_components/jquery-loader-plugin/min/jquery.loader.min.js',
    'js/app.js'
  ],

  css: [
    'bower_components/bootstrap/dist/css/bootstrap.min.css',
    'bower_components/font-awsome/css/font-awesome.min.css',
    'bower_components/jquery-loader-plugin/min/jquery.loader.min.css',
    'css/style.css'
  ],

  fonts: [
    'bower_components/font-awsome/fonts/*'
  ],

  images: [
    'img/background.png'
  ]
}

// ----------------------------
// Configuration
// ----------------------------
var option = {
  cssmin: {
    keepSpecialComments: 0,
    compatibility: 'ie9,-properties.zeroUnits',
    advanced: false
  },
}

// ----------------------------
// Gulp task definitions
// ----------------------------
gulp.task('default', ['css', 'js', 'fonts', 'img']);

gulp.task('bower', function() {
  return bower();
});

gulp.task('fonts', function() {
  return gulp.src(files.fonts)
    .pipe(gulp.dest('../../assets/fonts'));
});

gulp.task('img', function() {
  return gulp.src(files.images)
    .pipe(gulp.dest('../../assets/img'));
});

gulp.task('css', ['bower'],function() {
  return gulp.src(files.css)
    .pipe(cssmin(option.cssmin))
    .pipe(concat('default.css'))
    .pipe(gulp.dest('../../assets'));
});

gulp.task('js', ['bower'], function() {
  return gulp.src(files.js)
    .pipe(uglify())
    .pipe(concat('app.min.js'))
    .pipe(gulp.dest('../../assets'));
});

gulp.task('watch', ['default'], function() {
  gulp.watch('js/*.js', ['js']);
  gulp.watch('css/*.css', ['css']);
});
