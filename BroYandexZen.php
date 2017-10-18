<?php

/*
Plugin Name: Bro Yandex zen rss feed
Plugin URI: http://alkoweb.ru
Author: petrozavodsky
Author URI: http://alkoweb.ru
Requires PHP: 5.6
*/

require_once( "includes/Autoloader.php" );

use BroYandexZen\Autoloader;

new Autoloader( __FILE__, 'BroYandexZen' );


use BroYandexZen\Base\Wrap;
use BroYandexZen\Classes\Feed;

class BroYandexZen extends Wrap {
	public $version = '2.0.0';
	public static $textdomine;

	public $path;
	public $url;
	public static $slug = 'feed-yandex-zen';
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
	];

	function __construct() {
		$this->addState();

		self::$textdomine = $this->setTextdomain();
		new Feed( $this->path , $this->slug );

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
	}

	private function addState() {
		$this->path = plugin_dir_path( __FILE__ );
		$this->url  = plugin_dir_url( __FILE__ );
	}


	public function activate() {
		flush_rewrite_rules();
	}

	public function deactivate() {
		flush_rewrite_rules();
	}


}

function BroYandexZen__init() {
	new BroYandexZen();
}

add_action( 'plugins_loaded', 'BroYandexZen__init', 30 );