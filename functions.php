<?php
/**
 * Theme bootstrap.
 *
 * @package FSE
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports and editor features.
 */
function fse_setup() {
	load_theme_textdomain( 'fse-commerce', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'fse_setup' );

/**
 * Register local pattern categories.
 */
function fse_register_pattern_categories() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	register_block_pattern_category(
		'fse',
		array(
			'label' => __( 'FSE Theme', 'fse-commerce' ),
		)
	);

	register_block_pattern_category(
		'fse-store',
		array(
			'label' => __( 'FSE Store', 'fse-commerce' ),
		)
	);
}
add_action( 'init', 'fse_register_pattern_categories' );

/**
 * Register custom block styles.
 */
function fse_register_block_styles() {
	if ( ! function_exists( 'register_block_style' ) ) {
		return;
	}

	register_block_style(
		'core/button',
		array(
			'name'         => 'fse-commerce-quiet-outline',
			'label'        => __( 'Quiet Outline', 'fse-commerce' ),
			'inline_style' => '
				.wp-block-button.is-style-fse-commerce-quiet-outline .wp-block-button__link {
					background: transparent;
					color: var(--wp--preset--color--contrast);
					border: 1px solid var(--wp--preset--color--border);
					box-shadow: none;
				}

				.wp-block-button.is-style-fse-commerce-quiet-outline .wp-block-button__link:hover,
				.wp-block-button.is-style-fse-commerce-quiet-outline .wp-block-button__link:focus {
					background: var(--wp--preset--color--surface);
					color: var(--wp--preset--color--contrast);
					border-color: var(--wp--preset--color--contrast);
				}
			',
		)
	);
}
add_action( 'init', 'fse_register_block_styles' );

/**
 * Get the current theme version.
 *
 * @return string
 */
function fse_get_theme_version() {
	static $version = null;

	if ( null === $version ) {
		$version = wp_get_theme()->get( 'Version' );
	}

	return $version ?: '1.0.0';
}

/**
 * Get the Vite dev server origin.
 *
 * @return string
 */
function fse_get_vite_origin() {
	$origin = apply_filters( 'fse_vite_dev_server', 'http://localhost:5173' );

	return untrailingslashit( $origin );
}

/**
 * Check whether the Vite dev server is available.
 *
 * @return bool
 */
function fse_is_vite_dev_server_running() {
	static $is_running = null;

	if ( null !== $is_running ) {
		return $is_running;
	}

	$response = wp_remote_get(
		fse_get_vite_origin() . '/@vite/client',
		array(
			'timeout'   => 0.35,
			'sslverify' => false,
		)
	);

	$is_running = ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response );

	return $is_running;
}

/**
 * Return the parsed Vite manifest.
 *
 * @return array<string, array<string, mixed>>
 */
function fse_get_vite_manifest() {
	static $manifest = null;

	if ( null !== $manifest ) {
		return $manifest;
	}

	$manifest_path = get_theme_file_path( 'dist/manifest.json' );

	if ( ! file_exists( $manifest_path ) ) {
		$manifest = array();
		return $manifest;
	}

	$decoded = json_decode( (string) file_get_contents( $manifest_path ), true );
	$manifest = is_array( $decoded ) ? $decoded : array();

	return $manifest;
}

/**
 * Lookup a Vite manifest entry by source path.
 *
 * @param string $entry Entry key from the Vite manifest.
 * @return array<string, mixed>|null
 */
function fse_get_vite_manifest_entry( $entry ) {
	$manifest = fse_get_vite_manifest();

	return $manifest[ $entry ] ?? null;
}

/**
 * Enqueue shared theme styles for the front end and editor.
 */
function fse_enqueue_theme_styles() {
	if ( fse_is_vite_dev_server_running() ) {
		wp_enqueue_style( 'fse-theme', fse_get_vite_origin() . '/assets/styles/main.css', array(), null );
		return;
	}

	$style_entry = fse_get_vite_manifest_entry( 'assets/styles/main.css' );

	if ( empty( $style_entry['file'] ) ) {
		return;
	}

	wp_enqueue_style(
		'fse-theme',
		get_theme_file_uri( 'dist/' . $style_entry['file'] ),
		array(),
		fse_get_theme_version()
	);
}
add_action( 'enqueue_block_assets', 'fse_enqueue_theme_styles' );

/**
 * Load the typography pairing used by the theme and editor.
 */
function fse_enqueue_font_faces() {
	wp_enqueue_style(
		'fse-fonts',
		'https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap',
		array(),
		null
	);
}
add_action( 'enqueue_block_assets', 'fse_enqueue_font_faces' );

/**
 * Add font preconnect hints.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type Resource relation type.
 * @return array
 */
function fse_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' !== $relation_type ) {
		return $urls;
	}

	$urls[] = 'https://fonts.googleapis.com';
	$urls[] = array(
		'href'        => 'https://fonts.gstatic.com',
		'crossorigin' => 'anonymous',
	);

	return $urls;
}
add_filter( 'wp_resource_hints', 'fse_resource_hints', 10, 2 );
