// gulpfile.js (ESM)
import gulp from 'gulp';
import bs from 'browser-sync';
import gulpSass from 'gulp-sass';
import * as dartSass from 'sass'; // ← 推奨の名前空間 import
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import terser from 'gulp-terser';
import imagemin from 'gulp-imagemin';
import imageminMozjpeg from 'imagemin-mozjpeg';
import imageminPngquant from 'imagemin-pngquant';
import imageminSvgo from 'imagemin-svgo';
import imageminGifsicle from 'imagemin-gifsicle';
import webp from 'gulp-webp';
import newer from 'gulp-newer';
import sourcemaps from 'gulp-sourcemaps';
import gulpIf from 'gulp-if';
import { deleteAsync } from 'del';

const browserSync = bs.create();
const sass = gulpSass(dartSass);
const isProd = process.env.NODE_ENV === 'production';

const paths = {
  styles: { src: 'src/scss/**/*.scss', entry: 'src/scss/style.scss', dest: 'public/css' },
  scripts:{ src: 'src/js/**/*.js',     dest: 'public/js' },
  images: { src: 'src/images/**/*.{png,jpg,jpeg,svg,gif}', dest: 'public/images' }
};

// ---- tasks ----
export function clean() {
  return deleteAsync(['public/css', 'public/js']); // images は消さない
}

export function styles() {
  return gulp.src('src/scss/*.scss', { allowEmpty: true }) // entry ではなく *.scss
    .pipe(gulpIf(!isProd, sourcemaps.init()))
    .pipe(sass.sync({ outputStyle: 'expanded' }).on('error', sass.logError))
    .pipe(postcss([autoprefixer(), ...(isProd ? [cssnano()] : [])]))
    .pipe(gulpIf(!isProd, sourcemaps.write('.')))
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(browserSync.stream());
}

export function scripts() {
  return gulp.src(paths.scripts.src, { allowEmpty: true })
    .pipe(gulpIf(!isProd, sourcemaps.init()))
    .pipe(gulpIf(isProd, terser()))
    .pipe(gulpIf(!isProd, sourcemaps.write('.')))
    .pipe(gulp.dest(paths.scripts.dest))
    .pipe(browserSync.stream());
}

export function images() {
  return gulp.src(paths.images.src, { allowEmpty: true })
    .pipe(newer(paths.images.dest))
    .pipe(imagemin([
      imageminMozjpeg({ quality: 80 }),
      imageminPngquant({ quality: [0.7, 0.85] }),
      imageminSvgo(),
      imageminGifsicle({ interlaced: true })
    ], { verbose: true }))
    .pipe(gulp.dest(paths.images.dest))
    .pipe(browserSync.stream());
}

export function imagesWebp() {
  return gulp.src('src/images/**/*.{png,jpg,jpeg}', { allowEmpty: true })
    .pipe(newer({ dest: paths.images.dest, ext: '.webp' }))
    .pipe(webp({ quality: 80 }))
    .pipe(gulp.dest(paths.images.dest));
}

export function serve() {
  browserSync.init({
    proxy: 'http://localhost/nitchymoo/public', // XAMPP の Laravel をプロキシ
    open: false,
    notify: false
  });

  gulp.watch(paths.styles.src, styles);
  gulp.watch(paths.scripts.src, scripts);
  gulp.watch(paths.images.src, gulp.series(images, imagesWebp));
  gulp.watch(['resources/views/**/*.blade.php', 'public/**/*']).on('change', browserSync.reload);
}

// ---- composed ----
export const build = gulp.series(clean, gulp.parallel(styles, scripts, images, imagesWebp));
export const dev   = gulp.series(build, serve);
export default dev;
