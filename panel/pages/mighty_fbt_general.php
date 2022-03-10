<?php

if (!defined('ABSPATH')) {
    exit;
}

$default_product = [
    'related' => 'Related',
    'none' => 'None'
];

$box_position = [
    'woocommerce_short_description' => 'Below Product Summary',
    'woocommerce_after_single_product' => 'Below Product Tabs'
];

$layout = [
    'view_1' => 'View 1'
];


$pro_general = '';

( defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) ? include_once MIGHTY_FBT_DIR_PATH . 'pro/panel/pages/pro_general.php' : ''; 
?>

<form method="post" action="" novalidate="novalidate">
    <table class="form-table" role="presentation">
        <tbody>

            <tr>
                <th scope="row"><label for="blogdescription">Default Products</label></th>
                <td><select name="default_product" id="default_role">
                        <?php foreach ($default_product as $key => $value) { ?>

                            <option <?php echo ((isset($setting_data['default_product']) && (strtolower($setting_data['default_product']) == strtolower($key))) ? 'selected' : '') ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>

                        <?php } ?>
                    </select>
                    <p class="description" id="tagline-description">Select what products you want to display if there nothing is specified on the product level.</p>
                </td>
            </tr>
            <tr>
                <th><label for="product_character_limit">Product Character limit</label></th>
                <td>
                    <input name="product_character_limit" type="number" id="product_character_limit" value="<?php echo isset( $setting_data['product_character_limit'] ) ? $setting_data['product_character_limit'] : ''; ?>" class="regular-text code">
                    <p class="description" id="tagline-description">Here you have to enter the Product Title Character limit. This will be Product Title Character Limit.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="blogname">Box Position</label></th>
                <td><select name="box_position" id="default_role">

                        <?php foreach ($box_position as $key => $value) { ?>

                            <option <?php echo ((isset($setting_data['box_position']) && (strtolower($setting_data['box_position']) == strtolower($key))) ? 'selected' : '') ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>

                        <?php } ?>
                    </select>
                    <p class="description" id="tagline-description">Choose the position where you want to place the frequently bought together box. </p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="blogdescription">Layout</label></th>
                <td><select name="layout" id="default_role">
                        <?php foreach ($layout as $key => $value) { ?>

                            <option <?php echo ((isset($setting_data['layout']) && (strtolower($setting_data['layout']) == strtolower($key))) ? 'selected' : '') ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>

                        <?php } ?>

                    </select>
                    <p class="description" id="tagline-description">Select which layout you want to set for the frequently bought together products and boxes. </p>
                </td>
            </tr>
                        <?php echo $pro_general; ?>

            <tr>
                <th scope="row"><label for="blogdescription">Delete all data on uninstall</label></th>
                <td>
                <label class="switch">
                <input type="checkbox" name="uninstall_data" class="uninstall_data" <?php echo (isset($setting_data['uninstall_data']) && $setting_data['uninstall_data'] == 'on') ? 'checked' : ''; ?> value="<?php echo (isset($setting_data['uninstall_data'])) ? $setting_data['uninstall_data'] : '' ?>">
                <span class="slider round"></span>
                    </label>
                    <p class="description" id="tagline-description"> If you enable this option and uninstall the Mighty Frequently Bought Together plugin, all the data of this plugin will be deleted from the database.	</p>
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
               $(this).val('on');
           }
        });
    })(jQuery);

</script>
