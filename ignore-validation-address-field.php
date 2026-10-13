<?php
add_filter( 'gform_field_validation_9_111', 'skip_postcode_validation', 10, 4 );
function skip_postcode_validation( $result, $value, $form, $field ) {

// Nothing to do if the field already passed, or it isn't an address value.
if ( $result['is_valid'] || ! is_array( $value ) ) {
    return $result;
}

// Sub-inputs that should STILL be required (1.5 postcode and 1.2 line 2 are left out).
// 111.1 = Street, 111.3 = City, 111.4 = State/Province, 111.6 = Country
$still_required = array( '111.1', '111.3', '111.4', '111.6' );

foreach ( $still_required as $input_id ) {
    $input = GFFormsModel::get_input( $field, $input_id );

    // Skip sub-inputs that are hidden in the field settings.
    if ( ! $input || rgar( $input, 'isHidden' ) ) {
        continue;
    }

    // Something other than the postcode is empty, so keep the error.
    if ( rgblank( rgar( $value, $input_id ) ) ) {
        return $result;
    }
}

// Only the postcode was missing, so let it through.
$result['is_valid'] = true;
$result['message']  = '';

return $result;
}
?>
