<?php

if (isset($no_of_alternate_product) && $no_of_alternate_product >= 1) {

   $current_product_totalprice = 0;
   $fbtProductIds[] = $product->get_id();

   $current_product = isset($current_product) ? $current_product : '';
?>

   <div class="mt-fbt-form mt-fbt-form-v1">

      <h3 class="mt-fbt-heading"><?php echo esc_html($label_style_data['box_title']); ?></h3>

      <?php if ( defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) { ?>

         <input type="hidden" class="product_checked" name="" value="<?php echo isset($current_product['product_checked']) ? $current_product['product_checked'] : '';?>">
         <input type="hidden" class="no_of_product" name="" value="<?php echo count($alternates_products_rand) + 1;?>">
         <?php if(isset($current_product['additional_text']) && !empty($current_product['additional_text'])){?>

         <div class="mt-fbt-additional-text">
            <p><?php echo (isset($current_product['additional_text']) ? esc_html($current_product['additional_text']) : ''); ?></p>
         </div>

         <?php }?>

      <?php } ?>

      <div class="mt-fbt-top-box">

         <div class="mt-fbt-image-box">

            <?php if (isset($current_product_image)) : ?>

               <div class="mt-fbt-image mt-remove-pro<?php echo $product_id; ?>">

                  <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">

                     <img src="<?php echo $current_product_image; ?>" alt="<?php echo $product->get_name(); ?>" />

                  </a>

               </div>

            <?php endif; ?>

            <?php foreach ($alternates_products_rand as $key => $value) {

               $alternate_products = wc_get_product($value);

               $fbtProductIds[] = $alternate_products->get_id();
               if(!empty($alternate_products->get_image_id())){

               $image = wp_get_attachment_image_url($alternate_products->get_image_id());
               if(empty($image)){
                  $image = wc_placeholder_img_src();
               }
               }else{
                  $image = wc_placeholder_img_src();
               }

            ?>

               <?php if (isset($image)) : ?>

                  <div class="mt-fbt-image mt-remove-pro<?php echo $alternate_products->get_id(); ?>">

                     <a href="<?php echo esc_url(get_permalink($alternate_products->get_id())); ?>">

                        <img src="<?php echo $image; ?>" alt="<?php echo $alternate_products->get_name(); ?>" />

                     </a>

                  </div>

               <?php endif; ?>

            <?php } ?>
         </div>

         <!-- Product Total Price -->
         <div class="mt-fbt-price-box">

            <div class="mt-fbt-total-price">

               <?php $total_label['total_label'] = esc_html($label_style_data['total_label']); ?>

               <?php if ( defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) {

                  if (!class_exists('mighty_pro_view')) {
                     require  MIGHTY_FBT_DIR_PATH . 'pro/panel/template/pro_view1.php';
                  }

                  $label = new mighty_pro_view();
                  $total_label = $label->button_label($label_style_data, $alternates_products_rand);

               } ?>

               <span class="mt-fbt-price-label">
                  <?php echo $total_label['total_label']; ?>
               </span>

               <?php if ($current_sale_price) {

                  $current_product_totalprice = 0 + $current_sale_price;
               } else {

                  $current_product_totalprice = 0 + $current_regular_price;
               }

               foreach ($alternates_products_rand as $key => $value) {

                  $alternate_products = wc_get_product($value);

                  if ($alternate_products->get_sale_price()) {

                     $current_product_totalprice += $alternate_products->get_sale_price();
                  } else {

                        if (empty($alternate_products->get_regular_price())) {

                           $current_product_totalprice += 0;
                        } else {

                           $current_product_totalprice += $alternate_products->get_regular_price();
                        }
                  }
               } ?>

               <?php $price =$curreny_symbol .'<span class="mt-fbt-price-ammount">' .
                    number_format($current_product_totalprice, 2) . '
                  </span>'; ?>

               <?php if ( defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) {

                  if (!class_exists('mighty_pro_view')) {
                     require  MIGHTY_FBT_DIR_PATH . 'pro/panel/template/pro_view1.php';
                  }

                  $pro_data=[ 'no_of_alternate_product'=>$no_of_alternate_product, 'current_product_totalprice'=>$current_product_totalprice, 'curreny_symbol'=>$curreny_symbol, 'layout'=>$setting_data['layout'], 
                  'highlight_discount' => !empty($setting_data['highlight_discount']) ? $setting_data['highlight_discount'] : '',
                  'current_product'=>$current_product ];

                  $new_price = new mighty_pro_view();

                 $price = $new_price->price($pro_data);
               }  ?>

               <?php echo $price; ?>

            </div>

            <div class="mt-fbt-price-button">

               <a class="mt-fbt-button" href="<?php echo esc_url_raw(add_query_arg('add-to-cart', implode(',', $fbtProductIds))); ?>">

                  <?php $button_label['button_label'] = esc_html($label_style_data['button_label']); ?>

                  <?php

                  if ( defined('MIGHTY_FBT_PRO') && constant('MIGHTY_FBT_PRO')) {
                     if (!class_exists('mighty_pro_view')) {
                        require  MIGHTY_FBT_DIR_PATH . 'pro/panel/template/pro_view1.php';
                     }

                     $button_label = new mighty_pro_view();
                     $button_label = $button_label->button_label($label_style_data, $alternates_products_rand);
                  }  ?>

                  <?php echo $button_label['button_label']; ?>

               </a>

            </div>

         </div>
         <!-- Product Total Price -->
      </div>
      <!-- START Product Links -->
      <div class="mt-fbt-items">
         <!-- START Single Product -->
         <div class="mt-fbt-item">

            <?php if (!empty($current_sale_price) ) { ?>

               <input class="mt-fbt-check-input" type="checkbox" id="" 
               <?php echo (isset($current_product['product_checked'])) 
               && $current_product['product_checked'] == 'on' ? '' : 'checked';
               ?> data-product_id="<?php echo $product_id; ?>" 
               data-product_price="<?php echo number_format($current_sale_price, 2); ?>">

               <label class="mt-fbt-check-label" for="">

                  <strong>This Item : </strong> 
                  <?php if ( !empty ( $setting_data['product_character_limit'] ) && count_chars( $product->get_title() ) > $setting_data['product_character_limit'] ) { ?>
               <span title="<?php echo $product->get_title(); ?>"> 
                  <?php echo substr( $product->get_title(), 0, $setting_data['product_character_limit'] ) . '...'; ?> 
               </span>
               <?php } else { ?>
                  <span> <?php echo $product->get_title(); ?> </span>
               <?php } ?>
                  <s>
                     <span class="mt-fbt-item-price">
                        <?php echo $curreny_symbol . ' ' . number_format($current_regular_price, 2); ?>
                     </span>
                  </s>
                  <strong>
                     <span class="mt-fbt-item-price"><?php echo $curreny_symbol . ' ' . number_format($current_sale_price, 2); ?></span>
                  </strong>

               </label>

            <?php } else { ?>

               <input class="mt-fbt-check-input" type="checkbox" id="" 
               <?php echo (isset($current_product['product_checked'])) 
               && $current_product['product_checked'] == 'on' ? '' : 'checked';
               ?> data-product_id="<?php echo $product_id; ?>" 
               data-product_price="<?php echo number_format($current_regular_price, 2); ?>">

               <label class="mt-fbt-check-label" for="">
                  
                  <strong>This Item : </strong> 
               
               <?php if ( !empty ( $setting_data['product_character_limit'] ) && count_chars( $product->get_title() ) > $setting_data['product_character_limit'] ) { ?>
               <span title="<?php echo $product->get_title(); ?>"> 
                  <?php echo substr( $product->get_title(), 0, $setting_data['product_character_limit'] ) . '...'; ?> 
               </span>
               <?php } else { ?>
                  <span> <?php echo $product->get_title(); ?> </span>
               <?php } ?>


                  <?php if (!empty($current_regular_price)) : ?>
                     <strong>
                     <span class="mt-fbt-item-price">

                        <?php echo $curreny_symbol . ' ' . number_format($current_regular_price, 2); ?>

                     </span>
                     </strong>

                  <?php endif; ?>

               </label>

            <?php } ?>

         </div>

         <?php foreach ($alternates_products_rand as $key => $value) { ?>

            <div class="mt-fbt-item">

               <?php $alternate_products = wc_get_product($value); ?>

               <?php

               $sale_price = $alternate_products->get_sale_price();

               $regular_price = $alternate_products->get_regular_price(); ?>

               <?php if ( $sale_price ) { ?>

                  <input class="mt-fbt-check-input" type="checkbox" id="" 
                  <?php echo (isset($current_product['product_checked'])) 
                  && $current_product['product_checked'] == 'on' ? '' : 'checked';
                  ?> data-product_id="<?php echo $value; ?>" 
                  data-product_price="<?php echo number_format($sale_price, 2); ?>">

                  <label class="mt-fbt-check-label" for="">
                  <?php if ( !empty ( $setting_data['product_character_limit'] ) && count_chars( $alternate_products->get_title() ) > $setting_data['product_character_limit'] ) { ?>
                  
                  <span title="<?php echo $alternate_products->get_title(); ?>">
                     <a href="<?php echo esc_url(get_permalink($alternate_products->get_id())); ?> ">
                     <?php echo substr( $alternate_products->get_title(), 0, $setting_data['product_character_limit'] ) . '...'; ?>
                     </a> </span>
                  <?php } else { ?>
                     <span>
                     <a href="<?php echo esc_url(get_permalink($alternate_products->get_id())); ?> ">
                     <?php echo $alternate_products->get_title() ?>
                     </a> </span>
                  <?php } ?>

                     <s><span class="mt-fbt-item-price"><?php echo $curreny_symbol . ' ' . number_format($regular_price, 2); ?></span></s></label>

                  <strong><span class="mt-fbt-item-price"><?php echo $curreny_symbol . ' ' . number_format($sale_price, 2); ?></span></strong></label>

               <?php } else { ?>
                  <input class="mt-fbt-check-input" type="checkbox" id="" 
                  <?php echo (isset($current_product['product_checked'])) 
                  && $current_product['product_checked'] == 'on' ? '' : 'checked';
                  ?> data-product_id="<?php echo $value; ?>" 
                  data-product_price="<?php echo ($regular_price) ? number_format($regular_price, 2) : number_format(0, 2); ?>"> <label class="mt-fbt-check-label" for="">
                     
                  
                  <?php if ( !empty ( $setting_data['product_character_limit'] ) && count_chars( $alternate_products->get_title() ) > $setting_data['product_character_limit'] ) { ?>
                  
                  <span title="<?php echo $alternate_products->get_title(); ?>">
                     <a href="<?php echo esc_url(get_permalink($alternate_products->get_id())); ?> ">
                     <?php echo substr( $alternate_products->get_title(), 0, $setting_data['product_character_limit'] ) . '...'; ?>
                     </a> </span>
                  <?php } else { ?>
                     <span>
                     <a href="<?php echo esc_url(get_permalink($alternate_products->get_id())); ?> ">
                     <?php echo $alternate_products->get_title() ?>
                     </a> </span>
                  <?php } ?>

                  <strong><span class="mt-fbt-item-price"> <?php echo ($regular_price) ? $curreny_symbol . ' ' . number_format($regular_price, 2) : $curreny_symbol . ' ' . number_format(0, 2); ?></span></strong></label>

               <?php } ?>

            </div>

         <?php } ?>
         <!-- Single Product END -->
      </div>
      <!-- Product Links END -->
   </div>
   <!-- View 1 END -->
<?php } ?>