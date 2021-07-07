<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="title-box">

    <div class="mighty-brand">

        <div class="brand">

            <img class="logo" src="<?php echo MIGHTY_FBT_PLG_URL . 'assets/images/mighty-fbt-logo.png'; ?>" alt="Mighty Frequently Bought together logo">

            <span class="mighty-product-name">Mighty Frequently Bought Together | <?php echo MIGHTY_FBT_VERSION;?></span>

        </div>

        <a href="https://mightythemes.com" target="_BLANK" class="mighty-more-themes-plugins-button"><span class="dashicons dashicons-cart"></span> More WP Themes &amp; Plugins</a>

    </div>

</div>

<ul class="nav nav-tabs">

    <li class="nav-item">

        <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] == 'mighty-fbt-home' ? ' active' : ''; ?>" aria-current="page" href="<?php echo admin_url('admin.php?page=mighty-fbt-home'); ?>">Setting</a>

    </li>

    <li class="nav-item">

        <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] == 'mighty-fbt-labelstyle' ? ' active' : ''; ?>" href="<?php echo admin_url('admin.php?page=mighty-fbt-labelstyle'); ?>">Label & Styling</a>

    </li>

    <?php if (!defined('MIGHTY_FBT_PRO') || (constant('MIGHTY_FBT_PRO') == '')) { ?>

        <li class="nav-item">
            <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] == 'mighty-fbt-go-pro' ? ' active' : ''; ?>" href="<?php echo admin_url('admin.php?page=mighty-fbt-go-pro'); ?>">Go Pro 🚀</a>
        </li>

    <?php } ?>

    <?php if (defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) { 
        include_once MIGHTY_FBT_DIR_PATH . 'pro/includes/pro_header.php';
     } ?>
     
     <div class="docs-link">
        <a target="_blank" href="https://mightythemes.com/docs/docs-category/mighty-fbt/"><span class="dashicons dashicons-media-document"></span> Documentation</a>
        <a target="_blank" href="https://mightythemes.com/support/c/mighty-frequently-bought-together-for-woocommerce/"><span class="dashicons dashicons-editor-help"></span> Help</a>
    </div>
</ul>