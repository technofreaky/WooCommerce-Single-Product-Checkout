<?php

namespace SPCF_WC\Installer;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( '\SPCF_WC\Installer\Installer' ) ) {
	/**
	 * Class Installer
	 *
	 * @package WC_Product_Subtitle
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 4.0
	 */
	class Installer {
		/**
		 * Triggers V4.0 Installer.
		 *
		 * @static
		 * @return bool
		 */
		public static function run_v1() {
			require_once __DIR__ . '/v1-installer.php';
			return \SPCF_WC\Installer\Version_1::run();
		}
	}
}
