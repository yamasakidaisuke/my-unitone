<?php

/**
 * Plugin name: My unitone
 * Description: 不具合が生じた場合、このプラグインをオフにすることでテーマ側の問題点と切り分けできます。
 * Version: 0.0.1
 *
 * @package my-unitone
 * @author yamasakidaisuke
 * @license GPL-2.0+
 */

/**
 * unitone 以外のテーマを利用している場合は有効化してもカスタマイズが反映されないようにする
 */
$theme = wp_get_theme();
if ( 'unitone' !== $theme->template ) {
	return;
}

/*  おまじない  */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Directory url of this plugin
 *
 * @var string
 */
define( 'MY_UNITONE_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Directory path of this plugin
 *
 * @var string
 */
define( 'MY_UNITONE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * Display message in console.log if this plugin is enabled.
 */
add_action(
	'wp_footer',
	function () {
		if ( is_user_logged_in() ) :
			?>
			<script>console.log( 'My unitone plugin is active' );</script>
			<?php
		endif;
	}
);

//CSS JSの読み込み 第3引数の11はCSSを読み込む順番を指定
add_action('wp_enqueue_scripts', 'mut_enqueue_style_script', 11 ,1);
function mut_enqueue_style_script()
	{
	if (is_front_page() || is_home()) {
		wp_enqueue_style(
		  'swiper_style',
		  MY_UNITONE_URL . '/css/swiper-bundle.min.css',
		  [],
		  filemtime(MMY_UNITONE_PATH . '/css/swiper-bundle.min.css')
		);
		wp_enqueue_style(
		  'my-swiper_style',
		  MY_UNITONE_URL . '/css/my-swiper.css',
		  [],
		  filemtime(MMY_UNITONE_PATH . '/css/my-swiper.css')
		);
		wp_enqueue_script(
		  'swiper_scripts',
		  MY_UNITONE_URL . '/js/swiper-bundle.min.js',
		  ['jquery'],
		  filemtime(MMY_UNITONE_PATH . '/js/swiper-bundle.min.js'),
		  true
		);
		wp_enqueue_script(
		  'my-swiper_scripts',
		  MY_UNITONE_URL . '/js/my-swiper.js',
		  ['jquery'],
		  filemtime(MMY_UNITONE_PATH . '/js/my-swiper.js'),
		  true
		);
	}
	wp_enqueue_style(
		'mut_style',
		MY_UNITONE_URL . '/css/myplugin_css.css',
		[],
		filemtime(MY_UNITONE_PATH . '/css/myplugin_css.css')
	);

	wp_enqueue_script(
		'mut_scripts',
		MY_UNITONE_URL . '/js/myplugin_js.js',
		['jquery'],
		filemtime(MY_UNITONE_PATH . '/js/myplugin_js.js'),
		true
	);
}

//ゴミ箱内での自動削除を停止する
add_action('init', 'remove_schedule_delete');
function remove_schedule_delete()
{
	remove_action('wp_scheduled_delete', 'wp_scheduled_delete');
}

//画像アップロード時の自動生成をすべて停止する方法（Ver 5.3対応）
function disable_image_sizes($new_sizes)
{
	unset($new_sizes['thumbnail']);
	unset($new_sizes['medium']);
	unset($new_sizes['large']);
	unset($new_sizes['medium_large']);
	unset($new_sizes['1536x1536']);
	unset($new_sizes['2048x2048']);
	return $new_sizes;
}
add_filter('intermediate_image_sizes_advanced', 'disable_image_sizes');

add_filter('big_image_size_threshold', '__return_false');

// my-unitoneのCSSを編集画面でも反映する
add_action('after_setup_theme', 'my_editor_style_setup');
function my_editor_style_setup()
{
  add_theme_support( 'editor-styles' );
  add_editor_style(MY_UNITONE_URL . '/css/myplugin_css.css' );
}
