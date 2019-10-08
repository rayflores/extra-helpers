jQuery(document).ready(function($){
    
    $('.woocommerce-billing-fields>h3').hide();
    
    var timeout;
    var delay = 1000;
    
    /* Add/remove products with radio or checkbox inputs */
    $( '#opc-product-selection select#checkout-products' ).on( 'change', function(e) {

        var select = $(this),
            selectors = '#checkout-products option[data-add_to_cart]',
            option = $('#checkout-products option:selected');
            option_value = option.val();
        
        
        clearTimeout(timeout);

        timeout = setTimeout(function() {

            var data = {
                add_to_cart: parseInt( option_value ),
                nonce:       wcopc.wcopc_nonce
            };

            if ( $('option').is( ':selected' ) ) {

                if ( option.prop( 'type' ) == 'option' ) {

                    data.empty_cart = 'true';
                    $( 'option[data-add_to_cart]' ).prop( 'selected', false );
                    option.prop( 'selected', true );
                    $( '.selected' ).removeClass( 'selected' );
                }

                data.action = 'pp_add_to_cart';
                option.addClass( 'selected' );

            } else {

                data.action = 'pp_remove_from_cart';
                input.parents( '.product-item' ).removeClass( 'selected' );

            }

            option.ajax_add_remove_product( data, e, selectors );

        }, delay );

    } ); 
});