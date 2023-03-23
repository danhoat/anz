<?php

namespace QMS4\TaxonomyMeta;


class TaxonomyMeta
{
	/** @var    string */
	private $object_name;

	/** @var    string */
	private $name;

	/** @var    string */
	private $label;

	/** @var    string */
	private $query;

	/** @var    bool */
	private $color_available;

	/**
	 * @param    string    $label
	 * @param    string    $name
	 * @param    string    $query
	 */
	public function __construct(
		string $object_name,
		string $name,
		string $label,
		string $query,
		bool $color_available
	)
	{
		$this->object_name = $object_name;
		$this->name = $name;
		$this->label = $label;
		$this->query = $query;
		$this->color_available = $color_available;
	}

	/**
	 * @param    array<string,mixed>    $row
	 * @return    self
	 */
	public static function from_array( string $object_name, array $row ): self
	{
		if ( ! isset( $row[ 'label' ], $row[ 'name' ], $row[ 'query' ] ) ) {
			throw new \InvalidArgumentException();
		}

		return new self(
			$object_name,
			$row[ 'name' ],
			$row[ 'label' ],
			$row[ 'query' ],
			$row[ 'color_available' ] ?? false
		);
	}

	// ====================================================================== //

	/**
	 * @return    string
	 */
	public function taxonomy(): string
	{
		return "{$this->object_name}__{$this->name}";
	}

	/**
	 * @return    string
	 */
	public function object_name(): string
	{
		return $this->object_name;
	}

	/**
	 * @return    string
	 */
	public function name(): string
	{
		return $this->name;
	}

	/**
	 * @return    string
	 */
	public function label(): string
	{
		return $this->label;
	}

	/**
	 * @return    string
	 */
	public function query(): string
	{
		$query = trim( $this->query );

		return empty( $query ) ? $this->taxonomy() : $query;
	}

	/**
	 * @return    bool
	 */
	public function color_available(): bool
	{
		return $this->color_available;
	}
}
