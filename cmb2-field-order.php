<?php
/*
Plugin Name: CMB2 Field Type: Order
Plugin URI: https://github.com/rubengc/cmb2-field-order
GitHub Plugin URI: https://github.com/rubengc/cmb2-field-order
Description: CMB2 field type to allow pick an order of predefined options.
Version: 1.0.0
Author: Ruben Garcia
Author URI: http://rubengc.com/
License: GPLv2+
*/


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'CMB2_Field_Order' ) ) {
    /**
     * Class CMB2_Field_Order
     */
    class CMB2_Field_Order {

        /**
         * Current version number
         */
        const VERSION = '1.0.0';

        /**
         * Initialize the plugin by hooking into CMB2
         */
        public function __construct() {
            add_action( 'cmb2_render_order', array( $this, 'render' ), 10, 5 );
            add_action( 'cmb2_sanitize_order', array( $this, 'sanitize' ), 10, 4 );
        }

        /**
         * Render field
         */
        public function render( $field, $value, $object_id, $object_type, $field_type ) {
            $this->setup_admin_scripts();

            $field_name = $field->_name();

            if( is_array( $field->args( 'options' ) ) ){
                echo '<ul class="cmb-order-items ' . ( $field->args( 'inline' ) ? 'cmb-order-inline' : '' ) .  '" id="' . $field_name . '_items">';

                if( ! isset( $value ) || empty( $value ) ) {
                    foreach( $field->args( 'options' ) as $key => $option) {
                        $value[] = $key;
                    }
                }

                foreach($value as $order => $key){
                    echo '<li><input type="hidden" name="'.$field_name.'[]" value="'.$key.'"><span>'.$field->args( 'options' )[$key].'</span></li>';
                }

                echo '</ul>';
            }

            $field_type->_desc( true, true );

        }

        /**
         * Optionally save the latitude/longitude values into two custom fields
         */
        public function sanitize( $override_value, $value, $object_id, $field_args ) {
            $fid = $field_args['id'];

            if( $field_args['render_row_cb'][0]->data_to_save[$fid] ) {
                $value = $field_args['render_row_cb'][0]->data_to_save[$fid];
            } else {
                $value = false;
            }

            return $value;
        }

        /**
         * Enqueue scripts and styles
         */
        public function setup_admin_scripts() {
            wp_register_script( 'cmb-field-order', plugins_url( 'js/order.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), self::VERSION );
            wp_enqueue_script( 'cmb-field-order' );

            wp_enqueue_style( 'cmb-field-order', plugins_url( 'css/order.css', __FILE__ ), array(), self::VERSION );
            wp_enqueue_style( 'cmb-field-order' );

        }

    }

    $cmb2_field_order = new CMB2_Field_Order();
}
