var gulp = require('gulp'),
	sass = require('gulp-sass'),
	prefix = require('gulp-autoprefixer'),
	minifyCSS = require('gulp-minify-css'),
	concat = require('gulp-concat'),
	uglify = require('gulp-uglify');

gulp.task('styles', function () {
    gulp.src('assets/css/dev/*.scss')
        .pipe(sass())
        .pipe(prefix("last 1 version", "> 1%", "ie 8", "ie 7"))
        .pipe(minifyCSS())
        .pipe(gulp.dest('assets/css'))
});

/*
gulp.task('scripts', function () {
	gulp.src([	'./public/js/dev/libraries/jquery.js', 
				'./public/js/dev/bootstrap/button.js', 
				'./public/js/dev/bootstrap/dropdown.js',
				'./public/js/dev/custom.js'
			])
		.pipe(concat("scripts.js"))
		.pipe(uglify())
		.pipe(gulp.dest('public/js'))
});
*/

gulp.task('watch', function () {
	gulp.watch('assets/css/dev/*.scss', ['styles']);
	//gulp.watch('public/js/dev/*.js', ['scripts']);
});


gulp.task('default', ['watch']);	