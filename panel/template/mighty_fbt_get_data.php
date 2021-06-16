<?php
if (!defined('ABSPATH')) {
    exit;
}
session_start();

class Mighty_fbt_page
{
    public function __construct()
    {

        add_action('wp_enqueue_scripts', [$this, 'mighty_enqueue_scripts']);

        add_action(get_option('mighty_fbt_setting_data')['box_position'], [$this, 'add_fbt_module'], 1);

        add_shortcode('mighty_fbt_data', [$this, 'mighty_fbt_show_data']);
        add_filter('woocommerce_cart_calculate_fees', [$this, 'add_fees'], 10);

    }

    public static function add_fbt_module()
    {

        $page_setting_data = get_option('mighty_fbt_setting_data');
        $product_data = get_option('mighty_fbt_save_data');

        $product_id = get_the_ID();

        $_SESSION['id'] = $product_id;

        if ($page_setting_data['default_product'] != 'none' || !empty($product_data[$product_id])) {
            do_shortcode('[mighty_fbt_data]');
        }
    }

    public function mighty_enqueue_scripts()
    {
        wp_enqueue_style('mighty_fbt_style', MIGHTY_FBT_PLG_URL . 'assets/css/fbt.css'); 

        wp_enqueue_style('mighty_fbt_main_style', MIGHTY_FBT_PLG_URL . 'assets/css/main.css');

        wp_enqueue_script(
            'mighty_fbt_view_script',
            MIGHTY_FBT_PLG_URL . 'assets/js/product_view.js',
            ['jquery'],
            MIGHTY_FBT_VERSION,
            true // in footer?
        );

        $label_style_data = get_option('mighty_fbt_label-style_data');

        wp_localize_script('mighty_fbt_view_script', 'fbt', array(

            'currentURL' => get_permalink(get_the_ID()),
            'id' => get_the_ID(),
            'total_label' => isset($label_style_data['total_label']) ? $label_style_data['total_label'] : '',
            'button_label' => isset($label_style_data['button_label']) ? $label_style_data['button_label'] : '',

        ));

        $inline_css = "
        .mt-fbt-button {
            color:{$label_style_data['button_text_color']};
            background-color :{$label_style_data['button_color']};
            font-size: unset;

        }
        .mt-fbt-button:hover {
            color:{$label_style_data['button_text_hover_color']};
            background :{$label_style_data['button_hover_color']};

        }";

        wp_add_inline_style('mighty_fbt_main_style', $inline_css);
    }

    // function to get all the data to show frequently bought together

    public function mighty_fbt_show_data()
    {
        $product_id = get_the_ID();
        
        $product = wc_get_product($product_id);

        $setting_data = get_option('mighty_fbt_setting_data');

        $curreny_symbol = get_woocommerce_currency_symbol();
        if(!empty($product->get_image_id())){

        $current_product_image = wp_get_attachment_image_url($product->get_image_id());
        if(empty($current_product_image)){
            $current_product_image = wc_placeholder_img_src();
        }
        } else{
            $current_product_image = wc_placeholder_img_src();
        }

        if ($product->get_sale_price()) {

            $current_sale_price = $product->get_sale_price();
        } else {
            $current_sale_price = 0;
        }

        if ($product->get_regular_price()) {

            $current_regular_price = $product->get_regular_price();
        } else {
            $current_regular_price = 0;
        }

        $terms = wp_get_post_terms($product_id, 'product_cat');
 		$category_ids = [];
        foreach ($terms as $term) {

             $product_cat_name = $term->name;
			 $args = array(
            'post_type' => 'product',
            'posts_per_page' => 10,
            'product_cat' => $product_cat_name,
        );

        $products_all = new WP_Query($args);
                foreach ($products_all->posts as $key => $value) {

                if ($value->ID != $product_id) {
                    array_push($category_ids, $value->ID);
                }
            }
        }
        
        $alternates_products = $category_ids;

        $product_data = get_option('mighty_fbt_save_data');
        
            if(!empty($product_data)){

            $current_product = array_key_exists($product_id, $product_data) ? $product_data[$product_id] : '';  

			}
                
                    if ((!empty($current_product['product_type']) && $current_product['product_type'] == 'use_related') || $setting_data['default_product'] == 'related') {

                        $no_of_product = (isset($current_product['num_of_product'])) ? $current_product['num_of_product'] : '';
                        $alternates_products = $category_ids;

                    }
                
                if (isset($current_product) && isset($current_product['product_type']) == 'custom_selection' && isset($current_product['selected_products'])) {

                    $alternates_products = $current_product['selected_products'];
                    $no_of_alternate_product = count($alternates_products);
                    $no_of_product = (isset($current_product['num_of_product'])) ? $current_product['num_of_product'] : '';

                }

                if($alternates_products) {
					

                    $no_of_alternate_product = count($alternates_products);

                    if ( (isset($no_of_product) && !empty($no_of_product) ) && $no_of_product <= $no_of_alternate_product && $current_product['show_product'] == 'random_limited_products') {

                        $number = $no_of_alternate_product - $no_of_product;

                        $alternates_products_rand = array_slice($alternates_products, $number);


                    } else if ( (isset($no_of_product) && !empty($no_of_product) ) && $no_of_product > $no_of_alternate_product && $current_product['show_product'] == 'all_selected') {
                        $alternates_products_rand = $alternates_products; 


                    } else if((empty($no_of_product) && (isset($current_product['show_product'])  && $current_product['show_product']== 'all_selected')) || (!empty($no_of_product) && $no_of_product > $no_of_alternate_product && $current_product['show_product'] == 'random_limited_products')) {
                        

                        $alternates_products_rand = $alternates_products;

                    } else {

                        if(count($alternates_products) <= 2){

                            $alternates_products_rand = $alternates_products;

                        } else{
                            $alternates_products_rand = [$alternates_products[rand(0,count($alternates_products)-1)],$alternates_products[rand(0,count($alternates_products)-1)]];

                        }
                       
                    }
                }
        if (($setting_data['layout'] == 'view_1' && (!empty($alternates_products_rand))) && ($setting_data['default_product'] != 'none' || $current_product['product_type'] != '')) {

            $label_style_data = get_option('mighty_fbt_label-style_data');

            require_once MIGHTY_FBT_DIR_PATH . 'panel/template/mighty_fbt_view1.php';
        }

        if (($setting_data['layout'] == 'view_2' && (!empty($alternates_products_rand))) && ($setting_data['default_product'] != 'none' || $current_product['product_type'] != '')) {

            $label_style_data = get_option('mighty_fbt_label-style_data');

            require_once MIGHTY_FBT_DIR_PATH . 'pro/panel/template/mighty_fbt_view2.php';
        }

    }
    
    public function add_fees()
    {

        $product_id = (isset($_SESSION['id']) ? $_SESSION['id'] : '');

        $product_cart_ids = [];

        foreach (WC()->cart->get_cart() as $cart_item) {

            array_push($product_cart_ids, $cart_item['product_id']);
        }

        $product_cart_id = WC()->cart->generate_cart_id($product_id);

        $in_cart = WC()->cart->find_product_in_cart($product_cart_id);

        if (!empty($in_cart && $product_cart_ids[0] == $product_id)) {

            $current_product = get_option('mighty_fbt_save_data');

            $current_product = $current_product[$product_id];

            $total_product = WC()->cart->get_cart_contents_count();

            $total_amount = floatval(WC()->cart->get_cart_contents_total());

            if (isset($current_product['discount']) && $current_product['discount'] == 'on') {

                $discount_type = $current_product['discount_type'];

                $discount_value = $current_product['discount_value'];

                if (($current_product['apply_condition_discount'] == 'on') && ((!empty($current_product['discount_user_spend']) && $current_product['discount_user_spend'] <= $total_amount) || (!empty($current_product['discount_user_choose']) && $total_product >= $current_product['discount_user_choose']))) {

                    if ($discount_type == 'flat') {

                        $saving_amount = number_format($discount_value, 2);
                    }

                    if ($discount_type == 'percentage') {

                        $saving_amount = number_format((($total_amount * $discount_value) / 100), 2);
                    }

                    WC()->cart->add_fee(get_option('mighty_fbt_setting_data')['discount_name'], -$saving_amount, true);
                }
            }
        }
    }
}
new Mighty_fbt_page();
