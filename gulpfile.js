var gulp = require('gulp');
var concat = require('gulp-concat');
var cleanCSS = require('gulp-clean-css');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var uglify = require('gulp-uglify');
var watch = require('gulp-watch');

/***** CONFIG PARAMS *****/
var paths = {
    frontend_css : [
        'frontend/web/css/fonts.scss',
        'frontend/web/css/site.css',
        'frontend/web/css/main.scss',
    ],
    frontend_js : [
        'frontend/web/js/*.js'
    ]
};

/***** COMPILE FRONT CSS *****/
gulp.task('build-frontend-css', function() {
    return gulp.src(paths.frontend_css)
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(concat('styles.min.css'))
        .pipe(gulp.dest('frontend/web/css/compressed/'));
});

/***** COMPILE FRONT JS *****/
gulp.task('build-frontend-js', function() {
    return gulp.src(paths.frontend_js)
        .pipe(uglify())
        .pipe(concat('script.min.js'))
        .pipe(gulp.dest('frontend/web/js/compressed/'));
});

/***** DEFAULT *****/
gulp.task('default', [
    'build-frontend-css',
    'build-frontend-js'
]);

/***** WATCH *****/
gulp.task('watch', function () {
    gulp.watch(paths.frontend_css , ['build-frontend-css']);
    gulp.watch(paths.frontend_js , ['build-frontend-js']);
});
