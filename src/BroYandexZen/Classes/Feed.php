<?php

namespace BroYandexZen\Classes;


class Feed {

	private $plugin_path;
	private $slug = 'feed-yandex-zen';

	function __construct( $plugin_path, $slug ) {
		$this->plugin_path = $plugin_path;
		$this->slug        = $slug;
		add_action( 'init', array( $this, "add_feed" ) );
	}

	public function add_feed( $template_path ) {
		global $wp_rewrite;
		if ( ! in_array( $this->slug, $wp_rewrite->feeds ) ) {
			$wp_rewrite->feeds[] = $this->slug;
		}
		$hook = 'do_feed_' . $this->slug;
		remove_action( $hook, $hook );
		add_action( $hook, [ $this, 'feed_markup' ], 10, 2 );

		return $hook;
	}

	function feed_markup() {
		header( 'Content-Type: ' . feed_content_type( 'rss' ) . '; charset=' . get_option( 'blog_charset' ), true );
		do_action( 'BroYandexZenFeed_feed_before' );
		$template = apply_filters( 'BroYandexZenFeed_template_rss', $this->plugin_path . "templates/feed.php" );
		if ( file_exists( $template ) ) {
			require_once( $template );
		}
		do_action( 'BroYandexZenFeed_feed_after' );
		exit;
	}

}
