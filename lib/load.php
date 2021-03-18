<?php
/**
 * Load API functions, register scripts and actions, etc.
 *
 * @package gutenberg
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

require_once __DIR__ . '/upgrade.php';

/**
 * Checks whether the Gutenberg experiment is enabled.
 *
 * @since 6.7.0
 *
 * @param string $name The name of the experiment.
 *
 * @return bool True when the experiment is enabled.
 */
function gutenberg_is_experiment_enabled( $name ) {
	$experiments = get_option( 'gutenberg-experiments' );
	return ! empty( $experiments[ $name ] );
}

// These files only need to be loaded if within a rest server instance
// which this class will exist if that is the case.
if ( class_exists( 'WP_REST_Controller' ) ) {
	/**
	* Start: Include for phase 2
	*/
	if ( ! class_exists( 'WP_REST_Sidebars_Controller' ) ) {
		require_once __DIR__ . '/class-wp-rest-sidebars-controller.php';
	}
	if ( ! class_exists( 'WP_REST_Widget_Types_Controller' ) ) {
		require_once __DIR__ . '/class-wp-rest-widget-types-controller.php';
	}
	if ( ! class_exists( 'WP_REST_Widgets_Controller' ) ) {
		require_once __DIR__ . '/class-wp-rest-widgets-controller.php';
	}
	if ( ! class_exists( 'WP_REST_Pattern_Directory_Controller' ) ) {
		require_once __DIR__ . '/class-wp-rest-pattern-directory-controller.php';
	}
	if ( ! class_exists( 'WP_REST_Menus_Controller' ) ) {
		require_once __DIR__ . '/class-wp-rest-menus-controller.php';
	}
	if ( ! class_exists( 'WP_REST_Menu_Items_Controller' ) ) {
		require_once __DIR__ . '/class-wp-rest-menu-items-controller.php';
	}
	if ( ! class_exists( 'WP_REST_Menu_Locations_Controller' ) ) {
		require_once __DIR__ . '/class-wp-rest-menu-locations-controller.php';
	}
	if ( ! class_exists( 'WP_Rest_Customizer_Nonces' ) ) {
		require_once __DIR__ . '/class-wp-rest-customizer-nonces.php';
	}
	if ( ! class_exists( 'WP_REST_Batch_Controller' ) ) {
		require_once __DIR__ . '/class-wp-rest-batch-controller.php';
	}
	if ( ! class_exists( 'WP_REST_Templates_Controller' ) ) {
		require_once __DIR__ . '/full-site-editing/class-wp-rest-templates-controller.php';
	}
	if ( ! class_exists( 'WP_REST_Block_Editor_Global_Styles_Settings_Controller' ) ) {
		require dirname( __FILE__ ) . '/class-wp-rest-block-editor-global-styles-settings-controller.php';
	}
	/**
	* End: Include for phase 2
	*/

	if ( ! class_exists( 'WP_REST_URL_Details_Controller' ) ) {
		require_once __DIR__ . '/class-wp-rest-url-details-controller.php';
	}

	require __DIR__ . '/rest-api.php';
}

if ( ! class_exists( 'WP_Widget_Block' ) ) {
	require_once __DIR__ . '/class-wp-widget-block.php';
}

require_once __DIR__ . '/widgets-page.php';

require __DIR__ . '/compat.php';
require __DIR__ . '/utils.php';
require __DIR__ . '/editor-settings.php';

if ( ! class_exists( 'WP_Block_Template ' ) ) {
	require __DIR__ . '/full-site-editing/class-wp-block-template.php';
}

// These are used by some FSE features
// as well as global styles.
require __DIR__ . '/class-wp-theme-json.php';
require __DIR__ . '/class-wp-theme-json-resolver.php';

require __DIR__ . '/full-site-editing/full-site-editing.php';
require __DIR__ . '/full-site-editing/block-templates.php';
require __DIR__ . '/full-site-editing/default-template-types.php';
require __DIR__ . '/full-site-editing/templates-utils.php';
require __DIR__ . '/full-site-editing/page-templates.php';
require __DIR__ . '/full-site-editing/templates.php';
require __DIR__ . '/full-site-editing/template-parts.php';
require __DIR__ . '/full-site-editing/template-loader.php';
require __DIR__ . '/full-site-editing/edit-site-page.php';
require __DIR__ . '/full-site-editing/edit-site-export.php';

require __DIR__ . '/blocks.php';
require __DIR__ . '/block-patterns.php';
require __DIR__ . '/client-assets.php';
require __DIR__ . '/demo.php';
require __DIR__ . '/widgets.php';
require __DIR__ . '/widgets-customize.php';
require __DIR__ . '/navigation.php';
require __DIR__ . '/navigation-page.php';
require __DIR__ . '/experiments-page.php';
require __DIR__ . '/global-styles.php';
require __DIR__ . '/query-utils.php';

require __DIR__ . '/block-supports/generated-classname.php';
require __DIR__ . '/block-supports/colors.php';
require __DIR__ . '/block-supports/align.php';
require __DIR__ . '/block-supports/typography.php';
require __DIR__ . '/block-supports/custom-classname.php';
require __DIR__ . '/block-supports/border.php';
