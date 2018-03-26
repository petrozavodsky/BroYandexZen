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
<<<<<<< HEAD
		'Путешествия',
	);
=======
	];
>>>>>>> 772d6b6e69562b83a5d51dc9cdf7b10a74a48506

	function __construct() {
		register_activation_hook( __FILE__, [ $this, 'hook_activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'hook_deactivate' ] );

		$this->addState();

<<<<<<< HEAD
		$this->init( __FILE__, get_called_class() );
		new \BroYandexZen\Classes\Feed( $this );
		\BroYandexZen\Classes\FeedHelper::run( $this->categories );
		new        \BroYandexZen\Classes\ZenCategories( $this->categories );
=======
		self::$textdomine = $this->setTextdomain();
		new Feed( $this->path , $this->slug );

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
>>>>>>> 772d6b6e69562b83a5d51dc9cdf7b10a74a48506
	}

	private function addState() {
		$this->path = plugin_dir_path( __FILE__ );
		$this->url  = plugin_dir_url( __FILE__ );
	}


<<<<<<< HEAD
	public function hook_activate() {
		if ( current_user_can( 'activate_plugins' ) ) {
			flush_rewrite_rules( false );
		}
	}

	public function hook_deactivate() {
		if ( current_user_can( 'activate_plugins' ) ) {
			flush_rewrite_rules( false );
		}

	}

=======
	public function activate() {
		flush_rewrite_rules();
	}

	public function deactivate() {
		flush_rewrite_rules();
	}


>>>>>>> 772d6b6e69562b83a5d51dc9cdf7b10a74a48506
}

function BroYandexZen__init() {
	new BroYandexZen();
}

add_action( 'plugins_loaded', 'BroYandexZen__init', 30 );