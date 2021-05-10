<?php

if (!defined('ABSPATH')) {
    exit;
}
$all_ids = get_posts(array(
    'post_type' => 'product',
    'fields' => 'names',

));
$page_id = get_the_ID();

$rem_id = array_search($page_id, array_column($all_ids, 'ID'));
unset($all_ids[$rem_id]);
$product_value = [' '=>' Select an Option','use_related'=>'Use Related', 'custom_selection'=>'Custom selection'];
$show_product = ['all_selected'=>'All Selected', 'random_limited_products' => 'Random Limited Products'];
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
                        <select name="product_type" id="product_type" class="product_type">

                            <?php foreach ($product_value as $key => $value) { ?>

                                <option 

                                <?php echo ((isset($current_product_data['product_type']) && (strtolower($current_product_data['product_type']) == strtolower($key))) ? 'selected' : '') ?> value="<?php echo $key; ?>"><?php echo $value; ?>

                                </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr class="select_product">
                    <th>Select products</th>
                    <td>
                        <div class="dropdown-mul-1">

                            <select class="mySelect for" name="selected_products[]" multiple="multiple" id="multiselect" style="width: 100%">

                                <?php foreach ($all_ids as $value) { ?>

                                    <?php if (isset($current_product_data['selected_products']) && (is_array($current_product_data['selected_products']) && count($current_product_data['selected_products']) > 0)) { ?>

                                        <option 

                                        <?php echo (in_array($value->ID, $current_product_data['selected_products']) ? 'selected' : '') ?> value="<?php echo $value->ID; ?>"><?php echo $value->post_title . '#' . $value->ID; ?>

                                         </option>

                                    <?php } else { ?>

                                        <option value="<?php echo $value->ID; ?>"><?php echo $value->post_title . '#' . $value->ID; ?> </option>
                                    <?php  } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>Show Product</th>
                    <td>

                        <?php foreach ($show_product as $key => $value) { ?>

                            <input type="radio" id="all_selected" class="show_product" name="show_product" 

                            <?php echo (( isset($current_product_data['show_product']) && (strtolower($current_product_data['show_product']) == strtolower($key)))  ? 'checked' : '')  ?>

                           value="<?php echo $key; ?>"> <?php echo $value; ?>

                        <?php } ?>

                    </td>
                </tr>

                <tr class="no_of_product_rand">

                    <th>Number of products to show randomly</th>

                    <td>

                        <input type="number" value="<?php echo (isset($current_product_data['num_of_product']) ? $current_product_data['num_of_product'] : '2'); ?>" name="num_of_product" id="" class="num_of_product">

                    </td>

                </tr>
            </tbody>
        </table>
    </form>
</div>

<script>
    var placeholder = "select";

    (function($) {

        var product_type_value = $('#product_type').find(":selected").val();

        if (product_type_value == 'custom_selection') {
            $('.select_product').css("display", "table-row");
        } else {
            $('.select_product').css("display", "none");
        }
        var show_product = $('#all_selected:checked').val();
        if (show_product == 'random_limited_products') {
            $('.no_of_product_rand').css("display", "table-row");
        } else {
            $('.no_of_product_rand').css("display", "none");
        }


        $('.product_type').on('change', function(e) {
            var pro_type = $('#product_type').val();
            if (pro_type == 'custom_selection') {
                $('.select_product').css("display", "table-row");
            } else {
                $('.select_product').css("display", "none");
            }
        });
        $('input[type=radio][name="show_product"]').on('change', function(e) {
            var show_pro = $(this).val();
            if (show_pro == 'random_limited_products') {
                $('.no_of_product_rand').css("display", "table-row");
            } else {
                $('.no_of_product_rand').css("display", "none");
            }
        });
        $(".mySelect").select2({
            placeholder: placeholder,
            allowClear: false,
            minimumResultsForSearch: 5
        });

    }(jQuery));
</script>