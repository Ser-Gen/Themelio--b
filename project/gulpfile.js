var $ = require('gulp');
var plugins = require('gulp-load-plugins');
var autoprefixer = require('autoprefixer-stylus');
var pngcrush = require('imagemin-pngcrush');

var cfg = {
	css : {
		SRC : 'assets/css/src/',
		PATH : 'assets/css/'
	},

	js : {
		SRC : 'assets/js/plugins/',
		PATH : 'assets/js/'
	},

	img : {
		SRC : 'assets/img/src/',
		PATH : 'assets/img'
	},

	b: 'b/',
	lib: 'b/lib/'
}


// компиляция силей
$.task('stylus', function () {
	$.src(cfg.css.SRC +'*.styl')
		.pipe(plugins().stylus({
			use: autoprefixer({
				browsers: ['last 5 versions', 'ie 8', 'ie 9']
			})
		}))
		.on('error', console.log)
		.pipe($.dest(cfg.css.PATH)).pipe(plugins().livereload());
});


// склейка скриптов
$.task('concat', function() {

	// один из файлов подгружается до содержимого документа
	$.src([
		cfg.js.SRC +'modernizr.js',
		cfg.js.SRC +'respond.js'
	])
	.pipe(plugins().concat('prepend.js'))
	.pipe($.dest(cfg.js.PATH));

	// другой — после
	$.src([
		cfg.js.SRC +'jquery.fancybox.js',
		cfg.js.SRC +'jquery.placeholderPolyfill.js',
		cfg.js.SRC +'jquery.tipTip.js',
	])
	.pipe(plugins().concat('plugins.js'))
	.pipe($.dest(cfg.js.PATH));
});


// склейка скриптов компонентов
$.task('concat--b', function() {
	$.src(cfg.lib +'**/*.js')
	.pipe(plugins().concat('b.js'))
	.pipe($.dest(cfg.b));
});


// оптимизация изображений
$.task('imageo', function () {
	return $.src(cfg.img.SRC +'**/*')
		.pipe(plugins().imagemin({
			progressive: true,
			svgoPlugins: [{removeViewBox: false}],
			use: [pngcrush()]
		}))
		.pipe($.dest(cfg.img.PATH));
});


// следить только за стилями
$.task('default', function () {
	$.watch(cfg.css.SRC +'**/*.styl', ['stylus']);
	$.watch(cfg.lib +'**/*.styl', ['stylus']);
	$.watch(cfg.lib +'**/*.js', ['concat--b']);
});
