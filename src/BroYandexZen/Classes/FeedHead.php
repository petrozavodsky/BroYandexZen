<?php

namespace BroYandexZen\Classes;


class FeedHead
{

    public function __construct()
    {
        add_action('BroYandexZenFeedFields_head', [$this, 'header']);
    }

    public function header()
    {

        $language_array = explode("-", get_bloginfo('language'));

        $lang = array_shift($language_array);

        ?>

        <title>
            <?php echo esc_html(get_bloginfo('name'))."\r\n"; ?>
        </title>

        <link>
        <?php echo esc_html(get_option('home'))."\r\n"; ?>
        </link>

        <description>
            <?php echo esc_html(get_bloginfo('description'))."\r\n"; ?>
        </description>

        <language>
            <?php echo $lang."\r\n"; ?>
        </language>

        <?php
    }

}