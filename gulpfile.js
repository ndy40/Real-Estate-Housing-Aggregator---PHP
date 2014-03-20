var gulp = require('gulp'),
	sass = require('gulp-sass'),
	prefix = require('gulp-autoprefixer'),
	minifyCSS = require('gulp-minify-css'),
	concat = require('gulp-concat'),
	uglify = require('gulp-uglify');

gulp.task('styles', function () {
    gulp.src('public/css/dev/*.scss')
        .pipe(sass())
        .pipe(prefix("last 1 version", "> 1%", "ie 8", "ie 7"))
        .pipe(minifyCSS())
        .pipe(gulp.dest('public/css'))
});

gulp.task('scripts', function () {
	gulp.src('public/js/dev/*.js')
		.pipe(concat("scripts.js"))
		.pipe(uglify())
		.pipe(gulp.dest('public/js'))
});

gulp.task('watch', function () {
	gulp.watch('public/css/dev/*.scss', ['styles']);
	gulp.watch('public/js/dev/*.js', ['scripts']);
});


gulp.task('default', ['watch']);	