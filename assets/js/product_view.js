(function ($) {

  function valToAmount(value) {
    value = value.toString();
    var afterPoint = '';
    if (value.indexOf('.') > 0)
      afterPoint = value.substring(value.indexOf('.'), value.length);
    value = Math.floor(value);
    value = value.toString();
    var lastThree = value.substring(value.length - 3);
    var otherNumbers = value.substring(0, value.length - 3);
    if (otherNumbers != '')
      lastThree = ',' + lastThree;
    return otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
  }

  $(".mt-fbt-check-input").on("click", function () {
    var productId = $(this).data("product_id");
    
    // Getting all checked product IDs
    let allProductIds = [];
    $( ".mt-fbt-check-input" ).each( function( index, element ) {
      if( $( element ).is( ':checked' ) ) {
        allProductIds.push( $( element ).data( 'product_id' ) );
      }
    });
   var checked_product = allProductIds.length;
    if(checked_product == 0){
      $('.mt-fbt-form-v1 .mt-fbt-top-box').css("display","none");
    }
    else{
      $('.mt-fbt-form-v1 .mt-fbt-top-box').css("display","flex");

    }

    var product_toggle = $(".mt-remove-pro" + productId);

    product_toggle.toggle("fast");

    var amount_feild = $(".mt-fbt-price-ammount");
    var total_price = parseFloat(amount_feild.text());
   
    var product_price = parseFloat(
      $(this).data("product_price")
    );
    var check = $(this).prop("checked");
    if (check) {
      var price = total_price + product_price;
    } else {
      var price = total_price - product_price;
    }
    price = parseFloat(price).toFixed(2);

    var actual_price = valToAmount(price);
    amount_feild.text(actual_price);

    // updating URL
    $( '.mt-fbt-button' ).attr( 'href', fbt.currentURL + '?add-to-cart=' + allProductIds.join() );
  });
})(jQuery);