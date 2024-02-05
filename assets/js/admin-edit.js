jQuery('#wc_promo_product_expiration').on("change", function() {
    if( jQuery(this).is(":checked") ){
        jQuery( '.wc_promo_product_expiration_date_time' ).show();
    }else{
        jQuery( '.wc_promo_product_expiration_date_time' ).hide();
    }
}).trigger("change");