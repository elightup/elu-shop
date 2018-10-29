<?php
function ps_setting( $name ) {
	if ( ! function_exists( 'rwmb_meta' ) ) {
		return false;
	}
	return rwmb_meta( $name, [ 'object_type' => 'setting' ], 'elu_shop' );
}
