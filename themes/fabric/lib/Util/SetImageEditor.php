<?php
namespace Fabric\Util;


class SetImageEditor
{
	/** @var    string */
	private $image_editor;

	/**
	 * @param    string    $image_editor
	 */
	public function __construct( string $image_editor )
	{
		$this->image_editor = $image_editor;

		add_filter( 'wp_image_editors', array( $this, 'filter' ) );
	}

	/**
	 * @param    string[]    $image_editors
	 * @return    string[]
	 */
	public function filter( array $image_editors ): array
	{
		if ( $this->image_editor === 'WP_Image_Editor_GD' || $this->image_editor === 'GD' ) {
			return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
		}

		return $image_editors;
	}
}
