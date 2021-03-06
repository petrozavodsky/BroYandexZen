<?php

/*
Plugin Name: Bro Yandex zen rss feed NG
Plugin URI: http://alkoweb.ru
Author: petrozavodsky, vovasik
Author URI: http://alkoweb.ru
Text Domain: BroYandexZen
Domain Path: /languages
Version: 2.0.0
Requires PHP: 5.6
License: GPLv3
*/

require_once(plugin_dir_path(__FILE__) . "includes/Autoloader.php");

use BroYandexZen\Autoloader;

new Autoloader(__FILE__, 'BroYandexZen');


use BroYandexZen\Base\Wrap;
use BroYandexZen\Classes\Feed;
use BroYandexZen\Classes\FeedHead;
use BroYandexZen\Classes\FeedHelper;
use BroYandexZen\Classes\ZenCategories;

class BroYandexZen extends Wrap
{
    public $version = '2.0.0';
    public static $textdomine;

    public $path;
    public $url;
    public static $slug = 'yandex-zen';
    public static $categories = [
        'Общество',
        'Происшествия',
        'Политика',
        'Война',
        'Экономика',
        'Спорт',
        'Технологии',
        'Наука',
        'Игры',
        'Музыка',
        'Литература',
        'Кино',
        'Культура',
        'Мода',
        'Знаменитости',
        'Психология',
        'Здоровье',
        'Авто',
        'Дом',
        'Хобби',
        'Еда',
        'Дизайн',
        'Фотографии',
        'Юмор',
        'Природа',
        'Путешествия',
    ];

    function __construct()
    {
        register_activation_hook(__FILE__, [$this, 'hook_activate']);
        register_deactivation_hook(__FILE__, [$this, 'hook_deactivate']);

        $this->addState();

        new FeedHead();
        new FeedHelper(self::$categories);
        new ZenCategories(self::$categories);

        new Feed($this->path, self::$slug);

        register_activation_hook(__FILE__, [$this, 'hook_activate']);
        register_deactivation_hook(__FILE__, [$this, 'hook_deactivate']);

    }

    private function addState()
    {
        $this->path = plugin_dir_path(__FILE__);
        $this->url = plugin_dir_url(__FILE__);
    }

    public function hook_activate()
    {
        if (current_user_can('activate_plugins')) {
            flush_rewrite_rules(false);
        }
    }

    public function hook_deactivate()
    {
        if (current_user_can('activate_plugins')) {
            flush_rewrite_rules(false);
        }

    }


}

function BroYandexZen__init()
{
    new BroYandexZen();
}

add_action('plugins_loaded', 'BroYandexZen__init', 30);