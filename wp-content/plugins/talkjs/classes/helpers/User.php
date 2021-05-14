<?php
namespace TalkJS\Helpers;

use WP_User;

class User extends WP_User {


    /**
     * Check if the user has role.
     *
     * @param string $role
     * @return bool
     */
    public static function hasRole( $role ) {

        $user = wp_get_current_user();
        return in_array( $role, $user->roles );
    }


    /**
     * Set User role.
     *
     * @param string $role
     * @return \Cuisine\User\User
     */
    public static function setRole( $role ) {
        $user = wp_get_current_user();
        $user->set_role($role);
		return $user;
	}


    /**
     * Get the Current User Id
     *
     * @return int
     */
    public static function getId(){

        return get_current_user_id();

    }


    /**
     * Check if the user can do a defined capability.
     *
     * @param string $cap
     * @return bool
     */
    public static function can( $cap ) {

        $user = wp_get_current_user();
        return current_user_can( $cap );

    }


    /**
     * Get an attribute for this user
     *
     * @param  string $attribute name of the attribute
     * @return mixed (result or false )
     */
    public static function getAttribute( $attribute, $default ){

        $user = wp_get_current_user();

        //no current user set:
        if( !isset( $user->ID ) || $user->ID == '' )
            return $default;

        switch( $attribute ){

            case 'email':

                return $user->data->user_email;

            break;

            case 'username':

                return $user->data->user_login;

            break;

            case 'ID':

                return $user->data->ID;

            break;


            case 'name':
                return $user->display_name;
            break;

            case 'avatar':

                return get_avatar_url( $user->data->ID );

            default:

                return get_user_meta( $user->data->ID, $attribute, true );

            break;

        }

        return false;
    }


    /**
     * Check if the user is logged in
     *
     * @return bool
     */
    public static function loggedIn(){
        return is_user_logged_in();
    }


    /**
     * Update the user properties.
     *
     * @param array $userdata
     * @return \TalkJS\Helpers\User | \WP_Error
     */
    public static function update(array $userdata) {
        $user = wp_get_current_user();
        $userdata = array_merge( $userdata, array( 'ID' => $user->ID ) );

        $user = wp_update_user($userdata);

        // if( is_wp_error( $user ) ) return $user;

        return $user;
    }


    /**
     * Returns all WP Users, with certain roles
     *
     * @return Array
     */
    public static function getAll()
    {
        $args = array(
            'role__in' => array( 'administrator', 'editor', 'author' ),
            'number' => -1
		);

        $args = apply_filters( 'talkjs_get_all_users', $args );
        $users = get_users( $args );
        $response = array();

        foreach( $users as $usr ){
            $response[] = array(
                'value' => $usr->data->ID,
                'label' => $usr->data->display_name
			);
        }

        return $response;
    }


    /**
     * Returns a talk object
     *
     * @param  String $type (current, author, anonymous )
     *
     * @return Array
     */
    public static function getTalkObject( $type = 'current', $userId = null )
    {
		global $authordata;

		// Decide type
		if (!is_null( $userId )) { // If you pass a user, type is ignored
			$type = 'userid';
		} else {
			$type = is_null($type) ? 'current' : strtolower($type);
		}

		// Decide user
		$user = null;

        if( $type == 'author' && !empty( $authordata ) ){
			$user = $authordata;
        }else if( $type == 'userid' && !is_null( $userId ) ){
            $user = get_user_by( 'ID', $userId );
        }else if( $type == 'current' && static::loggedIn() ){
            $user = wp_get_current_user();
		}

        if( is_null( $user ) )
            return null;

        $roles = $user->roles;
        $role = array_shift( $roles );

        return array(
            'id' => $user->data->ID,
            'name' => $user->data->display_name,
            'email' => $user->data->user_email,
            'photoUrl' => get_avatar_url( $user->data->ID ),
            'configuration' => strtolower($role) // Make sure it's always lower case
		);
    }

}
