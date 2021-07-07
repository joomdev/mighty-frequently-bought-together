<?php

if (!defined('ABSPATH')) {
    exit;
}
$all_ids = get_posts( array(
    'post_type' => 'product',
    'numberposts' => -1,
    'post_status' => 'publish',
    'fields' => 'ids',
) );

$page_id = get_the_ID();
$key = array_search($page_id, $all_ids);
unset( $all_ids[$key] );

$product_value = [' '=>' Select an Option','use_related'=>'Use Related', 'custom_selection'=>'Custom Selection'];
$show_product = ['all_selected'=>'All Selected', 'random_limited_products' => 'Random Limited Products'];

$pro_product = '';

( defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) ? include_once MIGHTY_FBT_DIR_PATH . 'pro/panel/pages/pro_product.php' : ''; 

?>
<style>
    .no_of_product_rand,
    .select_product {
        display: none;

    }
</style>
<div id="mighty_wfbt_data_option" class="panel woocommerce_options_panel" style="padding:5px 20px;">
    <form method="post" action="" novalidate="novalidate">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">Product Type</th>
                    <td>
                        <select name="product_type" id="product_type" class="product_type desc">

                            <?php foreach ($product_value as $key => $value) { ?>

                                <option 

                                <?php echo ((isset($current_product_data['product_type']) && (strtolower($current_product_data['product_type']) == strtolower($key))) ? 'selected' : '') ?> value="<?php echo $key; ?>"><?php echo $value; ?>

                                </option>
                            <?php } ?>
                        </select>
                        <p class="description" id="tagline-description">Choose which products you want to use as frequently bought products</p>
                    </td>
                    
                </tr>

                <tr class="select_product">
                    <th>Select Products</th>
                    <td>
                        <div class="dropdown-mul-1">
                            <select class="mySelect for" name="selected_products[]" multiple="multiple" id="multiselect" style="width: 100%">

                                <?php foreach ($all_ids as $id) { ?>

                                    <?php if (isset($current_product_data['selected_products']) && (is_array($current_product_data['selected_products']) && count($current_product_data['selected_products']) > 0)) { ?>

                                        <option 

                                        <?php echo (in_array($id, $current_product_data['selected_products']) ? 'selected' : '') ?> value="<?php echo $id; ?>"><?php echo wc_get_product($id)->get_title() . ' # ' . $id; ?>

                                         </option>

                                    <?php } else { ?>
                                        <option value="<?php echo $id; ?>"><?php echo wc_get_product($id)->get_title() . ' # ' . $id; ?> </option>
                                    <?php  } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <p class="description" id="tagline-description">Select products for "Frequently bought together" group</p>
                    </td>
                </tr>

                <tr>
                    <th>Show Product</th>
                    <td>
                    <?php if($show_product){?>
                        <?php foreach ($show_product as $key => $value) { ?>

                            <input  type="radio" id="all_selected" class="show_product" name="show_product"  

                            <?php echo (isset($current_product_data['show_product']) )?(((strtolower($current_product_data['show_product']) == strtolower($key)))  ? 'checked' : ''):'checked';  ?>

                           value="<?php echo $key; ?>"> <?php echo $value; ?>

                        <?php } ?>
                        <?php }  ?>
                        <p class="description" id="tagline-description" style="display: block;">Choose whether to show all products or set a limited number of products that will show randomly</p>
                       
                    </td>
                </tr>

                <tr class="no_of_product_rand">

                    <th >Number of products to show randomly</th>

                    <td>

                        <input style="width: 20%;" type="number" value="<?php echo (isset($current_product_data['num_of_product']) ? $current_product_data['num_of_product'] : '2'); ?>" name="num_of_product" id="" class="num_of_product">
                        <p class="description" id="tagline-description" >Set how many products to show excluding current one for "Frequently bought together" group	</p>

                    </td>
                   

                </tr>
                <?php echo $pro_product;?>
            </tbody>
        </table>
    </form>
</div>

