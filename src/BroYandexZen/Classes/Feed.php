<?php

namespace BroYandexZen\Classes {

	class Feed {

		private $version;
		private $plugin_path;
		private $slug = 'feed-yandex-zen';

		function __construct( $instance ) {
			$this->plugin_path = $instance->path;
			$this->version     = $instance->version;
			register_activation_hook( $instance->file, array( $this, 'activate' ) );
			register_deactivation_hook( $instance->file, array( $this, 'deactivate' ) );
			add_action( "template_redirect", array( $this, "template_rule" ) );
			add_action('init', array($this,"add_feed"));
		}

		public function add_feed( ) {
			add_feed( $this->slug, array( $this, 'feed_markup' ) );
		}

		function template_rule() {
			global $wp_query;
			if ( strpos( $wp_query->query_vars['feed'], $this->slug ) !== false ) {
				$wp_query->is_404     = false;
				$wp_query->have_posts = true;
				$wp_query->is_archive = true;
			}
		}

		function feed_markup() {
			header( 'Content-Type: ' . feed_content_type( 'rss' ) . '; charset=' . get_option( 'blog_charset' ), true );
			status_header( 200 );
			global $wp_query;
			do_action( 'BroYandexZenFeed_before_feed' );
			$template = apply_filters( 'BroYandexZenFeed_template_rss', $this->plugin_path . "templates/feed.php" );
			require( $template );
			do_action( 'BroYandexZenFeed_after_feed' );
			exit;
		}

		public function activate() {
			flush_rewrite_rules();
		}

		public function deactivate() {
			flush_rewrite_rules();
		}

	}
}