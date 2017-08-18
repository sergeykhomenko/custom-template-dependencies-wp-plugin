<?php

/*
Plugin Name: Custom Template Dependencies
Plugin URI:  https://untitled-production.com/
Description: Create dependencies between your page templates and custom fields. Auto-creation of custom fields for different templates.
Version:     0.0.3
Author:      Sergey Khomenko
Author URI:  https://untitled-production.com/
*/

add_filter( 'content_save_pre' , array('Custom_Template_Dependencies', 'meta_keyer') );

class Custom_Template_Dependencies {

	public function load_option_page() {
		$plugin_options = get_option( 'ctp_plugin_options' );

		// Redraw options page

		update_option( 'ctp_plugin_options', $plugin_options );
	}



	public function meta_keyer( $content ){
		$req = $_REQUEST;

		if( isset($req['post_ID']) && isset($req['page_template']) ){

			$existing_page_types = array( 'in_category' , 'in_state' );
			$key_name = '';

			foreach($existing_page_types as $page_type){
				delete_post_meta( $req['post_ID'] , $page_type );
			}

			switch( $req['page_template'] ){
				case 'payments.php':
					$key_name = 'in_category';
				break;
				default:
					$key_name = false;
				break;
			}

			if($key_name)
				add_post_meta( $req['post_ID'] , $key_name, 1 );

		}

		return $content;
	}

}

?>