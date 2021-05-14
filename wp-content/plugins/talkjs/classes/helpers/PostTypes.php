<?php

	namespace TalkJS\Helpers;


	class PostTypes{


		/**
		 * Return all (public) post-types
		 *
		 * @return Array
		 */
		public static function all()
		{
			$post_types = get_post_types();

	    	$response = array();
	    	$hidden = static::getHiddenPostTypes();
	    	foreach( $post_types as $pt ){

	    		$cpt = get_post_type_object( $pt );
	    		if( !in_array( $pt, $hidden ) )
	    			$response[] = array(
	    				'value' => $pt,
	    				'label' => $cpt->labels->singular_name
					);
	    	}

	    	return $response;
		}


		/**
		 * Returns an array of all post types that should be hidden
		 *
		 * @return Array
		 */
		public static function getHiddenPostTypes()
		{
			$response = array(
				'revision',
				'nav_menu_item',
				'attachment',
				'custom_css',
				'customize_changeset',
				'shop_order',
				'shop_order_refund',
				'shop_coupon',
				'shop_webhook'
			);

			return apply_filters( 'talkjs_hidden_post_types_for_chat_popup', $response );
		}
	}
