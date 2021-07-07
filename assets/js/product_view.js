(function ($) {

  var checked_value = $('.product_checked').val();
  if (checked_value == 'on') {
    $(".mt-fbt-price-box,.mt-fbt-image").css("display", "none");
    price = parseFloat(0).toFixed(2);
    var amount_feild = $(".mt-fbt-price-ammount");
    var actual_price = valToAmount(price);
    amount_feild.text(actual_price);
  }

  function valToAmount(value) {
    value = value.toString();
    var afterPoint = "";
    if (value.indexOf(".") > 0)
      afterPoint = value.substring(value.indexOf("."), value.length);
    value = Math.floor(value);
    value = value.toString();
    var lastThree = value.substring(value.length - 3);
    var otherNumbers = value.substring(0, value.length - 3);
    if (otherNumbers != "") lastThree = "," + lastThree;
    return (
      otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") +
      lastThree +
      afterPoint
    );
  }

  $(".mt-fbt-check-input").on("click", function () {


      var productId = $(this).data("product_id");
      // Getting all checked product IDs
      let allProductIds = [];

      $(".mt-fbt-check-input").each(function (index, element) {
        if ($(element).is(":checked")) {
          allProductIds.push($(element).data("product_id"));
        }
      });

      var checked_product = allProductIds.length;

      if (checked_product == 0) {
        $(".mt-fbt-price-box").css("display", "none");
      } else {
        $(".mt-fbt-price-box").css("display", "block");
      }

      // change button label and total label according to no of product
      if (fbt.isPro) {
      switch (checked_product) {

        case 1:
          $('.mt-fbt-button').text(fbt.button_label_single);
          $('.mt-fbt-price-label').text(fbt.total_label_single);
          break;
        case 2:
          $('.mt-fbt-button').text(fbt.button_label_double);
          $('.mt-fbt-price-label').text(fbt.total_label_double);
          break;
        case 3:
          $('.mt-fbt-button').text(fbt.button_label_triple);
          $('.mt-fbt-price-label').text(fbt.total_label_triple);
          break;
        default:
          $('.mt-fbt-button').text(fbt.button_label);
          $('.mt-fbt-price-label').text(fbt.total_label);
      }
    }
      // end

      var product_toggle = $(".mt-remove-pro" + productId);
      product_toggle.toggle("slow");

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
      // discount section
      if (fbt.isPro) {
        var current_product_id = fbt.id;
        var check_current_product_id = allProductIds.indexOf(
          parseInt(current_product_id)
        );
        if (check_current_product_id == 0) {

          var spend_amount = $(".spend_amount").val();
          var total_quantity = $(".total_quantity").val();
          var no_of_product = $(".no_of_product").val();
          var discount_type = $(".discount_type").val();
          var discount_value = $(".discount_value").val();

          if (
            ((spend_amount <= price && spend_amount != "") ||
            (total_quantity <= checked_product && total_quantity != "" )) || ( (no_of_product == checked_product ) && typeof(discount_value) !== 'undefined' )  )
           {

            if (discount_type == "flat") {
              var discount = price - discount_value;
            } else if (discount_type == "percentage") {
              discount = price - (price * discount_value) / 100;
              var saving_amount = (price * discount_value) / 100;
              saving_amount = parseFloat(saving_amount).toFixed(2);
              $(".discount_amount").text(saving_amount);
              $(".saving_amount").text(saving_amount);
            }

            discount = parseFloat(discount).toFixed(2);
            $(".discount_amount").text(discount);
            $(".mt-fbt-price-ammount").css("text-decoration", "line-through");
            $(".mt-fbt-discount,.mt-discount-save").css(
              "display",
              "inline-block"
            );
            $(".mt-fbt-discount-badge").css("display", "table");
          } else {
            $(".mt-fbt-discount,.mt-discount-save,.mt-fbt-discount-badge").css(
              "display",
              "none"
            );
            $(".mt-fbt-price-ammount").css("text-decoration", "none");
          }

        } else {
          $('.mt-fbt-discount,.mt-discount-save,.mt-fbt-discount-badge').css("display", "none");
          $(".mt-fbt-price-ammount").css("text-decoration", "none");

        }

      }
      // end



      price = parseFloat(price).toFixed(2);

      var actual_price = valToAmount(price);
      amount_feild.text(actual_price);

      // updating URL
      $(".mt-fbt-button").attr(
        "href",
        fbt.currentURL + "?add-to-cart=" + allProductIds.join()
      );
    },


  );
})(jQuery);