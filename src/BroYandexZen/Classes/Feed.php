<?php

namespace BroYandexZen\Classes;


class Feed
{

    private $plugin_path;

    private $slug;

    public function __construct($plugin_path, $slug)
    {

        $this->plugin_path = $plugin_path;

        $this->slug = $slug;

        add_action('init', [$this, 'init_feed']);

    }

    public function init_feed()
    {
        add_feed($this->slug, [$this, 'feed_markup']);
    }

    public function feed_markup()
    {

        /**
         * Hack php 7.2 compatible
         */
        global $page, $pages;
        $pages = [1, 2];
        $page = [1, 2];


        header('Content-Type: ' . feed_content_type('rss') . '; charset=' . get_option('blog_charset'), true);
        do_action('BroYandexZenFeed_feed_before');
        $template = apply_filters('BroYandexZenFeed_template_rss', $this->plugin_path . "templates/feed.php");
        if (file_exists($template)) {
            require_once($template);
        }
        do_action('BroYandexZenFeed_feed_after');
        exit;
    }

}
