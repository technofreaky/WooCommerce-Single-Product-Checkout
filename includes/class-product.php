<?php

namespace SPCF_WC;

defined( 'ABSPATH' ) || exit;

/**
 * Class Product
 *
 * @package SPCF_WC
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 */
class Product {
	/**
	 * @var \WPOnion\DB\Option
	 */
	protected $options;

	/**
	 * Product constructor.
	 */
	public function __construct() {
	}

	/**
	 * Checks if disabled.
	 *
	 * @return bool
	 */
	public function disabled() {
		return ( 'no' === $this->options->get( 'is_enabled' ) );
	}

	/**
	 * Checks if disabled.
	 *
	 * @return bool
	 */
	public function enabled() {
		return ( 'yes' === $this->options->get( 'is_enabled' ) );
	}

	/**
	 * Checks if Enabled but set to default.
	 *
	 * @return bool
	 */
	public function enabled_default() {
		return ( 'default' === $this->options->get( 'is_enabled' ) );
	}

	/**
	 * Returns Minium Quantity.
	 *
	 * @return string
	 */
	public function min_qty() {
		return $this->options->get( 'min_qty' );
	}

	/**
	 * Returns Maximum Quantity.
	 *
	 * @return string
	 */
	public function max_qty() {
		return $this->options->get( 'max_qty' );
	}
}
