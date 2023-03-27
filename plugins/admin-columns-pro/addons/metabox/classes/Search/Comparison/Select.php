<?php

namespace ACA\MetaBox\Search\Comparison;

use AC\Helper\Select\Options;
use ACP;

class Select extends ACP\Search\Comparison\Meta
	implements ACP\Search\Comparison\Values {

	/**
	 * @var array
	 */
	private $choices;

	public function __construct( $meta_key, $type, $choices ) {
		$operators = new ACP\Search\Operators( [
			ACP\Search\Operators::EQ,
			ACP\Search\Operators::NEQ,
			ACP\Search\Operators::IS_EMPTY,
			ACP\Search\Operators::NOT_IS_EMPTY,
		] );

		$this->choices = $choices;

		parent::__construct( $operators, $meta_key, $type );
	}

	public function get_values() {
		return Options::create_from_array( $this->choices );
	}

}