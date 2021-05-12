
jQuery(function( $ ) {
    var placeholder = "select";
 
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
    
     
});