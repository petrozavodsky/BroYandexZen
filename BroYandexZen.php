<?php
/*
Plugin Name: Bro YANDEX ZEN RSS feed
Author: Petrozavodsky
Author URI: http://alkoweb.ru
*/

require_once( "includes/Wrap.php" );


use BroYandexZen\Wrap;

class BroYandexZen extends Wrap {
	public $version = '1.0.0';
	public $path;
	public $url;
	public $categories = array(
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
	);

	function __construct() {
		register_activation_hook( __FILE__, [ $this, 'hook_activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'hook_deactivate' ] );

		$this->addState();

		$this->init( __FILE__, get_called_class() );
		new \BroYandexZen\Classes\Feed( $this );
		\BroYandexZen\Classes\FeedHelper::run( $this->categories );
		new        \BroYandexZen\Classes\ZenCategories( $this->categories );
	}

	private function addState() {
		$this->path = plugin_dir_path( __FILE__ );
		$this->url  = plugin_dir_url( __FILE__ );
	}


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

}

function bro_yandex_feed_init() {
	new BroYandexZen();
}

add_action( 'plugins_loaded', 'bro_yandex_feed_init', 200 );