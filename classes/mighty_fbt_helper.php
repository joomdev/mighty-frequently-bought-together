<?php

namespace MightyFBT\Classes;

if (!defined('ABSPATH')) {
    exit;
}

class MightyHelper
{

    // default value for setting tab form on plugin activation
    public static function updateDefaultSettings()
    {
        if (

            get_option('mighty_fbt_setting_data') &&
            isset(get_option('mighty_fbt_setting_data')['version']) &&
            get_option('mighty_fbt_setting_data')['version'] === MIGHTY_FBT_VERSION
            
        ) {
            // do nothing
        } else {

            $setting_data = [
                'version' => MIGHTY_FBT_VERSION,
                'default_product' => 'related',
                'product_character_limit' => '',
                'box_position' => 'woocommerce_before_add_to_cart_form',
                'layout' => 'view_1',
                'uninstall_data' =>'on'
            ];

            if(constant('MIGHTY_FBT_PRO') && defined('MIGHTY_FBT_PRO')){

                if (!class_exists('mighty_pro_view')) {

                require MIGHTY_FBT_DIR_PATH  . 'pro/classes/pro_helper.php';

                }

                $setting_data = array_merge($setting_data,$pro_setting_data);

            }
           
            update_option('mighty_fbt_setting_data', $setting_data);
        }
    }

    // default value for frequently bought together styling on plugin activation
    public static function updateDefaultStyling()
    {

        if (

            get_option('mighty_fbt_label-style_data') &&
            isset(get_option('mighty_fbt_label-style_data')['version']) &&
            get_option('mighty_fbt_label-style_data')['version'] === MIGHTY_FBT_VERSION

        ) {
            // do nothing
        } else {

            $styling_data = [
                'version' => MIGHTY_FBT_VERSION,
                'box_title' => 'Frequently Bought Together',
                'total_label' => 'Price for all',
                'button_label' => 'Add all to Cart',
                'button_color' => '#FA8900',
                'button_text_color' => '#fff',
                'button_hover_color' => '#183e95',
                'button_text_hover_color' => '#fff',
            ];

            if(constant('MIGHTY_FBT_PRO') && defined('MIGHTY_FBT_PRO')){

                if (!class_exists('mighty_pro_view')) {

                require MIGHTY_FBT_DIR_PATH  . 'pro/classes/pro_helper.php';

                }
              
                $styling_data = array_merge($styling_data,$pro_styling_data);

            }
           
            update_option('mighty_fbt_label-style_data', $styling_data);
        }
    }

}
