<?php
/**
 * @package      CMB2\Field_Order
 * @author       Tsunoa
 * @copyright    Copyright (c) Tsunoa
 *
 * Plugin Name: CMB2 Field Type: Order
 * Plugin URI: https://github.com/rubengc/cmb2-field-order
 * GitHub Plugin URI: https://github.com/rubengc/cmb2-field-order
 * Description: CMB2 field type to allow pick an order of predefined options.
 * Version: 1.0.1
 * Author: Tsunoa
 * Author URI: https://tsunoa.com/
 * License: GPLv2+
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
            add_action( 'admin_enqueue_scripts', array( $this, 'setup_admin_scripts' ) );
            add_action( 'cmb2_render_order', array( $this, 'render' ), 10, 5 );
            add_action( 'cmb2_sanitize_order', array( $this, 'sanitize' ), 10, 4 );
        }

        /**
         * Render field
         */
        public function render( $field, $value, $object_id, $object_type, $field_type ) {
            $field_name = $field->_name();

            $options = (array) $field->args( 'options' );

            if ( is_callable( $field->args( 'options_cb' ) ) ) {
                $options_cb = call_user_func( $field->args( 'options_cb' ), $field );

                if ( $options_cb && is_array( $options_cb ) ) {
                    $options = $options_cb + $options;
                }
            }

            if( $options && is_array( $options ) ) {

                if( ! isset( $value ) || empty( $value ) || ! is_array( $value ) ) {
                    $value = array();

                    // Initialize value if not exists or is empty
                    foreach( $options as $key => $option) {
                        $value[] = $key;
                    }
                } else {
                    // Check if all options exists in $value
                    foreach( $options as $key => $option) {
                        if( ! in_array( $key, $value ) ) {
                            $value[] = $key;
                        }
                    }
                } ?>

                <ul class="cmb-order-items <?php echo ( $field->args( 'inline' ) ? 'cmb-order-inline' : '' ); ?>" id="<?php echo $field_name; ?>_items">

                <?php foreach( $value as $key ) :
                    if( isset( $options[$key] ) ) : ?>
                        <li>
                            <input type="hidden" name="<?php echo $field_name ; ?>[]" value="<?php echo $key; ?>"><span><?php echo $options[$key]; ?></span>
                        </li>
                    <?php endif;
                endforeach; ?>

                </ul>

                <?php
            }

            $field_type->_desc( true, true );

        }

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
            wp_register_script( 'cmb-field-order', plugins_url( 'js/order.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), self::VERSION, true );
            wp_enqueue_script( 'cmb-field-order' );

            wp_enqueue_style( 'cmb-field-order', plugins_url( 'css/order.css', __FILE__ ), array(), self::VERSION );
            wp_enqueue_style( 'cmb-field-order' );

        }

    }

    $cmb2_field_order = new CMB2_Field_Order();
}
