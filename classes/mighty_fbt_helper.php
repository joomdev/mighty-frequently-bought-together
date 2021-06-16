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
                'pricing_method' => 'sale_price',
                'default_product' => 'related',
                'box_position' => 'woocommerce_after_single_product_summary',
                'layout' => 'view_1',
                'uninstall_data' =>'on'
            ];
           
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

            update_option('mighty_fbt_label-style_data', $styling_data);
        }
    }

}
