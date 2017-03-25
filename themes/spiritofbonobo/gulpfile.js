'use strict';

var gulp = require('gulp');
var bower = require('gulp-bower');
var concat = require('gulp-concat');
var cssmin = require('gulp-clean-css');

// ----------------------------
// Files
// ----------------------------
var files = {

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
    'img/**/*.png',
    'img/**/*.jpg'
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
gulp.task('default', ['css', 'fonts', 'img']);

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
    .pipe(concat('spiritofbonobo.css'))
    .pipe(gulp.dest('../../assets'));
});

gulp.task('watch', ['default'], function() {
  gulp.watch('css/*.css', ['css']);
});
