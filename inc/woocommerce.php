<?php
/**
 * Add WooCommerce support
 *
 * @package stylewp
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'stylewp_woocommerce_support' );
if ( ! function_exists( 'stylewp_woocommerce_support' ) ) {
	/**
	 * Declares WooCommerce theme support.
	 */
	function stylewp_woocommerce_support() {
		add_theme_support( 'woocommerce' );

		// Add New Woocommerce 3.0.0 Product Gallery support.
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );

		// hook in and customizer form fields.
		add_filter( 'woocommerce_form_field_args', 'stylewp_wc_form_field_args', 10, 3 );
	}
}

// First unhook the WooCommerce content wrappers.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Then hook in your own functions to display the wrappers your theme requires.
add_action( 'woocommerce_before_main_content', 'stylewp_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'stylewp_woocommerce_wrapper_end', 10 );

  

if ( ! function_exists( 'stylewp_wc_form_field_args' ) ) {
	/**
	 * Filter hook function monkey patching form classes
	 * Author: Adriano Monecchi http://stackoverflow.com/a/36724593/307826
	 *
	 * @param string $args Form attributes.
	 * @param string $key Not in use.
	 * @param null   $value Not in use.
	 *
	 * @return mixed
	 */
	function stylewp_wc_form_field_args( $args, $key, $value = null ) {
		// Start field type switch case.
		switch ( $args['type'] ) {
			/* Targets all select input type elements, except the country and state select input types */
			case 'select':
				// Add a class to the field's html element wrapper - woocommerce
				// input types (fields) are often wrapped within a <p></p> tag.
				$args['class'][] = 'form-group';
				// Add a class to the form input itself.
				$args['input_class']       = array( 'form-control', 'input-lg' );
				$args['label_class']       = array( 'control-label' );
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
					// Add custom data attributes to the form input itself.
				);
				break;
			// By default WooCommerce will populate a select with the country names - $args
			// defined for this specific input type targets only the country select element.
			case 'country':
				$args['class'][]     = 'form-group single-country';
				$args['label_class'] = array( 'control-label' );
				break;
			// By default WooCommerce will populate a select with state names - $args defined
			// for this specific input type targets only the country select element.
			case 'state':
				// Add class to the field's html element wrapper.
				$args['class'][] = 'form-group';
				// add class to the form input itself.
				$args['input_class']       = array( '', 'input-lg' );
				$args['label_class']       = array( 'control-label' );
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
				);
				break;
			case 'password':
			case 'text':
			case 'email':
			case 'tel':
			case 'number':
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
			case 'textarea':
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
			case 'checkbox':
				$args['label_class'] = array( 'custom-control custom-checkbox' );
				$args['input_class'] = array( 'custom-control-input', 'input-lg' );
				break;
			case 'radio':
				$args['label_class'] = array( 'custom-control custom-radio' );
				$args['input_class'] = array( 'custom-control-input', 'input-lg' );
				break;
			default:
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
		} // End of switch ( $args ).
		return $args;
	}
}

if ( ! is_admin() && ! function_exists( 'wc_review_ratings_enabled' ) ) {
	/**
	 * Check if reviews are enabled.
	 *
	 * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
	 *
	 * @return bool
	 */
	function wc_reviews_enabled() {
		return 'yes' === get_option( 'woocommerce_enable_reviews' );
	}

	/**
	 * Check if reviews ratings are enabled.
	 *
	 * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
	 *
	 * @return bool
	 */
	function wc_review_ratings_enabled() {
		return wc_reviews_enabled() && 'yes' === get_option( 'woocommerce_enable_review_rating' );
	}
}