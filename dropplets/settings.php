<?php

namespace Dropplets;

class Settings {
	private $settingsArray;
	private static $instance;
	public $post_directory = './posts/';
	public $cache_directory = './posts/cache/';
	public $posts_dir;
	public static function instance() {
		if (static::$instance == null) {
			static::$instance = new Settings ();
		}
		return static::$instance;
	}
	public function __construct() {
		$this->settingsArray = parse_ini_file ( './config.ini' );
	}
	public function init() {
		$display_errors = true;
		
		// Display errors if there are any.
		ini_set ( 'display_errors', $display_errors );
		
		if (glob ( $this->post_directory . '*.md' ) != false) {
			$this->posts_dir = './posts/';
		} else {
			$this->posts_dir = './dropplets/welcome/';
		}
		
		if (! file_exists ( $this->config ( "cache_dir" ) ) && ($this->get ( "post_cache" ) != 'false' || $this->get ( "index_cache" ) != 'false')) {
			mkdir ( $this->config ( "cache_dir" ), 0755, TRUE );
		}
	}
	public function get($var) {
		if (isset ( $_SESSION ['user'] ) && ($var == 'post_cache' || $var == 'index_cache')) {
			return 'false';
		}
		
		if (isset ( $this->settingsArray [$var] ))
			return $this->settingsArray [$var];
		return null;
	}
	public function config($var) {
		switch ($var) {
			case "cache_dir" :
				return './posts/cache/';
		}
		return null;
	}
}