<?php

if (!defined('ABSPATH')) {
    exit;
}

$pricing_method = [
    'sale_price' => 'Sale Price',
    'regular_price' => 'Regular Price'
];

$default_product = [
    'related' => 'Related',
    'none' => 'None'
];

$box_position = [
    'woocommerce_after_single_product_summary' => 'Below Product Summary',
    'woocommerce_after_single_product' => 'Below Product Tabs'
];

$layout = [
    'view_1' => 'View 1'
];
?>

<form method="post" action="" novalidate="novalidate">
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label for="blogname">Pricing method</label></th>
                <td><select name="pricing_method" id="default_role">

                        <?php foreach ($pricing_method as $key => $value) { ?>

                            <option <?php echo ((isset($setting_data['pricing_method']) && (strtolower($setting_data['pricing_method']) == strtolower($key))) ? 'selected' : '') ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>

                        <?php } ?>
                    </select>
                    <p class="description" id="tagline-description">Calculate prices from the sale price (default) or regular price of products.</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="blogdescription">Default products</label></th>
                <td><select name="default_product" id="default_role">
                        <?php foreach ($default_product as $key => $value) { ?>

                            <option <?php echo ((isset($setting_data['default_product']) && (strtolower($setting_data['default_product']) == strtolower($key))) ? 'selected' : '') ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>

                        <?php } ?>
                    </select>
                    <p class="description" id="tagline-description">Default products when don't specified any products.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="blogname">Box Position</label></th>
                <td><select name="box_position" id="default_role">

                        <?php foreach ($box_position as $key => $value) { ?>

                            <option <?php echo ((isset($setting_data['box_position']) && (strtolower($setting_data['box_position']) == strtolower($key))) ? 'selected' : '') ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>

                        <?php } ?>
                    </select>
                    <p class="description" id="tagline-description">Choose the position to show the products list.</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="blogdescription">Layout</label></th>
                <td><select name="layout" id="default_role">
                        <?php foreach ($layout as $key => $value) { ?>

                            <option <?php echo ((isset($setting_data['layout']) && (strtolower($setting_data['layout']) == strtolower($key))) ? 'selected' : '') ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>

                        <?php } ?>

                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="blogdescription">Delete all data on uninstall</label></th>
                <td>
                <label class="switch">
                <input type="checkbox" name="uninstall_data" class="uninstall_data" <?php echo (isset($setting_data['uninstall_data']) && $setting_data['uninstall_data'] == 'yes') ? 'checked' : ''; ?> value="<?php echo (isset($setting_data['uninstall_data'])) ? $setting_data['uninstall_data'] : '' ?>">
                <span class="slider round"></span>
                    </label>
                </td>
            </tr>
        </tbody>
    </table>

    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>

</form>
<script>
(function ($) {
    $('.uninstall_data').on('click',function(){
       var check = $(this).prop('checked');
       if(check){
           $(this).val('yes');
       }
    })
})(jQuery);
</script>