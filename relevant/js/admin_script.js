( function( $ ) {
  $( document ).ready( function() {
    if ( $.isFunction( $.fn.wpColorPicker ) ) {
      var colorPickerOptions = {
        change: function( event, ui ) {
          if( 'function' == typeof bws_show_settings_notice )
            bws_show_settings_notice();
        }
      }
      $( '.rltdpstsplgn_colorpicker' ).each( function() {
        $( this ).wpColorPicker( colorPickerOptions );
      } );
	  $( '.rltdpstsplgn_recommend_color' ).each( function() {
		$( this ).wpColorPicker( colorPickerOptions );
		} );
    }
    var windowWidth = $( window ).width();
    if( 350 >= windowWidth ) {
      $( '.iris-square' ).css( {'margin-right': '2%', 'width': '163px'} );
      $( '.iris-picker-inner' ).css( 'width', '196px' );
      $( '.iris-picker' ).css( 'width', '209px' );
      $( '.iris-palette' ).css( 'width', '17px' );
    }
    /* function for block width */
    $( 'select[class="rltdpstsplgn_block_unit"]' ).change( function(){
      if ( $(' #block_width_id' ).val() >= 100 && '%' == $( this ).val() ) {
      $( '#block_width_id' ).val(100);
      }
    } );
    $( '#block_width_id' ).on( 'input', function() {
      if ( $( this ).val() > 100 && '%' == $( 'select[class="rltdpstsplgn_block_unit"]' ).val() ) {
      $( this ).val( 100 );
      }
    } );
    /* function for content block width */
    $( 'select[class="rltdpstsplgn_text_unit"]' ).change( function(){
      if ( $(' #text_width_id' ).val() >= 100 && '%' == $( this ).val() ) {
      $( '#text_width_id' ).val(100);
      }
    } );

    $( '#text_width_id' ).on( 'input', function() {
      if ( $( this ).val() > 100 && '%' == $( 'select[class="rltdpstsplgn_text_unit"]' ).val() ) {
      $( this ).val( 100 );
      }
    } );

    $( '.widget' ).on( 'DOMSubtreeModified', '[name="savewidget"]', function() {
      $( '.bws_option_affect' ).on( 'change', function() {
        var options = $( '.bws_option_affect' );
        if ( options.length ) {
          options.each( function() {
            var element = $( this );
            if ( element.is( ':checked' ) ) {
              $( element.data( 'affect-hide' ) ).hide();
            } else {
              $( element.data( 'affect-hide' ) ).show();
            }
          } );
        }
      } ).trigger( 'change' );
    } );
	
	$( '.rltdpstsplgn_recommend_role' ).on( 'change', function() {
		var checkboxes = $( '.rltdpstsplgn_recommend_role' );
		if ( checkboxes.filter( ':checked' ).length == checkboxes.length ) {
			$( '.rltdpstsplgn_recommend_select_all' ).prop( 'checked', true );
		} else {
			$( '.rltdpstsplgn_recommend_select_all' ).prop( 'checked', false );
		}
	} ).trigger( 'change' );

	$( '.rltdpstsplgn_recommend_select_all' ).on( 'change', function() {
		if ( $( this ).is( ':checked' ) ) {
			$( '.rltdpstsplgn_recommend_role' ).prop( 'checked', true );
		} else {
			$( '.rltdpstsplgn_recommend_role' ).prop( 'checked', false );
		}
	} );

	$.ajax( {
		type: 'POST',
		url: cstmfld_local_object.ajax_url,
		data: field_element,
		beforeSend: function() {
			$( '.cntctfrm_ajax_loader' ).show();
		},
		success: function( id ) {
			$( '.cntctfrm_ajax_loader' ).hide();
			$( '.cntctfrm_settings_table tbody' ).first().append( prepare_table_row_html( field_element, id ) );
			cstmfld_init_sortable();
		}
	} );

  } );

} ) ( jQuery );
