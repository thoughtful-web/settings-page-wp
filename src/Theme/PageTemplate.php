<?php
/**
 * The file that facilitates page template file registration.
 *
 * @package    ThoughtfulWeb\LibraryWP
 * @subpackage Theme
 * @author     Zachary Kendall Watkins <watkinza@gmail.com>
 * @copyright  Zachary Kendall Watkins 2022
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0-or-later
 * @link       https://github.com/thoughtful-web/library-wp/blob/master/theme/pagetemplate.php
 * @since      0.1.0
 */

declare(strict_types=1);
namespace ThoughtfulWeb\LibraryWP\Theme;

/**
 * The class that registers page template file registration.
 *
 * @see   https://www.php.net/manual/en/language.oop5.basic.php
 * @since 0.1.0
 */
class PageTemplate {

	/**
	 * Plugin base directory.
	 *
	 * @var string $basedir The plugin base file directory.
	 */
	private $basedir;

	/**
	 * Default page template headers.
	 *
	 * @var array $default_headers {
	 *     An associative array of page template headers.
	 *
	 *     @type string $TemplateName The template name.
	 *     @type string $Description  The template description.
	 * }
	 */
	private $default_headers = array(
		'TemplateName' => 'Template Name Not Found',
		'Description'  => 'Description not found.',
	);

	/**
	 * Page template headers for registered page templates.
	 *
	 * @var array ...$template_headers {
	 *     An enumerable array of page template header configurations.
	 * }
	 */
	private $template_headers = array();

	/**
	 * Template file paths for hooks.
	 *
	 * @var array $template_paths {
	 *     A series of "filename.php":"Template Name" pairs.
	 * }
	 */
	private $template_paths;

	/**
	 * Construct the class.
	 *
	 * @see https://www.php.net/manual/en/function.is-array.php
	 * @see https://www.php.net/manual/en/function.array-key-exists.php
	 * @see https://www.php.net/manual/en/function.empty.php
	 * @see https://developer.wordpress.org/reference/classes/wp_error/
	 * @see https://www.php.net/manual/en/function.is-string.php
	 * @see https://developer.wordpress.org/reference/functions/plugin_dir_path/
	 *
	 * @todo Validate template file paths as part of the plugin's directory.
	 *
	 * @since  0.1.0
	 *
	 * @param string $root_file The full root plugin file path.
	 * @param array  $templates {
	 *     Page template data not able to be stored in the file header.
	 *
	 *     @key string $path The relative file path to the page template.
	 * }
	 * @return void
	 */
	public function __construct( $root_file, $templates ) {

		// Store the $this->basedir value.
		$this->basedir = plugin_dir_path( $root_file );

		// Store template file paths and the plugin's base directory.
		$this->template_headers = $this->get_file_data(
			$templates,
			$this->default_headers
		);

		// Store template file paths The WordPress Way for use in Core filters.
		$this->template_paths = $this->preprocess_template_paths( $templates, $this->template_headers );

		// Register templates.
		$this->add_template_hooks();

	}

	/**
	 * Get all template file headers.
	 *
	 * @param array $templates {
	 *     The page templates registered by this plugin.
	 *
	 *     @value string $path The path to the template file relative to the plugin's root directory.
	 * }
	 * @param array $default_headers The default page template file headers.
	 * @return void
	 */
	private function get_file_data( $templates, $default_headers ) {

		$data = array();
		foreach ( $templates as $file ) {
			$data[ $file ] = get_file_data( $this->basedir . '/' . $file, $default_headers );
		}
		return $data;

	}

	/**
	 * Set template_paths variable.
	 *
	 * @see https://www.php.net/manual/en/function.basename.php
	 *
	 * @since 0.1.0
	 *
	 * @param array $templates The plugin's template paths.
	 * @param array $template_headers The array of template file headers.
	 *
	 * @return void
	 */
	private function preprocess_template_paths( $templates, $template_headers ) {

		$template_paths = array();
		foreach ( $templates as $file ) {

			$name = $template_headers[ $file ]['TemplateName'];

			// Define the structure The WordPress Way.
			$template_paths[ $file ] = $name;

		}

		return $template_paths;

	}

	/**
	 * Register the page template in the WordPress dashboard.
	 *
	 * In WordPress v4.7 a change allowed page templates to be registered differently.
	 * The version check is for backwards compatibility.
	 *
	 * @see    https://www.php.net/manual/en/function.version-compare.php
	 * @see    https://www.php.net/manual/en/function.floatval.php
	 * @see    https://developer.wordpress.org/reference/functions/add_filter/
	 * @see    https://developer.wordpress.org/reference/hooks/theme_page_templates/
	 * @see    https://developer.wordpress.org/reference/hooks/admin_init/
	 * @see    https://developer.wordpress.org/reference/hooks/wp_insert_post_data/
	 * @see    https://developer.wordpress.org/reference/hooks/template_include/
	 * @since  0.1.0
	 * @return void
	 */
	private function add_template_hooks() {

		if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
			add_filter( 'page_attributes_dropdown_pages_args', array( $this, 'add_to_cache' ) );
		} else {
			add_filter( 'theme_page_templates', array( $this, 'add_to_cache_templates' ) );
		}

		add_filter( 'admin_init', array( $this, 'add_to_cache' ) );
		add_filter( 'wp_insert_post_data', array( $this, 'add_to_cache' ) );
		add_filter( 'template_include', array( $this, 'view_project_template' ) );

	}

	/**
	 * Add template to cache of theme page templates
	 *
	 * @see   https://developer.wordpress.org/reference/hooks/theme_page_templates/
	 * @see   https://www.php.net/manual/en/function.md5.php
	 * @see   https://developer.wordpress.org/reference/functions/get_theme_root/
	 * @see   https://developer.wordpress.org/reference/functions/get_stylesheet/
	 * @see   https://www.php.net/manual/en/function.empty.php
	 * @see   https://developer.wordpress.org/reference/functions/wp_cache_delete/
	 * @see   https://www.php.net/manual/en/function.array-merge.php
	 * @see   https://developer.wordpress.org/reference/functions/wp_cache_add/
	 * @since 0.1.0
	 *
	 * @param array $templates List of page templates.
	 *
	 * @return array
	 */
	public function add_to_cache_templates( $templates ) {

		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		if ( empty( $templates ) ) {
			$templates = array();
		}

		wp_cache_delete( $cache_key, 'themes' );

		$templates = array_merge( $templates, $this->template_paths );

		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $templates;

	}

	/**
	 * Add template to cache of theme page templates
	 *
	 * @see   https://developer.wordpress.org/reference/hooks/wp_insert_post_data/
	 * @see   https://www.php.net/manual/en/function.md5.php
	 * @see   https://developer.wordpress.org/reference/functions/get_theme_root/
	 * @see   https://developer.wordpress.org/reference/functions/get_stylesheet/
	 * @see   https://developer.wordpress.org/reference/functions/wp_get_theme/
	 * @see   https://developer.wordpress.org/reference/classes/wp_theme/get_page_templates/
	 * @see   https://www.php.net/manual/en/function.empty.php
	 * @see   https://developer.wordpress.org/reference/functions/wp_cache_delete/
	 * @see   https://www.php.net/manual/en/function.array-merge.php
	 * @see   https://developer.wordpress.org/reference/functions/wp_cache_add/
	 * @since 0.1.0
	 *
	 * @param array $atts Cache attributes.
	 *
	 * @return array
	 */
	public function add_to_cache( $atts ) {

		// Create a unique key for the cache item.
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Extract templates if using WordPress <4.7.
		$templates = wp_get_theme()->get_page_templates();

		if ( empty( $templates ) ) {
			$templates = array();
		}

		wp_cache_delete( $cache_key, 'themes' );

		$templates = array_merge( $templates, $this->template_paths );

		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	}

	/**
	 * Add template to template_include
	 *
	 * @see   https://developer.wordpress.org/reference/hooks/template_include/
	 * @see   https://developer.wordpress.org/reference/functions/get_post_meta/
	 * @see   https://www.php.net/manual/en/function.file-exists.php
	 * @see   https://developer.wordpress.org/reference/functions/esc_url/
	 * @since 0.1.0
	 *
	 * @param string $template Template.
	 *
	 * @return string
	 */
	public function view_project_template( $template ) {

		// Get global post.
		global $post;

		// Return template if post is empty.
		if ( ! $post ) {
			return $template;
		}

		// Stop the function if it's running for a template not defined for this plugin.
		$template_meta = get_post_meta(
			$post->ID,
			'_wp_page_template',
			true
		);
		if ( ! isset( $this->template_paths[ $template_meta ] ) ) {
			return $template;
		}

		$template = $this->basedir . $template_meta;

		// Just to be safe, we check if the file exist first.
		if ( file_exists( $template ) ) {
			return $template;
		}

		// Return template.
		return $template;

	}

}
