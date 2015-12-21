<?php
namespace Dropplets;
use Dropplets\Settings;


class Tools {
    
    public $settings = null;
    
    public function __construct() {
        $this->settings = Settings::instance();
    }
    
    public function loginForm() {
        
        $cookieVal = (isset($_COOKIE['dp-panel'])) ? $_COOKIE['dp-panel'] : "";
        $img = get_twitter_profile_img($this->settings->get('blog_twitter'));
        $blog_title = $this->settings->get('blog_title');
        
        $errorHtml = "";  
        if (isset($login_error)) {
            $errorHtml = <<<MARKER
        <div class="dp-row">
            <div class="dp-icon dp-icon-large dp-icon-question"></div>
            <div class="dp-content">Forget Your password?</div>
            <a class="dp-link" href="?action=forgot" target="_blank"></a>
        </div>
MARKER;
        }
        
$html = <<<MARKER
<div class="dp-panel-wrapper $cookieVal" id="dp-dropplets">
    <div class="dp-panel">
        <div class="dp-row profile">
            <div class="dp-icon">
                <img src="$img" alt="$blog_title" />
            </div>
            
            <div class="dp-content">
                <span class="title">Hey There!</span>
                <a class="dp-button dp-button-dark dp-close dp-icon-close" href="#dp-dropplets"></a>
            </div>
        </div>
        
        <div class="dp-row dp-editable dp-editable-dark">
            <div class="dp-icon dp-icon-key"></div>
            
            <div class="dp-content">
                <form method="POST" action="?action=login">
                    <label>Enter Your Password</label>
                    <input type="password" name="password" id="password">
                    <button class="dp-icon-checkmark" type="submit" name="submit" value="submit"></button>
                </form>
            </div>
        </div>
        $errorHtml
        <div class="dp-row">
            <div class="dp-icon dp-icon-dropplets"></div>
            <div class="dp-content">What is This?</div>
            <a class="dp-link" href="https://github.com/dangerdan/dropplets" target="_blank"></a>
        </div>
    </div>
</div>
MARKER;
        return $html;
        
    }
    
    /*-----------------------------------------------------------------------------------*/
    /* If Logged Out, Get the Login Form
    /*-----------------------------------------------------------------------------------*/
    public function internalMenu() {
        
        $login_error = LOGIN_ERROR;

        $blog_title = $this->settings->get('blog_title');
        $blog_twitter = $this->settings->get('blog_twitter');
        $blog_email = $this->settings->get('blog_email');
        $blog_title = $this->settings->get('blog_title');
        $meta_description = $this->settings->get('meta_description');

        $intro_title = $this->settings->get('intro_title');
        $intro_text = $this->settings->get('intro_text');

        $header_inject = $this->settings->get('header_inject');
        $footer_inject = $this->settings->get('footer_inject');
?>

<div class="dp-panel-wrapper <?php if($_COOKIE['dp-panel']) { echo($_COOKIE['dp-panel']); } ?>" id="dp-dropplets">
    <div class="dp-panel">
        <div class="dp-row profile">
            <div class="dp-icon">
                <img src="<?php echo get_twitter_profile_img($blog_twitter); ?>" alt="<?php echo $blog_title; ?>" />
            </div>
            
            <div class="dp-content">
                <span class="title">Welcome Back!</span>
                <a class="dp-button dp-button-dark dp-close  dp-icon-close" href="#dp-dropplets"></a>
            </div>
        </div>
        
        <div class="dp-row">
            <div class="dp-icon dp-icon-dropplets"></div>
            <div class="dp-content">Publish or Update Posts</div>
            <label class="dp-link" for="postfiles"></label>
            <input style="display: none;" type="file" name="postfiles" id="postfiles" class="postfiles" multiple="multiple" />
        </div>
        
        <form method="POST" action="./dropplets/save.php">
            <div class="dp-row">
                <div class="dp-icon dp-icon-settings"></div>
                <div class="dp-content">Blog Settings</div>                
                <a class="dp-link dp-toggle collapsed" href="#dp-settings"></a>
                <button class="dp-button dp-button-submit" type="submit" name="submit" value="submit">k</button>
            </div>
            
            <div class="dp-sub-panel" id="dp-settings">
                <div class="dp-row dp-editable">
                    <div class="dp-icon dp-icon-edit"></div>
                    
                    <div class="dp-content">
                        <label>Blog Password</label>
                        <input type="password" name="password" id="password" value="">
                    </div>
                </div>
            </div>
            
            <div class="dp-row">
                <div class="dp-icon dp-icon-profile dp-icon-large"></div>
                <div class="dp-content">Blog Profile</div>                
                <a class="dp-link dp-toggle" href="#dp-profile"></a>
                <button class="dp-button dp-button-submit" type="submit" name="submit" value="submit">k</button>
            </div>
            
            <div class="dp-sub-panel" id="dp-profile">
                <div class="dp-row dp-editable">
                    <div class="dp-icon dp-icon-edit"></div>
                    
                    <div class="dp-content">
                        <label>Blog Email</label>
                        <input type="text" name="blog_email" id="blog_email" value="<?php echo $blog_email; ?>">
                    </div>
                </div>
                
                <div class="dp-row dp-editable">
                    <div class="dp-icon dp-icon-edit"></div>
                    
                    <div class="dp-content">
                        <label>Blog Twitter</label>
                        <input type="text" name="blog_twitter" id="blog_twitter" value="<?php echo $blog_twitter; ?>">
                    </div>
                </div>
            </div>
            
            <div class="dp-row">
                <div class="dp-icon dp-icon-text"></div>
                <div class="dp-content">Blog Meta</div>                
                <a class="dp-link dp-toggle" href="#dp-meta-text"></a>
                <button class="dp-button dp-button-submit" type="submit" name="submit" value="submit">k</button>
            </div>
            
            <div class="dp-sub-panel" id="dp-meta-text">
                <div class="dp-row dp-editable">
                    <div class="dp-icon dp-icon-edit"></div>
                    
                    <div class="dp-content">
                        <label>Blog Title</label>
                        <input type="text" name="blog_title" id="blog_title" value="<?php echo $blog_title; ?>">
                    </div>
                </div>
                
                <div class="dp-row dp-editable">
                    <div class="dp-icon dp-icon-edit"></div>
                    
                    <div class="dp-content">
                        <label>Blog Description</label>
                        <textarea name="meta_description" id="meta_description" rows="6" placeholder="Add your site description here... just a short sentence that describes what your blog is going to be about."><?php echo $meta_description; ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="dp-row">
                <div class="dp-icon dp-icon-text"></div>
                <div class="dp-content">Intro Text</div>                
                <a class="dp-link dp-toggle" href="#dp-intro-text"></a>
                <button class="dp-button dp-button-submit" type="submit" name="submit" value="submit">k</button>
            </div>
            
            <div class="dp-sub-panel" id="dp-intro-text">
                <div class="dp-row dp-editable">
                    <div class="dp-icon dp-icon-edit"></div>
                    
                    <div class="dp-content">
                        <label>Intro Title</label>
                        <input type="text" name="intro_title" id="intro_title" value="<?php echo $intro_title; ?>">
                    </div>
                </div>
                
                <div class="dp-row dp-editable">
                    <div class="dp-icon dp-icon-edit"></div>
                    
                    <div class="dp-content">
                        <label>Intro Text</label>
                        <textarea name="intro_text" id="intro_text" rows="12"><?php echo $intro_text; ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="dp-row">
                <div class="dp-icon dp-icon-code"></div>
                <div class="dp-content">Code Injection</div>                
                <a class="dp-link dp-toggle" href="#dp-injection"></a>
                <button class="dp-button dp-button-submit" type="submit" name="submit" value="submit">k</button>
            </div>
            
            <div class="dp-sub-panel" id="dp-injection">
                <div class="dp-row dp-editable">
                    <div class="dp-icon dp-icon-edit"></div>
                    
                    <div class="dp-content">
                        <label>Header Injection</label>
                        <textarea class="dp-code" name="header_inject" id="header_inject" rows="12"><?php echo $header_inject; ?></textarea>
                    </div>
                </div>
                
                <div class="dp-row dp-editable">
                    <div class="dp-icon dp-icon-edit"></div>
                    
                    <div class="dp-content">
                        <label>Footer Injection</label>
                        <textarea class="dp-code" name="footer_inject" id="footer_inject" rows="12"><?php echo $footer_inject; ?></textarea>
                    </div>
                </div>
            </div>
        </form>
        
        <div class="dp-row">
            <div class="dp-icon dp-icon-large dp-icon-grid"></div>
            <div class="dp-content">Installed Templates</div>        
            <a class="dp-link dp-toggle" href="#dp-templates"></a>
        </div>
        
        <div class="dp-sub-panel" id="dp-templates">
            <div class="dp-row dp-templates">
                <?php get_installed_templates('all'); ?>
            </div>
        </div>
        
        <div class="dp-row">
            <div class="dp-icon dp-icon-templates"></div>
            <div class="dp-content">Featured Templates</div>
            <a class="dp-link dp-toggle" href="#dp-featured"></a>
            <span class="dp-number dp-number-dark"><?php count_premium_templates('featured'); ?></span>
        </div>
        
        <div class="dp-sub-panel" id="dp-featured">
            <div class="dp-row dp-templates">
                <?php get_premium_templates('featured'); ?>
            </div>
        </div>
        
        <div class="dp-row">
            <div class="dp-icon dp-icon-templates"></div>
            <div class="dp-content">Popular Templates</div>
            <a class="dp-link dp-toggle" href="#dp-popular"></a>        
            <span class="dp-number dp-number-dark"><?php count_premium_templates('popular'); ?></span>
        </div>
        
        <div class="dp-sub-panel" id="dp-popular">
            <div class="dp-row dp-templates">
                <?php get_premium_templates('popular'); ?>
            </div>
        </div>
        
        <div class="dp-row">
            <div class="dp-icon dp-icon-templates"></div>
            <div class="dp-content">All Templates</div>
            <a class="dp-link dp-toggle" href="#dp-all"></a>
            <span class="dp-number dp-number-dark"><?php count_premium_templates('all'); ?></span>
        </div>
        
        <div class="dp-sub-panel" id="dp-all">
            <div class="dp-row dp-templates">
                <?php get_premium_templates('all'); ?>
            </div>
        </div>
        
        <div class="dp-row">
            <div class="dp-icon dp-icon-large dp-icon-question"></div>
            <div class="dp-content">Need Some Help?</div>
            <a class="dp-link" href="https://github.com/dangerdan/dropplets"></a>
        </div>
        
        <div class="dp-row">
            <div class="dp-icon dp-icon-key"></div>
            <div class="dp-content">Log Out</div>
            <a class="dp-link" href="?action=logout" title="Logout"></a>
        </div>
    </div>
</div>

<div id="dp-uploaded"></div>

<?php } 
    
    public function showMenu() {
        $html = "";
        if (!isset($_SESSION['user'])) {
            $html = $this->loginForm();
        } else {
            $html = $this->internalMenu();
        }
        
        $blog_title = $this->settings->get('blog_title');
        $blog_url = $this->settings->get('blog_url');

        if (isset($_SESSION['user'])) {
        $html .= <<<MARKER
        <script type="text/javascript" src="https://gumroad.com/js/gumroad.js"></script>
        <script type="text/javascript" src="$blog_url/dropplets/includes/js/uploader.js"></script>
MARKER;
        }
        
        $html .= <<<MARKER
        <a class="dp-open dp-icon-dropplets" id="dp-dropplets-icon" href="#dp-dropplets"></a>

        <script type="text/javascript" src="$blog_url/dropplets/includes/js/cookies.js"></script>
        <script type="text/javascript" src="$blog_url/dropplets/includes/js/javascript.js"></script>
MARKER;


        return $html;
    }
}
