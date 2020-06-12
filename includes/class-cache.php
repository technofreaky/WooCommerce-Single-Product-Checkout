<?php

namespace SPCF_WC;

defined( 'ABSPATH' ) || exit;

use \VSP\Cache as VSP_Cache;

/**
 * Class Cache
 *
 * @package SPCF_WC
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 */
class Cache extends VSP_Cache {
	/**
	 * Cache Key Prefix.
	 *
	 * @var string
	 */
	protected static $prefix = 'spcf_wc';
}
