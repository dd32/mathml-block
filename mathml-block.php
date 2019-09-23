<?php
/**
 * Plugin Name:       MathML block
 * Description:       Display MathML formulas.
 * Version:           1.1.1
 * Requires at least: 5.0.0
 * Tested up to:      5.2.0
 * Requires PHP:      5.4
 * Stable tag:        5.2.0
 * Author:            adamsilverstein
 * Author URI:        http://tunedin.net
 * License:           GPLv2 or later
 * GitHub Plugin URI: https://github.com/adamsilverstein
 *
 * @package mathml-block
 */
namespace MathMLBlock;

 /**
  * Enqueue the admin JavaScript assets.
  */
function mathml_block_enqueue_scripts() {

	wp_enqueue_script(
		'mathml-block',
		plugin_dir_url( __FILE__ ) . 'dist/mathml-block.js',
		array( 'wp-blocks', 'wp-i18n', 'wp-editor' ),
		'',
		true
	);

	wp_enqueue_script(
		'mathjax',
		plugin_dir_url( __FILE__ ) . 'vendor/MathJax/MathJax.js?config=TeX-MML-AM_CHTML'
	);

}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\mathml_block_enqueue_scripts' );

// Maka JavaScript translatable.
function mathml_set_up_js_translations() {
	wp_set_script_translations( 'mathml-block', 'mathml-block' );
}
add_action( 'init', __NAMESPACE__ . '\mathml_set_up_js_translations' );

/**
 * Potentially enqueue the front end mathjax script, if any mathml blocks are detected in the content.
 */
function potentially_add_front_end_mathjax_script() {
	global $post;

	// Only apply on singular pages.
	if ( ! is_singular() ) {
		return;
	}

	// Check the content for mathml blocks.
	$has_mathml_block = strpos( $post->post_content, 'wp:mathml/mathmlblock' );
	$has_mathml_inline = strpos( $post->post_content, '<math>' );
	if ( false === $has_mathml_block ) {
		return;
	}

	// Enqueue the MathJax script for front end formula display.
	wp_register_script( 'mathjax', plugin_dir_url( __FILE__ ) . 'vendor/MathJax/MathJax.js?config=TeX-MML-AM_CHTML' );
	wp_enqueue_script( 'mathjax' );

}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\potentially_add_front_end_mathjax_script' );