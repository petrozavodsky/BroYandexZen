<?php

namespace BroYandexZen {
	abstract class Wrap {
		private $space;
		public $version = '1.0.0';
		public $prefix;
		public $base_name;
		private $loader;
		private $file;

		function init( $file, $className ) {
			$this->file      = $file;
			$this->space     = $className;
			$this->prefix    = "_{$this->space}";
			$this->base_name = $this->space;
			$this->autoload();
			$this->addNamespaceObject( "Widgets" );
			$this->addNamespaceObject( "Classes" );
			$this->activateWidgets( "Widgets" );
		}

		/**
		 * @param string $val
		 *
		 * @return $this
		 */
		public function setSpace( $val ) {
			//Exception
			$this->space = $val;

			return $this;
		}

		/**
		 * @param string $val
		 *
		 * @return $this
		 */
		public function setVersion( $val ) {
			//Exception
			$this->version = $val;

			return $this;
		}

		/**
		 * @param string $dir
		 * @param mixed $space
		 */
		function addNamespaceObject( $dir, $space = false ) {
			$s = DIRECTORY_SEPARATOR;
			if ( ! $space ) {
				$space = $this->space;
			}
			$this->loader->addNamespace(
				$space . "\\{$dir}",
				realpath( plugin_dir_path( $this->file ) ) . "{$s}src{$s}{$space}{$s}{$dir}"
			);
		}

		/**
		 * @param string $dir
		 * @param bool $space
		 */
		public function activateWidgets( $dir, $space = false ) {
			$s = DIRECTORY_SEPARATOR;
			if ( ! $space ) {
				$space = $this->space;
			}
			$dir = realpath( plugin_dir_path( $this->file ) ) . "{$s}src{$s}{$space}{$s}{$dir}";

			if ( file_exists( $dir ) ) {
				$dir = opendir( $dir );
				while ( ( $currentFile = readdir( $dir ) ) !== false ) {
					if ( $currentFile == '.' or $currentFile == '..' ) {
						continue;
					}

					$widget_name = basename( $currentFile, ".php" );
					add_action( 'widgets_init', function () use ( $widget_name ) {
						register_widget( $class_name = '\BroYandexZenFeed\Widgets\\' . $widget_name );
					} );
				}
				closedir( $dir );
			}
		}

		function autoload() {
			require_once( 'Autoload.php' );
			$this->loader = new Autoload();
			$this->loader->register();
		}

        public function registerJs($name, $in_footer = false, $deps = [], $version = false, $file = false)
        {
			if ( ! $file ) {
				$file = plugin_dir_url( $this->file ) . "public/css/{$this->base_name}-{$name}.css";
			}
			if ( ! $version ) {
				$version = $this->version;
			}

			wp_enqueue_script(
				$this->base_name . "-" . $name,
				$file,
				$deps,
				$version,
				$in_footer
			);

			return $name;
		}

        public function addJs($name, $position = "wp_enqueue_scripts", $deps = [], $version = false, $file = false)
        {
			$name      = $this->registerJs( $name, $file, $deps, $version );
			$in_footer = false;

			if ( $position == "footer" ) {
				$position  = "wp_footer";
				$in_footer = true;
			} elseif ( $position == "head" ) {
				$position = "wp_head";
			}

			add_action( $position, function () use ( $in_footer, $name, $file, $deps, $version ) {
				wp_enqueue_script( $name, $file, $deps, $version, $in_footer );
			} );
		}

        public function registerCss($name, $deps = [], $version = false, $file = false, $media = 'all')
        {
			if ( ! $file ) {
				$file = plugin_dir_url( $this->file ) . "public/css/{$this->base_name}-{$name}.css";
			}
			if ( ! $version ) {
				$version = $this->version;
			}

			wp_register_style(
				$this->base_name . "-" . $name,
				$file,
				$deps,
				$version,
				$media
			);

			return $name;
		}


        public function addCss($name, $position = "wp_enqueue_scripts", $deps = [], $version = false, $file = false, $media = 'all')
        {

			if ( $position == "footer" ) {
				$position = "wp_footer";
			} elseif ( $position == "head" ) {
				$position = "wp_head";
			}

			add_action( $position, function () use ( $media, $name, $file, $deps, $version ) {
				$name = $this->registerCss( $name, $deps, $file, $version, $media );
				wp_enqueue_style( $this->base_name . "-" . $name );
			} );
		}
	}
}