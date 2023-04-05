<?php

namespace QMS4\Item\Acf;

use QMS4\Param\Param;
use QMS4\Item\Acf\AcfCheckbox;
use QMS4\Item\Acf\AcfDatePicker;
use QMS4\Item\Acf\AcfFile;
use QMS4\Item\Acf\AcfGallery;
use QMS4\Item\Acf\AcfGroup;
use QMS4\Item\Acf\AcfImage;
use QMS4\Item\Acf\AcfNumber;
use QMS4\Item\Acf\AcfPostObject;
use QMS4\Item\Acf\AcfRadio;
use QMS4\Item\Acf\AcfRelationship;
use QMS4\Item\Acf\AcfRepeater;
use QMS4\Item\Acf\AcfSelect;
use QMS4\Item\Acf\AcfTaxonomy;
use QMS4\Item\Acf\AcfText;
use QMS4\Item\Acf\AcfTextarea;
use QMS4\Item\Acf\AcfUrl;
use QMS4\Item\Acf\AcfWysiwyg;


class AcfFactory
{
	public function from_name( $object, string $name, Param $param )
	{
		if ( ! function_exists( 'get_field_object' ) ) {
			throw new \RuntimeException();
		}

		$field_object = get_field_object( $name, $object, false );

		if ( $field_object === false ) {
			throw new \RuntimeException();
		}

		return $this->from_field_object( $field_object, $param );
	}

	public function from_field_object( array $field_object, Param $param )
	{
		switch ( $field_object[ 'type' ] ) {
			case 'checkbox': return new AcfCheckbox( $field_object, $param );
			case 'date_picker': return new AcfDatePicker( $field_object, $param );
			case 'date_time_picker': return new AcfDatePicker( $field_object, $param );
			case 'file': return new AcfFile( $field_object, $param );
			case 'gallery': return new AcfGallery( $field_object, $param );
			case 'group': return new AcfGroup( $field_object, $param );
			case 'image': return new AcfImage( $field_object, $param );
			case 'number': return new AcfNumber( $field_object, $param );
			case 'post_object': return new AcfPostObject( $field_object, $param );
			case 'radio': return new AcfRadio( $field_object, $param );
			case 'range': return new AcfNumber( $field_object, $param );
			case 'relationship': return new AcfRelationship( $field_object, $param );
			case 'repeater': return new AcfRepeater( $field_object, $param );
			case 'select': return new AcfSelect( $field_object, $param );
			case 'taxonomy': return new AcfTaxonomy( $field_object, $param );
			case 'text': return new AcfText( $field_object, $param );
			case 'textarea': return new AcfTextarea( $field_object, $param );
			case 'url': return new AcfUrl( $field_object, $param );
			case 'wysiwyg': return new AcfWysiwyg( $field_object, $param );
			default: var_dump( $field_object[ 'type' ] ); exit();
		}
	}
}
