<?php

/**
 * Plugin Name: Mighty Frequently Bought Together
 * Description: Give more choices to users when purchasing your product by showing <strong>Frequently Bought Together</strong> products.
 * Plugin URI: https://mightythemes.com/products/mighty-frequently-bought-together/
 * Version:     1.0.0
 * Author:      MightyThemes
 * Author URI:  https://mightythemes.com/
 * Text Domain: mighty-frequently-bought-together
 * WC requires at least: 4.2.0
 * WC tested up to: 5.2
 */

use MightyFBT\Classes\MightyHelper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

define( 'MIGHTY_FBT_VERSION', '1.0.0' );
define( 'MIGHTY_FBT_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'MIGHTY_FBT_PLG_URL', plugin_dir_url( __FILE__ ) );
define( 'MIGHTY_FBT_PLG_BASENAME', plugin_basename( __FILE__ ) );

class Mighty_fbt
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'mighty_enqueue_scripts']);

        add_action('plugins_loaded', array($this, 'init'));

        // Creating Default Values
        $this->update_default_values();

        add_filter('woocommerce_product_data_tabs', array($this, 'add_mighty_fbt'), 10, 1);

        add_action('woocommerce_product_data_panels', array($this, 'add_mighty_fbt_panel'));

        $product_types = [
            'simple',
            'variable',
            'grouped',
            'external',
            'rentable',
        ];
        foreach ($product_types as $product_type) {
            add_action('woocommerce_process_product_meta_' . $product_type, array($this, 'mighty_fbt_save_data'), 10, 1);
        }

        include MIGHTY_FBT_DIR_PATH . 'panel/template/mighty_fbt_get_data.php';
    }

    public function mighty_enqueue_scripts()
    {
        wp_enqueue_script(
            'mighty-select2',
            MIGHTY_FBT_PLG_URL . 'assets/js/select2.min.js',
            [ 'jquery' ],
            MIGHTY_FBT_VERSION,
            true // in footer?
        );

        wp_enqueue_style(
            'mighty-select2',
            MIGHTY_FBT_PLG_URL . 'assets/css/select2.min.css',
            null,
            MIGHTY_FBT_VERSION
        );
    }

    public function init()
    {
        require_once MIGHTY_FBT_DIR_PATH . 'classes/mighty_fbt_panel.php';
    }

    // set default value for setting and styling on activation
    public function update_default_values()
    {       
        include MIGHTY_FBT_DIR_PATH . 'classes/mighty_fbt_helper.php';

        MightyHelper::updateDefaultSettings();
        MightyHelper::updateDefaultStyling();
    }

    // show frequently bought together tab in product tab
    public function add_mighty_fbt($tabs)
    {
        $tabs['add_mighty_fbt'] = array(
            'label'  => __('Mighty Frequently Bought Together', 'mighty-fbt'),
            'target' => 'mighty_wfbt_data_option',

        );
        return $tabs;
    }

    // show frequently bought form on click on product tab
    public function add_mighty_fbt_panel()
    {
        $product_data = get_option('mighty_fbt_save_data');
        $page_id = get_the_ID();
        if( isset( $product_data[$page_id] ) ) {
            $current_product_data = $product_data[$page_id];
        }
        require_once MIGHTY_FBT_DIR_PATH . 'panel/pages/mighty_fbt_product.php';
    }

    // save data in wp_option of the form of frequently bought together
    function mighty_fbt_save_data()
    {
        $page_id = get_the_ID();
        $data['product_type'] = sanitize_text_field($_POST['product_type']);
        if ($data['product_type'] == 'custom_selection') {
            $data['selected_products'] = sanitize_text_field($_POST['selected_products']);
        }
        $data['show_product'] = sanitize_text_field($_POST['show_product']);
        $data['num_of_product'] = sanitize_text_field($_POST['num_of_product']);
        $get_data = get_option('mighty_fbt_save_data');
        
        if (!empty($get_data)) {
            if (array_key_exists($page_id, $get_data)) {
                unset($get_data[$page_id]);
                $mighty_fbt_data = array(
                    $page_id => $data,
                );
                $mighty_fbt_data = $get_data + $mighty_fbt_data;
                update_option('mighty_fbt_save_data', $mighty_fbt_data);
            } else {
                $mighty_fbt_data = array(
                    $page_id => $data,
                );
                $mighty_fbt_data = $get_data + $mighty_fbt_data;
                update_option('mighty_fbt_save_data', $mighty_fbt_data);
            }
        } else {
            $mighty_fbt_data = array(
                $page_id => $data,
            );
            update_option('mighty_fbt_save_data', $mighty_fbt_data);
        }
    }
}

new Mighty_fbt();
