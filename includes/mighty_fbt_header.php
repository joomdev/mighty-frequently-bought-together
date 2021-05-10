<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="title-box" >

    <div class="mighty-brand">

        <div class="brand">
            <img class="logo" src="<?php echo MIGHTY_FBT_PLG_URL . 'assets/images/fbt.png'; ?>" alt="mighty FBT logo">
            <span class="mighty-product-name">MIGHTY FREQUENTLY BOUGHT TOGETHER</span>
        </div>

        <a href="https://mightythemes.com" target="_BLANK" class="mighty-more-themes-plugins-button"><img src="<?php echo MIGHTY_FBT_PLG_URL . 'assets/images/cart.png'; ?>"> More WP Themes &amp; Plugins</a>

    </div>
</div>

    <div class="tabs">

        <a href="<?php echo admin_url('admin.php?page=mighty-fbt-home'); ?>" class="<?php echo $_GET['page'] == 'mighty-fbt-home' ? 'active' : ''; ?>">Setting</a>
        <a href="<?php echo admin_url('admin.php?page=mighty-fbt-labelstyle'); ?>" class="<?php echo $_GET['page'] == 'mighty-fbt-labelstyle' ? 'active' : ''; ?>">Label & Styling</a>

    </div>

