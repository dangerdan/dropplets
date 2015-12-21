<?php 

namespace Dropplets;

class Settings {
    
    private $settingsArray;
    private static $instance;
    
    public static function instance() {
        if (static::$instance == null) {
            static::$instance = new Settings;
        }
        return static::$instance;
    }
    
    public function __construct() {
        
        $this->settingsArray = parse_ini_file('../config.ini');
    }
    
    public function init() {
        
        $display_errors = true;

        // Display errors if there are any.
        ini_set('display_errors', $display_errors);
    }
    
    
    public function get($var) {
        if (isset($this->settingsArray[$var])) return $this->settingsArray[$var];
        return null;
    }
}


/*-----------------------------------------------------------------------------------*/
/* Post Cache ('on' or 'off')
/*-----------------------------------------------------------------------------------*/

$post_cache = 'off';
$index_cache = 'off';

/*-----------------------------------------------------------------------------------*/
/* Configuration & Options
/*-----------------------------------------------------------------------------------*/

//stripslashes($header_inject)

/*-----------------------------------------------------------------------------------*/
/* Definitions (These Should Be Moved to "Settings")
/*-----------------------------------------------------------------------------------*/

$language = 'en-us';
$feed_max_items = '10';
$error_title = 'Sorry, But That&#8217;s Not Here';
$error_text = 'Really sorry, but what you&#8217;re looking for isn&#8217;t here. Click the button below to find something else that might interest you.';

setlocale(LC_ALL, '');

/*-----------------------------------------------------------------------------------*/
/* Post Configuration
/*-----------------------------------------------------------------------------------*/

$pagination_on_off = "off"; //Infinite scroll by default?
define('PAGINATION_ON_OFF', $pagination_on_off);

$posts_per_page = 4;
define('POSTS_PER_PAGE', $posts_per_page);

$infinite_scroll = "off"; //Infinite scroll works only if pagination is on.
define('INFINITE_SCROLL', $infinite_scroll);

$post_directory = './posts/';
$cache_directory = './posts/cache/';

if (glob($post_directory . '*.md') != false)
{
    $posts_dir = './posts/';
} else {
    $posts_dir = './dropplets/welcome/';
}

// Definitions from the settings above.
define('POSTS_DIR', $posts_dir);
define('CACHE_DIR', $cache_directory);
define('FILE_EXT', '.md');

/*-----------------------------------------------------------------------------------*/
/* Cache Configuration
/*-----------------------------------------------------------------------------------*/

//no caching if user is logged in
if ( isset($_SESSION['user']) ) {
	$post_cache = 'off';
	$index_cache = 'off';
}

if (!file_exists(CACHE_DIR) && ($post_cache != 'off' || $index_cache != 'off')) {
	mkdir(CACHE_DIR,0755,TRUE);
}


/*-----------------------------------------------------------------------------------*/
/* Template Files
/*-----------------------------------------------------------------------------------*/

// Get the active template directory.
//$template_dir = './templates/' . $template . '/';
//$template_dir_url = $blog_url . 'templates/' . $template . '/';
//
//// Get the active template files.
//$post_file = $template_dir . 'post.php';
//$not_found_file = $template_dir . '404.php';
