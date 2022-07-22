<?php
if (!defined('ABSPATH')) {
    exit;
}
class Mighty_fbt_page
{
    public function __construct()
    {

        add_action('wp_enqueue_scripts', [$this, 'mighty_enqueue_scripts']);

        add_action(get_option('mighty_fbt_setting_data')['box_position'], [$this, 'add_fbt_module'], 1);

        add_shortcode('mighty_fbt_data', [$this, 'mighty_fbt_show_data']);

    }
    public static function add_fbt_module()
    {

        $page_setting_data = get_option('mighty_fbt_setting_data');
        $product_data = get_option('mighty_fbt_save_data');

        $product_id = get_the_ID();
        $cart_main_product_ids = get_option('mighty_cart_products_ids');
        if (empty($cart_main_product_ids)) {
            $cart_main_product_ids = [];
            array_push($cart_main_product_ids, $product_id);
            update_option('mighty_cart_products_ids', $cart_main_product_ids);
        } else {
            if (!in_array($product_id, $cart_main_product_ids)) {
                array_push($cart_main_product_ids, $product_id);
                update_option('mighty_cart_products_ids', $cart_main_product_ids);
            }
        }

        if ($page_setting_data['default_product'] != 'none' || !empty($product_data[$product_id])) {
            do_shortcode('[mighty_fbt_data]');
        }
    }

    public function mighty_enqueue_scripts()
    {
        wp_enqueue_style('mighty_fbt_main_style', MIGHTY_FBT_PLG_URL . 'assets/css/main.css');

        wp_enqueue_script(
            'mighty_fbt_view_script',
            MIGHTY_FBT_PLG_URL . 'assets/js/product_view.js',
            ['jquery'],
            MIGHTY_FBT_VERSION,
            true// in footer?
        );

        $label_style_data = get_option('mighty_fbt_label-style_data');

        wp_localize_script('mighty_fbt_view_script', 'fbt', array(

            'currentURL' => get_permalink(get_the_ID()),
            'isPro' => (defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) ? MIGHTY_FBT_PRO : '',
            'id' => get_the_ID(),
            'button_label_single' => isset($label_style_data['button_label_single']) ? $label_style_data['button_label_single'] : '',
            'total_label_single' => isset($label_style_data['total_label_single']) ? $label_style_data['total_label_single'] : '',
            'button_label_triple' => isset($label_style_data['button_label_triple']) ? $label_style_data['button_label_triple'] : '',
            'button_label_double' => isset($label_style_data['button_label_double']) ? $label_style_data['button_label_double'] : '',
            'total_label_double' => isset($label_style_data['total_label_double']) ? $label_style_data['total_label_double'] : '',
            'total_label_triple' => isset($label_style_data['total_label_triple']) ? $label_style_data['total_label_triple'] : '',
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

        if (defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) {
            $image_height = isset($label_style_data['image_height']) ? $label_style_data['image_height'] : '100';
            $image_width = isset($label_style_data['image_width']) ? $label_style_data['image_width'] : '100';
             $inline_css .= "
             .mt-fbt-image img{
                 height:{$image_height}px;
                 width :{$image_width}px;
             }
             ";
         }

        wp_add_inline_style('mighty_fbt_main_style', $inline_css);
    }

    // function to get all the data to show frequently bought together

    public function mighty_fbt_show_data()
    {
        $product_id = get_the_ID();

        $product = wc_get_product($product_id);

        $setting_data = get_option('mighty_fbt_setting_data');

        $curreny_symbol = get_woocommerce_currency_symbol();

        if ( !empty ( $product ) ) {
        if ( !empty($product->get_image_id() ) ) {

            $current_product_image = wp_get_attachment_image_url($product->get_image_id());
            if (empty($current_product_image)) {
                $current_product_image = wc_placeholder_img_src();
            }
        } else {
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

        if (!empty($product_data)) {

            $current_product = array_key_exists($product_id, $product_data) ? $product_data[$product_id] : '';
        }
        if ((!empty($current_product['product_type']) && $current_product['product_type'] == 'use_related') || (empty($current_product['product_type']) && $setting_data['default_product'] == 'related')) {

            $no_of_product = (isset($current_product['num_of_product'])) ? $current_product['num_of_product'] : '';
            $alternates_products = $category_ids;
        }
        if (defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) {

            require_once MIGHTY_FBT_DIR_PATH . 'pro/panel/template/pro_mighty_fbt_get_data.php';

            $pro_setting_data = new Mighty_fbt_page_pro();
            $current_product = isset($current_product) ? $current_product : '';
            $products = $pro_setting_data->setting_product($setting_data, $product, $current_product);

            if ($products) {

                $alternates_products = $products['alternative_product'];

                $no_of_product = $products['no_of_product'];
            }
        }

        if (isset($current_product) && isset($current_product['product_type']) == 'custom_selection' && isset($current_product['selected_products'])) {

            $alternates_products = $current_product['selected_products'];
            $no_of_alternate_product = count($alternates_products);
            $no_of_product = (isset($current_product['num_of_product'])) ? $current_product['num_of_product'] : '';
        }

        if ($alternates_products) {

            $no_of_alternate_product = count($alternates_products);

            if ((isset($no_of_product) && !empty($no_of_product)) && $no_of_product <= $no_of_alternate_product && $current_product['show_product'] == 'random_limited_products') {

                $number = $no_of_alternate_product - $no_of_product;

                $random_values = [];

                while ( count($random_values) != $no_of_product ) {
                    $alter_product_id = $alternates_products[rand(0, count($alternates_products) - 1)];
                    if( !in_array( $alter_product_id, $random_values ) ) {
                        array_push( $random_values, $alter_product_id ) ;
                    } 
                }
                $alternates_products_rand = $random_values;

            } else if ((isset($no_of_product) && !empty($no_of_product)) && $no_of_product > $no_of_alternate_product && $current_product['show_product'] == 'all_selected') {
                $alternates_products_rand = $alternates_products;
            } else if ((empty($no_of_product) && (isset($current_product['show_product']) && $current_product['show_product'] == 'all_selected')) || (!empty($no_of_product) && $no_of_product > $no_of_alternate_product && $current_product['show_product'] == 'random_limited_products')) {

                $alternates_products_rand = $alternates_products;
            } else {

                if (count($alternates_products) <= 2) {

                    $alternates_products_rand = $alternates_products;
                } else {

                    $random_values = [];
					while ( count($random_values) != 2 ) {
						$alter_product_id = $alternates_products[rand(0, count($alternates_products) - 1)];
						if( !in_array( $alter_product_id, $random_values ) ) {
							array_push( $random_values, $alter_product_id ) ;
						} 
					}
						
				}
                    $alternates_products_rand = $random_values;
                }
            
        }
        if(!empty($alternates_products_rand) && !empty ( $product_data[$product_id] )){
        $cart_data = get_option('mighty_cart_discount');
        if (!empty($cart_data)) {

            if (array_key_exists($product_id, $cart_data)) {

                unset($cart_data[$product_id]);

                $mighty_fbt_data = array(
                    $product_id => $alternates_products_rand,
                );

                $mighty_fbt_data = $cart_data + $mighty_fbt_data;

                update_option('mighty_cart_discount', $mighty_fbt_data);
            } else {

                $mighty_fbt_data = array(
                    $product_id => $alternates_products_rand,
                );

                $mighty_fbt_data = $cart_data + $mighty_fbt_data;

                update_option('mighty_cart_discount', $mighty_fbt_data);
            }
        } else {

            $mighty_fbt_data = array(
                $product_id => $alternates_products_rand,
            );
            update_option('mighty_cart_discount', $mighty_fbt_data);
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
    }

  
}
new Mighty_fbt_page();
