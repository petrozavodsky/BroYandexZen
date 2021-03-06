<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:media="http://search.yahoo.com/mrss/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:georss="http://www.georss.org/georss">

    <channel>

        <?php do_action('BroYandexZenFeedFields_head', $post); ?>

        <?php

        global $post;

        $args = [
            'post_type' => 'post',
            'posts_per_page' => 32,
        ];

        $args = apply_filters("BroYandexZenFeedFields_post_args", $args);

        $posts = get_posts($args);

        foreach ($posts as $post):?>

            <item>

                <?php do_action('BroYandexZenFeedFields_before', $post); ?>

                <guid><?php echo esc_html(get_the_ID()) ?></guid>

                <title><?php echo esc_html(get_the_title($post)); ?></title>

                <link><?php echo esc_url(get_the_permalink($post)); ?></link>

                <pdalink><?php echo esc_url(get_the_permalink($post)); ?></pdalink>

                <pubDate><?php echo esc_html(mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true, $post), false)); ?></pubDate>

                <media:rating scheme="urn:simple">nonadult</media:rating>

                <description>
                    <![CDATA[<?php echo esc_html(get_the_excerpt($post)); ?>]]>
                </description>

                <?php do_action('BroYandexZenFeedFields_after', $post); ?>

                <?php do_action('BroYandexZenFeedFields_related', $post, $posts); ?>

            </item>

            <?php
            wp_reset_postdata();
        endforeach;
        ?>

    </channel>
</rss>



