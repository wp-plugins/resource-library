<?php

namespace MightyDev\WordPress\Plugin;

class ResourcePluginController extends PluginController
{
	protected $taxonomy = 'groups';

	protected $default_pages_option_name = 'mdresourcelib_default_pages';

	public function init_collection_interface()
	{
		add_action( 'init', array( $this, 'create_post_type' ) );
		add_action( 'init', array( $this, 'create_taxonomy' ) );
		add_action( 'init', array( $this, 'create_default_terms' ) );
		add_action( 'admin_menu', array ( $this, 'menu_position' ) );
	}

	public function menu_position()
	{
		\MightyDev\Settings\setMenuPosition( 'mdresourcelib', '99.0300' );
	}

	public function create_post_type()
	{
		$labels = array(
			'name'               => _x( 'Resources', 'post type general name', 'resource-library' ),
			'singular_name'      => _x( 'Resource', 'post type singular name', 'resource-library' ),
			'add_new'            => _x( 'Add New', 'press release', 'resource-library' ),
			'add_new_item'       => __( 'Add New Resource', 'resource-library' ),
			'new_item'           => __( 'New Page', 'resource-library' ),
			'edit_item'          => __( 'Edit Resource', 'resource-library' ),
			'view_item'          => __( 'View Resource', 'resource-library' ),
			'all_items'          => __( 'All Resources', 'resource-library' ),
			'not_found'          => __( 'No resource found.', 'resource-library' ),
			'not_found_in_trash' => __( 'No resource found in Trash.', 'resource-library' )
		);
		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'show_ui'            => true, // required because public == false
			'show_in_menu'       => true,
			'show_in_admin_bar'  => true,
			'rewrite'            => false,
			'supports'           => array( 'title', 'revisions' ), // editor
			'menu_icon'          => 'dashicons-sos',
		);
		register_post_type( 'mdresourcelib', $args );
	}

	public function create_taxonomy()
	{
		$args = array(
			'hierarchical'          => true,
			'public'                => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => false,
			'show_tagcloud'         => false,
			'show_admin_column'     => true,
			'rewrite'               => false,
		);
		register_taxonomy( $this->taxonomy, 'mdresourcelib', $args );
	}

	public function create_default_terms()
	{
		// todo: run these once
		$this->create_term( 'Whitepapers', $this->taxonomy );
		$this->create_term( 'Datasheets', $this->taxonomy );
	}

	protected function create_term( $term, $taxonomy )
	{
		$result = term_exists( $term, $taxonomy );
		if ( ! is_array( $result ) ) {
			wp_insert_term( $term, $taxonomy );
		}
	}

	public function create_item_interface( \WPAlchemy\Factory $factory )
	{
		$options = array(
			'types' => array( 'mdresourcelib' ),
			'lock' => 'before_post_title',
		);

		$factory->createMetaBox( '_mdresourcelib_doc', 'Document', __DIR__ . '/../file-meta.php', $options );

		$options = array(
			'types' => array( 'mdresourcelib' ),
		);

		$factory->createMetaBox( '_mdresourcelib_info', 'Info', __DIR__ . '/../info-meta.php', $options );
	}

	public function init_admin_styles_and_scripts()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles_and_scripts' ) );
	}

	public function admin_styles_and_scripts()
	{
		wp_enqueue_media();
		wp_enqueue_style( 'mdresourcelib-admin', plugins_url( 'inc/admin.css', $this->plugin_file, array(), '0.1.2' ) );
		wp_enqueue_script( 'mdresourcelib-admin', plugins_url( 'inc/admin.js', $this->plugin_file ), array( 'jquery' ), '0.1.2', true );
	}

	public function init_shortcodes()
	{
		add_shortcode( 'resource-library', array( $this, 'shortcode' ) );
	}

	public $template;

	public function use_template_engine( $template )
	{
		$this->template = $template;
	}

	public function shortcode( $atts, $content = null )
	{
		$default_atts = array(
			'display' => 'default',
			'class' => '',
		);
		extract( shortcode_atts( $default_atts, $atts ) );
		$context = array();
		$terms = get_terms( $this->taxonomy );
		if ( ! empty( $terms ) ) {
			$context['terms'] = array();
			foreach( $terms as $term ) {
				$tax_query = array(
					'taxonomy' => $this->taxonomy,
					'field'    => 'slug',
					'terms'    => $term->slug,
				);
				$posts = get_posts( array(
					'post_type' => 'mdresourcelib',
					'posts_per_page' => '9999',
					'tax_query' => array( $tax_query )
				) );
				$docs = array();
				foreach( $posts as $post ) {
					array_push( $docs, $this->get_resource_meta( $post ) );
				}
				array_push( $context['terms'], array(
					'id' => $term->term_id,
					'name' => $term->name,
					'docs' => $docs,
				));
			}
		} else {
			$posts = get_posts( array(
				'post_type' => 'mdresourcelib',
				'posts_per_page' => '9999'
			) );
			if ( ! empty( $posts ) ) {
				$context['docs'] = array();
				foreach( $posts as $post ) {
					array_push( $context['docs'], $this->get_resource_meta( $post ) );
				}
			}
		}
		return $this->template->render( $display . '.html', $context );
	}

	protected function get_resource_meta( $post )
	{
		$doc = get_post_meta( $post->ID, '_mdresourcelib_doc', TRUE );
		$info = get_post_meta( $post->ID, '_mdresourcelib_info', TRUE );
		return array(
			'id' => $post->ID,
			'title' => $post->post_title,
			'caption' => isset( $info['caption'] ) ? $info['caption'] : null,
			'type' => isset( $info['type'] ) ? $info['type'] : null,
			'location' => isset( $doc['location'] ) ? $doc['location'] : null,
		);
	}

	public function init_front_styles_and_scripts()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'front_styles_and_scripts' ) );
	}

	public function front_styles_and_scripts()
	{
		wp_enqueue_style( 'mdresourcelib-fontello', plugins_url( 'inc/fontello/css/fontello.css', $this->plugin_file ), array(), '0.1.2' );
		wp_enqueue_style( 'mdresourcelib-fontello-ie7', plugins_url( 'inc/fontello/css/fontello-ie7.css', $this->plugin_file ), array(), '0.1.2' );
		wp_enqueue_style( 'mdresourcelib-front', plugins_url( 'inc/front.css', $this->plugin_file ), array( 'mdresourcelib-fontello' ), '0.1.2' );
		global $wp_styles;
		$wp_styles->add_data( 'mdresourcelib-fontello-ie7', 'conditional', 'IE 7' );
		wp_enqueue_script( 'mdresourcelib-front', plugins_url( 'inc/front.js', $this->plugin_file ), array( 'jquery' ), '0.1.2', true );
	}

	public function init_default_pages( \WPAlchemy\Factory $factory )
	{
		$option_val = get_option( $this->default_pages_option_name );
		if ( false === $option_val ) {
			$option_get_val = isset( $_GET[$this->default_pages_option_name] ) ? $_GET[$this->default_pages_option_name] : null ;
			if ( $option_get_val ) {
				update_option( $this->default_pages_option_name, $option_get_val );
				if ( 'dismiss' != $option_get_val ) {
					add_action( 'admin_init', array ( $this, 'create_default_pages' ) );
				}
			} else {
				// use thickbox
				add_action( 'admin_enqueue_scripts', 'add_thickbox' );
				$publish_url = admin_url( 'edit.php?post_status=publish&post_type=page&' . $this->default_pages_option_name . '=publish' );
				$dismiss_url = admin_url( 'edit.php?post_type=page&' . $this->default_pages_option_name . '=dismiss' );
				$info_url = plugins_url( 'inc/default-pages.html?TB_iframe=true&width=300&height=150', $this->plugin_file );
				$message = sprintf( __( 'Create Resources page? <a title="Create Resources page?" href="%s" class="thickbox">More info</a>. Yes, <a href="%s">publish pages</a>. No, <a href="%s">dismiss</a>.', 'mdresourcelib' ), $info_url, $publish_url, $dismiss_url );
				$factory->createNotice( $message, 'update-nag', 'edit_pages' );
			}
		}
		return $option_val;
	}

	public function create_default_pages()
	{
		$post_id = wp_insert_post( array(
			'post_content' => '[resource-library]',
			'post_title' => __( 'Resources', 'mdresourcelib' ),
			'post_name' => 'resources',
			'post_type' => 'page',
			'post_status' => 'publish',
		));
	}

	public function uninstall()
	{
		return $this->delete_option_with_prefix( 'mdresourcelib' );
	}
}
