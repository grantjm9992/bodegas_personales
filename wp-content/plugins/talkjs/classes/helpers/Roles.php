<?php

    namespace TalkJS\Helpers;

    use WP_Roles;

    class Roles{

        /**
         * Return all available roles
         *
         * @return void
         */
        public static function all(){

            $roles = new WP_Roles();
            return array_reverse( array_keys( $roles->roles ) );
        }

        /**
         * Returns an array of all roles for checkboxes
         *
         * @return Array
         */
        public static function forCheckboxes(){
            $roles = new WP_Roles();

            $response = array();
            foreach( $roles->roles as $role => $data ){
                $response[] = array( 'label' => $data['name'], 'value' => $role );
            }

            return $response;
        }

        /**
         * Get all role values
         *
         * @return Array
         */
        public static function values(){
            $roles = new WP_Roles();
            $response = array();
            foreach( $roles->roles as $role => $data ){
                $response[] = $role;
            }

            return $response;
        }
    }
