<?php

namespace Dropplets;

class PostHelper {
	public function get_all_posts($options = array()) {
		$category = null;
		
		$settings = Settings::instance ();
		if ($handle = opendir ( $settings->posts_dir )) {
			
			$files = array ();
			$filetimes = array ();
			
			while ( false !== ($entry = readdir ( $handle )) ) {
				if (substr ( strrchr ( $entry, '.' ), 1 ) == ltrim ( $settings->get ( "file_ext" ), '.' )) {
					
					// Define the post file.
					$fcontents = file ( $settings->posts_dir . $entry );
					
					// Define the post title.
					$post_title = \Michelf\Markdown::defaultTransform ( $fcontents [0] );
					
					// Define the post author.
					$post_author = str_replace ( array (
							"\n",
							'-' 
					), '', $fcontents [1] );
					
					// Define the post author Twitter account.
					$post_author_twitter = str_replace ( array (
							"\n",
							'- ' 
					), '', $fcontents [2] );
					
					// Define the published date.
					$post_date = str_replace ( '-', '', $fcontents [3] );
					
					// Define the post category.
					$post_category = str_replace ( array (
							"\n",
							'-' 
					), '', $fcontents [4] );
					
					// Early return if we only want posts from a certain category
					if (isset ( $options ["category"] ) && $options ["category"] && $options ["category"] != trim ( strtolower ( $post_category ) )) {
						continue;
					}
					
					// Define the post status.
					$post_status = str_replace ( array (
							"\n",
							'- ' 
					), '', $fcontents [5] );
					
					// Define the post intro.
					$post_intro = \Michelf\Markdown::defaultTransform ( $fcontents [7] );
					
					// Define the post content
					$post_content = \Michelf\Markdown::defaultTransform ( join ( '', array_slice ( $fcontents, 6, count ( $fcontents ) - 1 ) ) );
					
					// Pull everything together for the loop.
					$files [] = array (
							'fname' => $entry,
							'post_title' => $post_title,
							'post_author' => $post_author,
							'post_author_twitter' => $post_author_twitter,
							'post_date' => $post_date,
							'post_category' => $post_category,
							'post_status' => $post_status,
							'post_intro' => $post_intro,
							'post_content' => $post_content 
					);
					$post_dates [] = $post_date;
					$post_titles [] = $post_title;
					$post_authors [] = $post_author;
					$post_authors_twitter [] = $post_author_twitter;
					$post_categories [] = $post_category;
					$post_statuses [] = $post_status;
					$post_intros [] = $post_intro;
					$post_contents [] = $post_content;
				}
			}
			array_multisort ( $post_dates, SORT_DESC, $files );
			return $files;
		} else {
			return false;
		}
	}
	function get_posts_for_category($category) {
		$category = trim ( strtolower ( $category ) );
		return get_all_posts ( array (
				"category" => $category 
		) );
	}
	public function get_post_image_url($filename) {
		$settings = Settings::instance ();
		
		$supportedFormats = array (
				"jpg",
				"png",
				"gif" 
		);
		$slug = pathinfo ( $filename, PATHINFO_FILENAME );
		
		foreach ( $supportedFormats as $fmt ) {
			$imgFile = sprintf ( "%s%s.%s", $settings->posts_dir, $slug, $fmt );
			if (file_exists ( $imgFile ))
				return sprintf ( "%s/%s.%s", "${blog_url}posts", $slug, $fmt );
		}
		
		return false;
	}
	public function get_twitter_profile_img($username) {
		
		// Get the cached profile image.
		$cache = IS_CATEGORY ? '.' : '';
		$array = explode ( '/category/', $_SERVER ['REQUEST_URI'] );
		if (isset ( $array [1] ))
			$array = explode ( '/', $array [1] );
		if (count ( $array ) != 1)
			$cache .= './.';
		$cache .= './cache/';
		$profile_image = $cache . $username . '.jpg';
		
		// Cache the image if it doesn't already exist.
		if (! file_exists ( $profile_image )) {
			$image_url = 'http://twitter.com/' . $username . '/profile_image?size=original';
			$image = file_get_contents ( $image_url );
			if (is_dir ( $cache )) {
				file_put_contents ( $cache . $username . '.jpg', $image );
			}
		}
		
		// Return the image URL.
		return $profile_image;
	}
}