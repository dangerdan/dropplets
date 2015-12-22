<?php

use Michelf\Markdown;
use Dropplets\Actions;
use Dropplets\Settings;

/*-----------------------------------------------------------------------------------*/
/* User Machine
/*-----------------------------------------------------------------------------------*/

$login_error = null;

            $settings = Settings::instance();

if (isset($_GET['action']))
{
    $action = $_GET['action'];
    switch ($action)
    {

        // Logging in.
        case 'login':
            // Password hashing via phpass.
            $hasher = new \Phpass\Hash;
            $password = $settings->get('password');
            if ((isset($_POST['password'])) && $hasher->CheckPassword($_POST['password'], $password)) {
                $_SESSION['user'] = true;

                // Redirect if authenticated.
                header('Location: ' . './');
            } else {
                
                // Display error if not authenticated.
                $login_error = 'Nope, try again!';
            }
            break;

        // Logging out.
        case 'logout':
            session_unset();
            session_destroy();

            // Redirect to dashboard on logout.
            header('Location: ' . './');
            break;
        
        // Fogot password.
        case 'forgot':
            
            // The verification file.
            $verification_file = "./verify.php";
            
            // If verified, allow a password reset.
            if (!isset($_GET["verify"])) {
            
                $code = sha1(md5(rand()));

                $verify_file_contents[] = "<?php";
                $verify_file_contents[] = "\$verification_code = \"" . $code . "\";";
                file_put_contents($verification_file, implode("\n", $verify_file_contents));

                $recovery_url = sprintf("%s/index.php?action=forgot&verify=%s,", $blog_url, $code);
                $message      = sprintf("To reset your password go to: %s", $recovery_url);

                $headers[] = "From: " . $blog_email;
                $headers[] = "Reply-To: " . $blog_email;
                $headers[] = "X-Mailer: PHP/" . phpversion();

                mail($blog_email, $blog_title . " - Recover your Dropplets Password", $message, implode("\r\n", $headers));
                $login_error = "Details on how to recover your password have been sent to your email.";
            
            // If not verified, display a verification error.   
            } else {

                include($verification_file);

                if ($_GET["verify"] == $verification_code) {
                    $_SESSION["user"] = true;
                    unlink($verification_file);
                } else {
                    $login_error = "That's not the correct recovery code!";
                }
            }
            break;
        
        // Invalidation            
        case 'invalidate':
            if (!$_SESSION['user']) {
                $login_error = 'Nope, try again!';
            } else {
                if (!file_exists($upload_dir . 'cache/')) {
                    return;
                }
                
                $files = glob($upload_dir . 'cache/*');
                
                foreach ($files as $file) {
                    if (is_file($file))
                        unlink($file);
                }
            }
            
            header('Location: ' . './');
            break;
    }
    
}

define('LOGIN_ERROR', $login_error);


/*-----------------------------------------------------------------------------------*/
/* Get Image for a Post
/*-----------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/* Post Pagination
/*-----------------------------------------------------------------------------------*/

function get_pagination($page,$total) {

    $string = '';
    $string .= "<ul style=\"list-style:none; width:400px; margin:15px auto;\">";

    for ($i = 1; $i<=$total;$i++) {
        if ($i == $page) {
            $string .= "<li style='display: inline-block; margin:5px;' class=\"active\"><a class=\"button\" href='#'>".$i."</a></li>";
        } else {
            $string .=  "<li style='display: inline-block; margin:5px;'><a class=\"button\" href=\"?page=".$i."\">".$i."</a></li>";
        }
    }
    
    $string .= "</ul>";
    return $string;
}

/*-----------------------------------------------------------------------------------*/
/* Get Installed Templates
/*-----------------------------------------------------------------------------------*/

function get_installed_templates() {
    
    // The currently active template.
    $active_template = ACTIVE_TEMPLATE;

    // The templates directory.
    $templates_directory = './templates/';

    // Get all templates in the templates directory.
    $available_templates = glob($templates_directory . '*');
    
    foreach ($available_templates as $template):

        // Generate template names.
        $template_dir_name = substr($template, 12);

        // Template screenshots.
        $template_screenshot = '' . $templates_directory . '' . $template_dir_name . '/screenshot.jpg'; {
            ?>
            <li<?php if($active_template == $template_dir_name) { ?> class="active"<?php } ?>>
                <div class="shadow"></div>
                <form method="POST" action="./dropplets/save.php">
                    <img src="<?php echo $template_screenshot; ?>">
                    <input type="hidden" name="template" id="template" required readonly value="<?php echo $template_dir_name ?>">
                    <button class="<?php if ($active_template == $template_dir_name) :?>active<?php else : ?>activate<?php endif; ?>" type="submit" name="submit" value="submit"><?php if ($active_template == $template_dir_name) :?>t<?php else : ?>k<?php endif; ?></button>
                </form>
            </li>
        <?php
        }
    endforeach;
}

/*-----------------------------------------------------------------------------------*/
/* Get Premium Templates
/*-----------------------------------------------------------------------------------*/

function get_premium_templates($type = 'all', $target = 'blank') {
    
    $templates = simplexml_load_file('http://dropplets.com/templates-'. $type .'.xml');
    
    if($templates===FALSE) {
        // Feed not available.
    } else {
        foreach ($templates as $template):
            
            // Define some variables
            $template_file_name=$template->file;
            $template_price=$template->price;
            $template_url=$template->url;
            
            { ?>
            <li class="premium">
                <img src="http://dropplets.com/demo/templates/<?php echo $template_file_name; ?>/screenshot.jpg">
                <a class="buy" href="http://gum.co/dp-<?php echo $template_file_name; ?>" title="Purchase/Download"><?php echo $template_price; ?></a> 
                <a class="preview" href="http://dropplets.com/demo/?template=<?php echo $template_file_name; ?>" title="Prview" target="_<?php echo $target; ?>">p</a>    
            </li>
            <?php } 
        endforeach;
    }
}

function count_premium_templates($type = 'all') {

    $templates = simplexml_load_file('http://dropplets.com/templates-'. $type .'.xml');

    if($templates===FALSE) {
        // Feed not available.
    } else {
        $templates = simplexml_load_file('http://dropplets.com/templates-'. $type .'.xml');
        $templates_count = $templates->children();
        echo count($templates_count);
    }
}

/*-----------------------------------------------------------------------------------*/
/* If is Home (Could use "is_single", "is_category" as well.)
/*-----------------------------------------------------------------------------------*/



$homepage = parse_url($settings->get('blog_url'), PHP_URL_PATH);

// Get the current page.    
$currentpage  = $_SERVER["REQUEST_URI"];

// If is home.
$is_home = ($homepage==$currentpage);
define('IS_HOME', $is_home);
define('IS_CATEGORY', (bool)strstr($_SERVER['REQUEST_URI'], '/category/'));
define('IS_SINGLE', !(IS_HOME || IS_CATEGORY));

/*-----------------------------------------------------------------------------------*/
/* Get Profile Image
/*-----------------------------------------------------------------------------------*/

function get_twitter_profile_img($username) {
	
	// Get the cached profile image.
    $cache = IS_CATEGORY ? '.' : '';
    $array = explode('/category/', $_SERVER['REQUEST_URI']);
    if (isset($array[1])) $array = explode('/', $array[1]);
    if(count($array)!=1) $cache .= './.';
    $cache .= './cache/';
	$profile_image = $cache.$username.'.jpg';

	// Cache the image if it doesn't already exist.
	if (!file_exists($profile_image)) {
	    $image_url = 'http://twitter.com/'.$username.'/profile_image?size=original';
	    $image = file_get_contents($image_url);
        if (is_dir($cache)) {
	       file_put_contents($cache.$username.'.jpg', $image);
        }
	}
	
	// Return the image URL.
	return $profile_image;
}

/*-----------------------------------------------------------------------------------*/
/* Include All Plugins in Plugins Directory
/*-----------------------------------------------------------------------------------*/

foreach(glob('./plugins/' . '*.php') as $plugin){
    include_once $plugin;
}

/*-----------------------------------------------------------------------------------*/
/* Dropplets Header
/*-----------------------------------------------------------------------------------*/

function get_header() {

            $settings = Settings::instance();
            $blog_url = $settings->get('blog_url');
?>
    <!-- RSS Feed Links -->
    <link rel="alternate" type="application/rss+xml" title="Subscribe using RSS" href="<?php echo BLOG_URL; ?>rss" />
    <link rel="alternate" type="application/atom+xml" title="Subscribe using Atom" href="<?php echo BLOG_URL; ?>atom" />
    
    <!-- Dropplets Styles -->
    <link rel="stylesheet" href="<?php echo $blog_url?>/dropplets/style/style.css">
    <link rel="shortcut icon" href="<?php echo $blog_url?>/dropplets/style/images/favicon.png">

    <!-- User Header Injection -->
    <?php //echo $settings->get('header_inject'); ?>
    
    <!-- Plugin Header Injection -->
    <?php \Dropplets\Actions\Action::run('dp_header'); ?>
<?php 

} 

/*-----------------------------------------------------------------------------------*/
/* Dropplets Footer
/*-----------------------------------------------------------------------------------*/

function get_footer() { 


$settings = new Dropplets\Settings;
    
$PAGINATION_ON_OFF = $settings->get('PAGINATION_ON_OFF');       

?>
    <!-- jQuery & Required Scripts -->
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    
    <?php if (!IS_SINGLE && $PAGINATION_ON_OFF !== "off") { ?>
    <!-- Post Pagination -->
    <script>
        var infinite = true;
        var next_page = 2;
        var loading = false;
        var no_more_posts = false;
        $(function() {
            function load_next_page() {
                $.ajax({
                    url: "index.php?page=" + next_page,
                    beforeSend: function () {
                        $('body').append('<article class="loading-frame"><div class="row"><div class="one-quarter meta"></div><div class="three-quarters"><img src="./templates/<?php echo(ACTIVE_TEMPLATE); ?>/loading.gif" alt="Loading"></div></div></article>');
                        $("body").animate({ scrollTop: $("body").scrollTop() + 250 }, 1000);
                    },
                    success: function (res) {
                        next_page++;
                        var result = $.parseHTML(res);
                        var articles = $(result).filter(function() {
                            return $(this).is('article');
                        });
                        if (articles.length < 2) {  //There's always one default article, so we should check if  < 2
                            $('.loading-frame').html('You\'ve reached the end of this list.');
                            no_more_posts = true;
                        }  else {
                            $('.loading-frame').remove();
                            $('body').append(articles);
                        }
                        loading = false;
                    },
                    error: function() {
                        $('.loading-frame').html('An error occurred while loading posts.');
                        //keep loading equal to false to avoid multiple loads. An error will require a manual refresh
                    }
                });
            }

            $(window).scroll(function() {
                var when_to_load = $(window).scrollTop() * 0.32;
                if (infinite && (loading != true && !no_more_posts) && $(window).scrollTop() + when_to_load > ($(document).height()- $(window).height() ) ) {
                    // Sometimes the scroll function may be called several times until the loading is set to true.
                    // So we need to set it as soon as possible
                    loading = true;
                    setTimeout(load_next_page,500);
                }
            });
        });
    </script>
    <?php } ?>
    
    <!-- Dropplets Tools -->
    <?php 
    
    $tools = new Dropplets\Tools;
    echo $tools->showMenu(); ?>
    
    <!-- User Footer Injection -->
    <?php echo $settings->get('FOOTER_INJECT'); ?>
    
    <!-- Plugin Footer Injection -->
    <?php \Dropplets\Actions\Action::run('dp_footer'); ?>
<?php 

}
