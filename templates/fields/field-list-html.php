<?php
/**
 * The default list field output template.
 *
 * @global \GV\Template_Context $gravityview
 * @since future
 */
$field_id = $gravityview->field->ID;
$field = $gravityview->field->field;
$value = $gravityview->value;
$display_value = $gravityview->display_value;

$column_id = gravityview_get_input_id_from_id( $field_id );

if ( $field->enableColumns && false !== $column_id ) {

	/**
	 * @filter `gravityview/fields/list/column-format` Format of single list column output of a List field with Multiple Columns enabled
	 * @since 1.14
	 * @param string $format `html` (for <ul> list), `text` (for CSV output)
	 */
	$format = apply_filters( 'gravityview/fields/list/column-format', 'html' );

	echo GravityView_Field_List::column_value( $field, $value, $column_id, $format );

} else {
	echo $display_value;
}