<?php
namespace ELUSHOP;

class TemplateLoader extends \Gamajo_Template_Loader {
	/**
	 * Prefix for filter names.
	 *
	 * @var string
	 */
	protected $filter_prefix = 'elu_shop';

	/**
	 * Directory name where custom templates for this plugin should be found in the theme.
	 *
	 * @var string
	 */
	protected $theme_template_directory = 'elu-shop';

	/**
	 * Reference to the root directory path of this plugin.
	 *
	 * Can either be a defined constant, or a relative reference from where the subclass lives.
	 *
	 * In this case, `MEAL_PLANNER_PLUGIN_DIR` would be defined in the root plugin file as:
	 *
	 * ~~~
	 * define( 'MEAL_PLANNER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	 * ~~~
	 *
	 * @var string
	 */
	protected $plugin_directory = ELU_SHOP_DIR;

	/**
	 * Directory name where templates are found in this plugin.
	 *
	 * Can either be a defined constant, or a relative reference from where the subclass lives.
	 *
	 * e.g. 'templates' or 'includes/templates', etc.
	 *
	 * @var string
	 */
	protected $plugin_template_directory = 'templates';

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
