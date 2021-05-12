<?php

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

class Mighty_panel
{

    const PLG_SLUG = 'mighty-fbt';

    public function __construct()
    {

        add_action( 'admin_enqueue_scripts', [ $this, 'mighty_enqueue_scripts' ] );

        // Init Plugin
        add_action('admin_menu', [$this, 'add_fbt_menu'], 22);

        // Fire before the WC_Form_Handler::add_to_cart_action callback.
        add_action( 'wp_loaded', [ $this, 'mighty_woocommerce_add_multiple_products_to_cart' ], 15 );

        
    }
   
    
    public function mighty_enqueue_scripts( $hook )
    {
        wp_enqueue_style(
            'mighty-style',
            MIGHTY_FBT_PLG_URL . 'assets/css/style.css',
            null,
            MIGHTY_FBT_VERSION
        );

        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_script( 'mighty-color-script', MIGHTY_FBT_PLG_URL . 'assets/js/color-script.js', array( 'wp-color-picker' ), MIGHTY_FBT_VERSION, true );
        
}

    public function add_fbt_menu()
    {
        add_menu_page(
            __('Mighty FBT', 'mighty-fbt'),
            __('Mighty FBT', 'mighty-fbt'),
            'manage_options',
            'mighty-fbt-home',
            [$this, 'generate_homepage'],
            MIGHTY_FBT_PLG_URL . 'assets/images/mighty-themes-logo.svg'

            
        );

        add_submenu_page(
            'mighty-fbt-home',
            __('Mighty Settings', 'mighty-fbt'),
            __('Settings', 'mighty-fbt'),
            'manage_options',
            'mighty-fbt-home',
            [$this, 'generate_homepage']

        );

        add_submenu_page(
            'mighty-fbt-home',
            __('Mighty Label & Styling', 'mighty-fbt'),
            __('Label & Styling', 'mighty-fbt'),
            'manage_options',
            'mighty-fbt-labelstyle',
            [$this, 'generate_labelstylepage']
        );
    }

    public function generate_homepage()
    {
        $setting_data = get_option('mighty_fbt_setting_data');
        if ( isset(  $_POST['submit'] ) ) {
            $data = [
                'version' => MIGHTY_FBT_VERSION,
                'pricing_method' => sanitize_text_field($_POST['pricing_method']),
                'default_product' => sanitize_text_field($_POST['default_product']),
                'box_position' => sanitize_text_field($_POST['box_position']),
                'layout' => sanitize_text_field($_POST['layout']),
                'uninstall_data' =>sanitize_text_field($_POST['uninstall_data'])
            ];

            update_option( 'mighty_fbt_setting_data', $data );
            $setting_data = $data;
        }


        include_once MIGHTY_FBT_DIR_PATH . 'includes/mighty_fbt_header.php';

        require_once MIGHTY_FBT_DIR_PATH  . 'panel/pages/mighty_fbt_general.php';
    }

    public function generate_labelstylepage()
    {
        $styling = get_option('mighty_fbt_label-style_data');

        if ( isset( $_POST['submit'] ) ) {
            
            $data = [
                'version' => MIGHTY_FBT_VERSION,
                'box_title' => sanitize_text_field($_POST['box_title']),
                'total_label' => sanitize_text_field($_POST['total_label']),
                'button_label' => sanitize_text_field($_POST['button_label']),
                'button_color' => sanitize_text_field($_POST['button_color']),
                'button_text_color' => sanitize_text_field($_POST['button_text_color']),
                'button_hover_color' => sanitize_text_field($_POST['button_hover_color']),
                'button_text_hover_color' => sanitize_text_field($_POST['button_text_hover_color']),
            ];
          
            update_option( 'mighty_fbt_label-style_data', $data );
            $styling = $data;
        }


        include_once MIGHTY_FBT_DIR_PATH . 'includes/mighty_fbt_header.php';
        
        require_once MIGHTY_FBT_DIR_PATH  . 'panel/pages/mighty_fbt_labelstyle.php';
    }

    /**
     * Enables adding multiple product in the cart
     * https://dsgnwrks.pro/snippets/woocommerce-allow-adding-multiple-products-to-the-cart-via-the-add-to-cart-query-string/
     * @since   1.0.0
     */
    function mighty_woocommerce_add_multiple_products_to_cart( $url = false ) {
        // Make sure WC is installed, and add-to-cart qauery arg exists, and contains at least one comma.
        if ( ! class_exists( 'WC_Form_Handler' ) || empty( $_REQUEST['add-to-cart'] ) || false === strpos( $_REQUEST['add-to-cart'], ',' ) ) {
            return;
        }
    
        // Remove WooCommerce's hook, as it's useless (doesn't handle multiple products).
        remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );
    
        $product_ids = explode( ',', sanitize_text_field($_REQUEST['add-to-cart']) );
        $count       = count( $product_ids );
        $number      = 0;
    
        foreach ( $product_ids as $id_and_quantity ) {
            // Check for quantities defined in curie notation (<product_id>:<product_quantity>)
            // https://dsgnwrks.pro/snippets/woocommerce-allow-adding-multiple-products-to-the-cart-via-the-add-to-cart-query-string/#comment-12236
            $id_and_quantity = explode( ':', $id_and_quantity );
            $product_id = $id_and_quantity[0];
    
            $_REQUEST['quantity'] = ! empty( $id_and_quantity[1] ) ? absint( $id_and_quantity[1] ) : 1;
    
            if ( ++$number === $count ) {
                // Ok, final item, let's send it back to woocommerce's add_to_cart_action method for handling.
                $_REQUEST['add-to-cart'] = $product_id;
    
                return WC_Form_Handler::add_to_cart_action( $url );
            }
    
            $product_id        = apply_filters( 'woocommerce_add_to_cart_product', absint( $product_id ) );
            $was_added_to_cart = false;
            $adding_to_cart    = wc_get_product( $product_id );
    
            if ( ! $adding_to_cart ) {
                continue;
            }
    
            $add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $adding_to_cart->get_type(), $adding_to_cart );
    
            // Variable product handling
            if ( 'variable' === $add_to_cart_handler ) {
                $this->woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_variable', $product_id );
    
            // Grouped Products
            } elseif ( 'grouped' === $add_to_cart_handler ) {
                $this->woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_grouped', $product_id );
    
            // Custom Handler
            } elseif ( has_action( 'woocommerce_add_to_cart_handler_' . $add_to_cart_handler ) ){
                do_action( 'woocommerce_add_to_cart_handler_' . $add_to_cart_handler, $url );
    
            // Simple Products
            } else {
                $this->mighty_woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_simple', $product_id );
                add_filter( 'woocommerce_add_to_cart_redirect', [ $this ,'redirect_after_add_to_cart' ], 10);
            }
        }
    }
    
    /**
     * Invoke class private method
     *
     * @since   1.0.0
     */
    
    public function mighty_woo_hack_invoke_private_method( $class_name, $methodName ) {

        if ( version_compare( phpversion(), '5.3', '<' ) ) {
            throw new Exception( 'PHP version does not support ReflectionClass::setAccessible()', __LINE__ );
        }
    
        $args = func_get_args();
        unset( $args[0], $args[1] );
        $reflection = new ReflectionClass( $class_name );
        $method = $reflection->getMethod( $methodName );
        $method->setAccessible( true );
    
        $args = array_merge( array( $reflection ), $args );
        return call_user_func_array( array( $method, 'invoke' ), $args );
    }

     
    function redirect_after_add_to_cart( $url ) {
        $site_url = site_url();
        $str =  add_query_arg('/','add-to-cart');
        $new =  explode("?",$str) ;
        $new_url = $url . $new[0];
      
      
        return $new_url;
    }
}

new Mighty_panel();
