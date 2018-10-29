<?php

namespace ELUSHOP\User;

use ELUSHOP\TemplateLoader;

class invoice {
	public function init() {
		add_filter( 'the_content', [ $this, 'filter_content' ] );
	}

	public function filter_content( $content ) {
		if ( ! $this->is_page() ) {
			return $content;
		}

		ob_start();
		$view = isset( $_GET['view'] ) ? $_GET['view'] : 'index';
		TemplateLoader::instance()->get_template_part( "account/$view" );
		return ob_get_clean();
	}

	protected function is_page() {
		return is_page() && get_the_ID() == ps_setting( 'invoice_details' );
	}
}
