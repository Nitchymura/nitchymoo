// gulpfile.js
const gulp = require('gulp');
const browserSync = require('browser-sync').create();
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const terser = require('gulp-terser');
const imagemin = require('gulp-imagemin');
const mozjpeg = require('imagemin-mozjpeg');
const pngquant = require('imagemin-pngquant');
const webp = require('gulp-webp');
const newer = require('gulp-newer');
const sourcemaps = require('gulp-sourcemaps');
const gulpIf = require('gulp-if');
const del = require('del');

const isProd = process.env.NODE_ENV === 'production';

const paths = {
  html:   { src: 'src/html/**/*.html',   dest: 'dist' },
  styles: { src: 'src/scss/**/*.scss',   dest: 'dist/css' },
  scripts:{ src: 'src/js/**/*.js',       dest: 'dist/js' },
  images: { src: 'src/images/**/*.{png,jpg,jpeg,svg,gif}', dest: 'dist/images' }
};

// クリーン
function clean() {
  return del(['dist']);
}

// HTML コピー
function html() {
  return gulp.src(paths.html.src)
    .pipe(newer(paths.html.dest))
    .pipe(gulp.dest(paths.html.dest))
    .pipe(browserSync.stream());
}

// Sass → CSS（開発はソースマップ、本番はminify）
function styles() {
  return gulp.src('src/scss/style.scss', { allowEmpty: true })
    .pipe(gulpIf(!isProd, sourcemaps.init()))
    .pipe(sass.sync({ outputStyle: 'expanded' }).on('error', sass.logError))
    .pipe(postcss([
      autoprefixer(),
      ...(isProd ? [cssnano()] : [])
    ]))
    .pipe(gulpIf(!isProd, sourcemaps.write('.')))
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(browserSync.stream());
}

// JS 圧縮（本番のみminify／開発はそのまま + ソースマップ）
function scripts() {
  return gulp.src(paths.scripts.src, { allowEmpty: true })
    .pipe(gulpIf(!isProd, sourcemaps.init()))
    .pipe(gulpIf(isProd, terser()))
    .pipe(gulpIf(!isProd, sourcemaps.write('.')))
    .pipe(gulp.dest(paths.scripts.dest))
    .pipe(browserSync.stream());
}

// 画像最適化（差分のみ処理）
function images() {
  return gulp.src(paths.images.src, { allowEmpty: true })
    .pipe(newer(paths.images.dest))
    .pipe(imagemin([
      mozjpeg({ quality: 80 }),      // JPEG最適化
      pngquant({ quality: [0.7, 0.85] }), // PNG最適化
      imagemin.svgo(),
      imagemin.gifsicle({ interlaced: true })
    ], { verbose: true }))
    .pipe(gulp.dest(paths.images.dest))
    .pipe(browserSync.stream());
}

// 追加: WebP 生成（オプション）
function imagesWebp() {
  return gulp.src('src/images/**/*.{png,jpg,jpeg}', { allowEmpty: true })
    .pipe(newer({ dest: paths.images.dest, ext: '.webp' }))
    .pipe(webp({ quality: 80 }))
    .pipe(gulp.dest(paths.images.dest));
}

// ローカルサーバ & 自動リロード
function serve() {
  browserSync.init({
    server: 'dist',
    open: false,     // 自動でブラウザを開きたければ true
    notify: false
  });

  gulp.watch(paths.html.src, html);
  gulp.watch(paths.styles.src, styles);
  gulp.watch(paths.scripts.src, scripts);
  gulp.watch(paths.images.src, gulp.series(images, imagesWebp));
}

const build = gulp.series(
  clean,
  gulp.parallel(html, styles, scripts, images, imagesWebp)
);

const dev = gulp.series(build, serve);

exports.clean = clean;
exports.html = html;
exports.styles = styles;
exports.scripts = scripts;
exports.images = images;
exports.webp = imagesWebp;
exports.build = build;
exports.dev = dev;
exports.default = dev;
