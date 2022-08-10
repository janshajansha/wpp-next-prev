<?php
/**
 *
 * This plugin is for the displaying the next and previous product in the Woocommerce Single Product.
 *
 * @package     Woo Next Previous Product
 * @author      Ohiowebtech Devteam
 * @copyright   2022 Ohiowebtech
 * @license     GPL-2.0+
 * Plugin Name: Woo Next Previous Product
 * Plugin URI: https://www.ohiowebtech.com
 * Description: Displays the next and previous product in the Woocommerce Single Product.
 * Version: 1.0.0
 * Author: Ohiowebtech
 * Author URI: https://www.ohiowebtech.com
 * Text Domain: wnp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'WNPPATH', plugin_dir_path( __FILE__ ) . 'includes/' );
require_once WNPPATH . 'class-wnp.php';
