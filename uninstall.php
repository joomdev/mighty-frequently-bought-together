<?php 
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$uninstall_data = get_option('mighty_fbt_setting_data');

if($uninstall_data && (isset($uninstall_data['uninstall_data']) && $uninstall_data['uninstall_data'] == 'yes')){
    delete_option('mighty_fbt_setting_data');
    delete_option('mighty_fbt_label-style_data');
    delete_option('mighty_fbt_save_data');
}

?>