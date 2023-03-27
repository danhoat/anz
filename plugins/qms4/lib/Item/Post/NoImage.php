<?php

namespace QMS4\Item\Post;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Acf\AcfFactory;
use QMS4\Item\Term\Terms;
use QMS4\Item\Term\TermsFactory;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class NoImage
{
	/** @var    Param */
	protected $_param;

	/** @var    array<string,mixed> */
	protected $_memo = array();

	public function __construct( Param $param )
	{
		$this->_param = $param;
	}

	/**
	 * @param    string    $name
	 * @return    mixed
	 */
	public function __get( $name )
	{
		return $this->__call( $name, array() );
	}

	/**
	 * @param    string    $name
	 * @param    array    $args
	 * @return    mixed
	 */
	public function __call( string $name, array $args )
	{
		if ( isset( $this->_memo[ $name ] ) ) {
			$value = $this->_memo[ $name ];
		} else {
			$value = $this->_memo[ $name ] = $this->___proxy( $name );
		}

		return $this->___compute( $name, $value, $args );
	}

	// ====================================================================== //

	/**
	 * @param    string    $name
	 * @return    mixed
	 */
	private function ___proxy( string $name )
	{
		$method_name = "_{$name}";

		if ( method_exists( $this, $method_name ) ) {
			return $this->$method_name();
		}

		return '';
	}

	/**
	 * @param    string    $name
	 * @param    mixed    $value
	 * @param    array<string,mixed>    $args
	 * @return    mixed
	 */
	private function ___compute( string $name, $value, array $args )
	{
		$compute_method_name = "__{$name}";

		if ( method_exists( $this, $compute_method_name ) ) {
			$compute = new Compute( $this, $compute_method_name );
			return $compute->bind( $this->_param )->invoke( $value, $args );
		}

		return $value;
	}

	// ====================================================================== //

	/**
	 * @return    string
	 */
	public function __toString(): string
	{
		return $this->html;
	}

	/**
	 * @return    string
	 */
	protected function _alt(): string
	{
		return 'No Image';
	}


	/**
	 * @return    string
	 */
	protected function _caption(): string
	{
		return '';
	}


	/**
	 * @return    string
	 */
	protected function _description(): string
	{
		return '';
	}


	/**
	 * @return    string
	 */
	protected function _filename(): string
	{
		// TODO: 実装する
		return '';
	}


	/**
	 * @return    int
	 */
	protected function _filesize(): int
	{
		// TODO: 実装する
		return -1;
	}


	/**
	 * @return    int
	 */
	protected function _height(): int
	{
		// TODO: 実装する
		return -1;
	}

	/**
	 * @param    int    $value
	 * @param    string    $image_size
	 * @return    int
	 */
	protected function __height(
		int $value,
		string $image_size = 'full'
	): int
	{
		// TODO: 実装する
		return $value;
	}

	/**
	 * @return    int
	 */
	protected function _html(): int
	{
		// TODO: 実装する
		return -1;
	}

	/**
	 * @param    int    $value
	 * @param    string    $image_size
	 * @return    string
	 */
	protected function __html(
		int $value,
		string $image_size = 'full'
	): string
	{
		if ( defined( 'ARKHE_NOIMG_ID' ) && ARKHE_NOIMG_ID ) {
			return wp_get_attachment_image( ARKHE_NOIMG_ID, $image_size );
		} elseif ( defined( 'ARKHE_NOIMG_URL' ) && ARKHE_NOIMG_URL ) {
			return '<img src="' . esc_url( ARKHE_NOIMG_URL ) . '" alt="">';
		} else {
			return '';
		}
	}


	/**
	 * @return    bool
	 */
	protected function _is_no_image(): bool
	{
		return true;
	}


	/**
	 * @return    string
	 */
	protected function _mime_type(): string
	{
		return 'image/png';
	}


	/**
	 * @return    int
	 */
	protected function _src(): int
	{
		// TODO: 実装する
		return '';
	}

	/**
	 * @param    int    $value
	 * @param    string    $image_size
	 * @return    string
	 */
	protected function __src(
		int $value,
		string $image_size = 'full'
	): int
	{
		// TODO: 実装する
		return -1;
	}


	/**
	 * @return    string
	 */
	protected function _title(): string
	{
		return 'No Image';
	}

	/**
	 * @param    string    $value
	 * @param    int    $title_length
	 * @param    bool    $slash_to_br
	 * @return    string
	 */
	protected function __title(
		string $value,
		int $title_length = -1,
		bool $slash_to_br = true
	): string
	{
		if ($title_length >= 0) {
			$value = mb_strimwidth($value, 0, $title_length * 2);
		}

		return $value;
	}


	/**
	 * @return    int
	 */
	protected function _width(): int
	{
		// TODO: 実装する
		return -1;
	}

	/**
	 * @param    int    $value
	 * @param    string    $image_size
	 * @return    int
	 */
	protected function __width(
		int $value,
		string $image_size = 'full'
	): int
	{
		// TODO: 実装する
		return $value;
	}
}
