<?php // @codingStandardsIgnoreLine
/**
 * Plugin Name:     Gutena Star Ratings
 * Description:     Gutena Star Ratings
 * Version:         1.0.1
 * Author:          Gutena
 * Author URI:      https://wpexperts.io/
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     gutena-star-ratings
 *
 * @package         gutena-star-ratings
 */

defined( 'ABSPATH' ) || exit;

/**
 * Abort if the class is already exists.
 */
if ( ! class_exists( 'Gutena_Star_Ratings' ) ) {

	/**
	 * Gutena Star Ratings class.
	 *
	 * @class Main class of the plugin.
	 */
	class Gutena_Star_Ratings {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		public $version = '1.0.1';

		/**
		 * Instance of this class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		protected static $instance;

		/**
		 * Get the singleton instance of this class.
		 *
		 * @since 1.0.0
		 * @return Gutena_Star_Ratings
		 */
		public static function get() {
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'init', [ $this, 'register' ] );
			add_filter( 'block_categories_all', [ $this, 'register_category' ], 10, 2 );
		}

		/**
		 * Register required functionalities.
		 */
		public function register() {
			// Register blocks.
			register_block_type( __DIR__ . '/build' );
		}

		/**
		 * Generate dynamic styles
		 *
		 * @param array $styles
		 * @return string
		 */
		private function render_css( $styles ) {
			$style = [];
			foreach ( (array) $styles as $key => $value ) {
				$style[] = $key . ': ' . $value;
			}

			return join( ';', $style );
		}

		/**
		 * Register block category.
		 */
		public function register_category( $block_categories, $editor_context ) {
			$fields = wp_list_pluck( $block_categories, 'slug' );
			
			if ( ! empty( $editor_context->post ) && ! in_array( 'gutena', $fields, true ) ) {
				array_push(
					$block_categories,
					[
						'slug'  => 'gutena',
						'title' => __( 'Gutena', 'gutena-star-ratings' ),
					]
				);
			}

			return $block_categories;
		}
	}
}

/**
 * Check the existance of the function.
 */
if ( ! function_exists( 'Gutena_Star_Ratings_init' ) ) {
	/**
	 * Returns the main instance of Gutena_Star_Ratings to prevent the need to use globals.
	 *
	 * @return Gutena_Star_Ratings
	 */
	function Gutena_Star_Ratings_init() {
		return Gutena_Star_Ratings::get();
	}

	// Start it.
	Gutena_Star_Ratings_init();
}

// Gutena Ecosystem init.
if ( file_exists( __DIR__ . '/includes/gutena/gutena-ecosys-onboard/gutena-ecosys-onboard.php' ) ) {
	require_once  __DIR__ . '/includes/gutena/gutena-ecosys-onboard/gutena-ecosys-onboard.php';
}