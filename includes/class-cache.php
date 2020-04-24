<?php

namespace SPCF_WC;

defined( 'ABSPATH' ) || exit;

use \VSP\Cache as VSP_Cache;

if ( ! class_exists( '\SPCF_WC\Cache' ) ) {
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
		 * @since 2.0
		 */
		protected static $prefix = 'spcf_wc';
	}
}
