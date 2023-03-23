<?php
namespace Fabric\Action\IncludePart;


class IncludePart
{
	/** @var    string */
	private $screen;

	/** @var    string */
	private $part_name;

	/**
	 * @param    string    $screen
	 * @param    string    $part_name
	 */
	public function __construct( string $screen, string $part_name )
	{
		$this->screen = $screen;
		$this->part_name = $part_name;
	}

	/**
	 * @param    int|string    $the_id
	 * @return    void
	 */
	public function __invoke( $the_id = '' ): void
	{
		$inc_path = ( $template = $this->template( $this->part_name ) )
			? __DIR__ . "/../../../template-parts/{$this->screen}/{$template}.php"
			: __DIR__ . "/../../../template-parts/{$this->screen}/{$this->part_name}.php";

		if ( file_exists( $inc_path ) ) {
			include( $inc_path );
			return;
		}


		$inc_path = __DIR__ . "/../../../template-parts/common/{$this->part_name}.php";

		if ( file_exists( $inc_path ) ) {
			include( $inc_path );
			return;
		}
	}

	/**
	 * @param    string    $part_name
	 * @return    string|null
	 */
	private function template( string $part_name ): ?string
	{
		$map = array(
			'start_front_main' => 'start_main',
			'start_page_main' => 'start_main',
			'start_entry_main' => 'start_main',
			'start_404_main' => 'start_main',

			'end_front_main' => 'end_main',
			'end_page_main' => 'end_main',
			'end_entry_main' => 'end_main',
			'end_404_main' => 'end_main',

			'before_front_content' => 'before_content',
			'before_page_content' => 'before_content',
			'before_entry_content' => 'before_content',
			'before_404_content' => 'before_content',

			'after_front_content' => 'after_content',
			'after_page_content' => 'after_content',
			'after_entry_content' => 'after_content',
			'after_404_content' => 'after_content',

			'start_entry_foot' => 'start_foot',
			'end_entry_foot' => 'end_foot',
		);

		return isset( $map[ $part_name ] ) ? $map[ $part_name ] : null;
	}
}
