import {src, dest, watch, parallel, series, lastRun} from 'gulp';
import del from 'del';
import sass from 'gulp-sass';
import cleanCss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import yargs from 'yargs';
import browserSync from 'browser-sync';
import cheerio from 'gulp-cheerio';
import svgmin from 'gulp-svgmin';
import svgstore from 'gulp-svgstore';
import rename from 'gulp-rename';
import autoprefixer from 'gulp-autoprefixer';
import sourcemaps from 'gulp-sourcemaps';
import wpPot from 'gulp-wp-pot';

// Set project paths
const paths = {
  styles: {
    src: 'assets/scss/**/*.scss',
    dist: 'assets/css',
  },
  scripts: {
    src: 'assets/js/src/*.js',
    dist: 'assets/js'
  },
  icons: {
    src: 'assets/icons/src/*.svg',
    dist: 'assets/icons'
  },
  assets: {
    src: ['src/**/*', '!src/{icons,js,scss}/**/*'],
    dist: 'assets'
  }
}


// Recognise `--production` argument
const argv = yargs.argv;
const production = !!argv.production;


/**
 * Compile Sass and run stylesheet through Autoprefixer and minify.
 *
 * https://www.npmjs.com/package/gulp-sass
 * https://www.npmjs.com/package/gulp-autoprefixer
 * https://www.npmjs.com/package/gulp-clean-css
 */
export const buildStyles = () => src(paths.styles.src)
  .pipe(sourcemaps.init())
  .pipe(sass.sync({
    outputStyle: 'expanded'
  })
  .on('error', sass.logError))
  .pipe(autoprefixer({
    browsers: [
      'last 2 versions',
      'ie >= 10',
      'ios >= 7'
    ]
  }))
  .pipe(gulpif(production, cleanCss({ compatibility: 'ie10' })))
  .pipe(gulpif(!production, sourcemaps.write()))
  .pipe(dest(paths.styles.dist))
  .pipe(browserSync.stream());


/**
 * Minify, concatenate, and clean SVG icons.
 *
 * https://www.npmjs.com/package/gulp-svgmin
 * https://www.npmjs.com/package/gulp-svgstore
 * https://www.npmjs.com/package/gulp-cheerio
 */
export const buildIcons = () => src(paths.icons.src)
  .pipe(svgmin())
  .pipe(rename({ 'prefix': 'icon-' }))
  .pipe(svgstore({'inlineSvg': true}))
  .pipe(cheerio({
    'run': function($, file) {
      $('svg').attr('style', 'display:none');
      $('[fill]').removeAttr('fill');
    },
    'parserOptions': {'xmlMode': true}
  }))
  .pipe(dest(paths.icons.dist))
  .pipe(browserSync.stream());


/**
 * Scan the theme and create a POT file.
 *
 * https://www.npmjs.com/package/gulp-wp-pot
 */
export const translate = () => src('**/*.php')
  .pipe(wpPot({
    domain: 'rafter',
    package: 'Rafter'
  }))
  .pipe(dest('languages/rafter.pot'))
  .pipe(browserSync.stream());


// Watch Task
export const watchFiles = () => {
  watch('**/*.php', series(browserSync.reload));
  watch(paths.styles.src, series(buildStyles));
  watch(paths.icons.src, series(buildIcons));
};


// Start BrowserSync server
function server(done) {
  browserSync.init({
    proxy: 'theme-development.dev'
  });
  done();
}

// Build Task
export const build = series(parallel(buildStyles, buildIcons, translate));

// Serve Task
export const serve = series(parallel(buildStyles, buildIcons), parallel(watchFiles, server));

// Default task
export default build;
