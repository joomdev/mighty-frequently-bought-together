<?php

if (!defined('ABSPATH')) {
    exit;
}
?>

<form method="post" action="" novalidate="novalidate">
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label for="box_title">Box Title</label></th>
                <td><input name="box_title" type="text" id="box_title" value="<?php echo $styling['box_title']; ?>" class="regular-text code">
                    <p class="description" id="tagline-description">Enter the title you want to display on the Frequently Bought Together box.</p>
                </td>
            </tr>
            
            <?php   $isProEnable = ( defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) ? 'pro/' : ''; ?>

            <?php   ( defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) ? require_once MIGHTY_FBT_DIR_PATH . $isProEnable . 'panel/pages/pro_labelstyle.php' : ''; ?>
            <tr>
                <th> <label for="total_label"><?php echo ( defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) ? 'Total label for multiple products' : 'Total Label';?></label></th>
                <td> <input name="total_label" type="text" id="total_label" value="<?php echo $styling['total_label']; ?>" class="regular-text code">
                    <p class="description" id="tagline-description">The text entered in this field will be displayed for price when more than three products have been checked.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="bottom_label"><?php echo ( defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) ? 'Button label for multiple products' : 'Button Label';?></label></th>
                <td><input name="button_label" type="text" id="button_label" value="<?php echo $styling['button_label']; ?>" class="regular-text code">
                    <p class="description" id="tagline-description">This is the label for Add to Cart button. The text entered here will be shown on the button when more than three products have been checked.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="button_color">Button Color</label></th>
                <td><input type="text" class="my-color-field" id="button_color" name="button_color" value="<?php echo $styling['button_color']; ?>">
                    <p class="description " id="tagline-description">Set the button background color.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="button_hover_color">Button Hover Color</label></th>
                <td><input type="text" class="my-color-field" id="button_hover_color" name="button_hover_color" value="<?php echo $styling['button_hover_color']; ?>">
                    <p class="description" id="tagline-description">Set the button background hover color.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="button_text_color">Button Text Color</label></th>
                <td><input type="text" class="my-color-field" id="button_text_color" name="button_text_color" value="<?php echo $styling['button_text_color']; ?>">
                    <p class="description" id="tagline-description">Set the button text color</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="button_text_hover_color">Button Text Hover Color</label></th>
                <td><input type="text" class="my-color-field" id="button_text_hover_color" name="button_text_hover_color" value="<?php echo $styling['button_text_hover_color']; ?>">
                <p class="description" id="tagline-description">Set the button text hover color.</p>
                </td>
            </tr>
        </tbody>
    </table>

    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
    </p>


</form>