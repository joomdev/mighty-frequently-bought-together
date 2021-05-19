<?php
if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

class Mighty_fbt_page
{
    public function __construct()
    {

        add_action('wp_enqueue_scripts', [$this, 'mighty_enqueue_scripts']);

        add_action(get_option('mighty_fbt_setting_data')['box_position'], [$this, 'add_fbt_module'], 1);
        
        add_shortcode('mighty_fbt_data', [$this, 'mighty_fbt_show_data']);
    }

    public function add_fbt_module()
    {

        $page_setting_data = get_option('mighty_fbt_setting_data');
        $product_data = get_option('mighty_fbt_save_data');
        $product_id = get_the_ID();

        if ($page_setting_data['default_product'] == 'related' || !empty($product_data[$product_id])) {
            do_shortcode('[mighty_fbt_data]');
        }
    }

    public function mighty_enqueue_scripts()
    {
        wp_enqueue_style('mighty_fbt_style', MIGHTY_FBT_PLG_URL . "assets/css/fbt.css");
        // wp_enqueue_style('mighty_fbt_grid_style', MIGHTY_FBT_PLG_URL . "assets/css/grid.css");
        wp_enqueue_style('mighty_fbt_main_style', MIGHTY_FBT_PLG_URL . "assets/css/main.css");
       
        wp_enqueue_script(
            'mighty_fbt_view_script',
            MIGHTY_FBT_PLG_URL . 'assets/js/product_view.js',
            [ 'jquery' ],
            MIGHTY_FBT_VERSION,
            true // in footer?
        );
        
        wp_localize_script('mighty_fbt_view_script', 'fbt', array(
            'currentURL' => get_permalink(get_the_ID()),
        ));

        $label_style_data = get_option('mighty_fbt_label-style_data');
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

        $current_product_image = wp_get_attachment_image_src($product->get_image_id())[0];


        if($product->get_sale_price()){

            $current_sale_price = $product->get_sale_price();
        }
        else{
            $current_sale_price = 0;
        }

        if($product->get_regular_price()){

            $current_regular_price = $product->get_regular_price();
        }
        else{
            $current_regular_price = 0;
        }

            $cat_id = [];
            $terms = get_the_terms($product_id, 'product_cat');
            foreach ($terms as $term) {
                $cat_id[] = $term->term_id;
            }

            $all_ids = get_posts( array (
                'post_type' => 'product',
                'numberposts' => -1,
                'post_status' => 'publish',
                'fields' => 'names',
            ) );

            $rem_id = array_search($product_id, array_column($all_ids, 'ID'));

            unset($all_ids[$rem_id]);

            $detail = [];

            foreach ($all_ids as $key => $value) {

                $category_id = wc_get_product($value);
                $detail[$value->ID] = $category_id->get_category_ids();

            }

            $alternates_products = [];

            foreach ($detail as $key => $value) {
                $result = array_intersect($cat_id, $value);
                if (count($result) > 0) {
                    $alternates_products[$key] = $value;
                }
            }

            $no_of_alternate_product = count($alternates_products);

            if(count($alternates_products) && count($alternates_products) >=2 && $setting_data['default_product'] == 'related'){
                $alternates_products_rand = array_rand($alternates_products, 2);
            }
            else if(count($alternates_products) == 1){
                
                $alternates_products_rand = array_keys($alternates_products);
            }
            else{
                $alternates_products_rand = [];
            }
           
           
            $product_data = get_option('mighty_fbt_save_data');
            if(!empty($product_data) ){
              $current_product =  array_key_exists($product_id , $product_data) ?  $product_data[$product_id] :  '';

                    if($current_product && $current_product['product_type'] == 'custom_selection' &&  $current_product['selected_products']){
    
                       $no_of_product = ( isset( $current_product['num_of_product'] ) ) ? $current_product['num_of_product'] : '';
    
                        $alternates_products = $current_product['selected_products'];
    
                        if($alternates_products){

                            $no_of_alternate_product = count($alternates_products);
                        }
            
                        if ((isset($no_of_product) && !empty($no_of_product)) && $no_of_product <= $no_of_alternate_product && $current_product['show_product'] == 'random_limited_products') {
            
                            $number = $no_of_alternate_product - $no_of_product;

                            $alternates_products_rand = array_slice($alternates_products, $number);

                        } else if ((isset($no_of_product) && !empty($no_of_product)) && $no_of_product > $no_of_alternate_product && $current_product['show_product'] == 'all_selected') {
                            
                            $alternates_products_rand = $alternates_products;

                        } else {

                            $alternates_products_rand = $alternates_products;
                           
                        }
    
                    }
                   else if( $current_product && $current_product['product_type'] == 'use_related' ) {

                        $alternates_products_rand = array_keys($alternates_products);

                        $no_of_alternate_product = count($alternates_products_rand);

                        if ($no_of_alternate_product == 1 || $no_of_alternate_product == 2) {

                            $alternates_products_rand = $alternates_products_rand;

                        } else if ($no_of_alternate_product > 2 ) {
                            
                            $no_of_product = $current_product['num_of_product'];
                            
                            if ($no_of_product && $no_of_product >= 2 && $current_product['show_product'] == 'random_limited_products') {

                                if($no_of_alternate_product <= $no_of_product){
                                
                                    $alternates_products_rand = array_rand($alternates_products, $no_of_alternate_product);
                                } else {
                                         $alternates_products_rand = array_rand($alternates_products, $no_of_product);
                                    }
                            }
                            if( empty($no_of_product)){

                                $alternates_products_rand = array_rand($alternates_products, 2);
                            }
                            if ($no_of_product > $no_of_alternate_product || $current_product['show_product'] == 'all_selected') {
                                
                                $alternates_products_rand = array_rand($alternates_products, $no_of_alternate_product);
                            } 
                            
                        }
                    }
                    
                }

       
             if ($setting_data['layout'] == 'view_1' && ( $setting_data['default_product'] == 'related' || ( $current_product['product_type'] == 'custom_selection' &&  $current_product['selected_products'] ) ||  $current_product['product_type'] == 'use_related') ) {
                
                $label_style_data = get_option('mighty_fbt_label-style_data');
    
                require_once MIGHTY_FBT_DIR_PATH . 'panel/template/mighty_fbt_view1.php';
            }
        }

}
new Mighty_fbt_page();
