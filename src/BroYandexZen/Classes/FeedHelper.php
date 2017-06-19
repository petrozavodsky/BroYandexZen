<?php

namespace BroYandexZen\Classes {
	class FeedHelper {

		private static $categories;
		private static $field_category_name = "_zen_category";

		public static function run( $categories ) {
			self::$categories = $categories;
			add_action( "BroYandexZenFeedFields_after", array( __CLASS__, 'author' ), 11, 1 );
			add_action( "BroYandexZenFeedFields_after", array( __CLASS__, 'category' ), 12, 1 );
			add_action( "BroYandexZenFeedFields_after", array( __CLASS__, 'enclosure' ), 13, 1 );
			add_action( "BroYandexZenFeedFields_after", array( __CLASS__, 'fulltext' ), 13, 1 );

		}


		/**
		 * @param WP_Post $post WordPress post object
		 * @param bool $show
		 *
		 * @return string
		 */
		public static function fulltext( $post, $show = true ) {

			$content = $post->post_content;
			$content = do_shortcode( $content );

			$content = self::fulltext_helper($content);

			$res = "";
			$res .= "<content:encoded> <![CDATA[{$content}]]> </content:encoded>\r\n";

			if ( $show ) {
				echo $res;
			} else {
				return $res;
			}
		}

		public function fulltext_helper( $str ) {
			return preg_replace( '/(<img.+?>)/iu', '<figure>$1</figure>', $str );
		}

		/**
		 * @param WP_Post $post WordPress post object
		 * @param bool $show
		 *
		 * @return string
		 */
		public static function category( $post, $show = true ) {
			$category = get_post_meta( $post->ID, self::$field_category_name, true );
			if ( $category === "" || $category === false ) {
				$category = self::$categories[0];
			}

			$category = trim( $category );

			$res = "";
			$res .= "<category>{$category}</category>\r\n";

			if ( $show ) {
				echo $res;
			} else {
				return $res;
			}
		}

		/**
		 * @param WP_Post $post WordPress post object
		 * @param bool $show
		 *
		 * @return string
		 */
		public static function author( $post, $show = true ) {
			$name = get_the_author_meta( 'display_name', $post->post_author );
			$name = trim( $name );
			$res  = "";
			$res  .= "<author>{$name}</author>\r\n";

			if ( $show ) {
				echo $res;
			} else {
				return $res;
			}
		}

		/**
		 * @param WP_Post $post WordPress post object
		 * @param bool $show
		 *
		 * @return string|bool
		 */
		public static function enclosure( $post, $show = true ) {
			$image_size_name = "full";
			$thumbnail_id    = get_post_thumbnail_id( $post );
			if ( ! is_numeric( $thumbnail_id ) ) {
				return __return_empty_string();
			}

			$image_meta_data = get_post_meta( $thumbnail_id, '_wp_attachment_metadata', true );
			$size            = self::get_image_file_size( dirname( $image_meta_data['file'] ), $image_meta_data['sizes'][ $image_size_name ]['file'] );
			$url             = get_the_post_thumbnail_url( $post, $image_meta_data['sizes'][ $image_size_name ] );
			$mime_type       = self::get_image_type( $url );
			$res             = "";
			$res             .= "<enclosure url='{$url}'  type='{$mime_type}' length='{$size}'/>\r\n";

			if ( $show ) {
				echo $res;
			} else {
				return $res;
			}

		}


		public static function get_image_type( $filename ) {
			$mime_types = array(
				'png'  => 'image/png',
				'jpe'  => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg'  => 'image/jpeg',
				'gif'  => 'image/gif',
				'bmp'  => 'image/bmp',
				'ico'  => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif'  => 'image/tiff',
				'svg'  => 'image/svg+xml',
				'svgz' => 'image/svg+xml',
			);

			$ext = strtolower( array_pop( explode( '.', $filename ) ) );
			if ( array_key_exists( $ext, $mime_types ) ) {
				return $mime_types[ $ext ];
			} elseif ( function_exists( 'finfo_open' ) ) {
				$finfo    = finfo_open( FILEINFO_MIME );
				$mimetype = finfo_file( $finfo, $filename );
				finfo_close( $finfo );

				return $mimetype;
			} else {
				return 'application/octet-stream';
			}
		}


		/**
		 * @param string $dir_str
		 * @param string $filename
		 *
		 * @return int
		 */
		public static function get_image_file_size( $dir_str, $filename ) {
			$upload_dir  = wp_upload_dir();
			$file_string = $upload_dir['basedir'] . "/" . $dir_str . "/" . $filename;
			if ( file_exists( $file_string ) ) {
				return filesize( $file_string );
			}

			return 0;
		}


	}
}

