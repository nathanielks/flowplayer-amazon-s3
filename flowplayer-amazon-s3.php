<?php
/**
 *
 * @package   Amazon S3 for Flowplayer 5 Plugin
 * @author    Nathaniel Schweinberg <nathaniel@fightthecurrent.org>
 * @license   GPL-2.0+
 * @copyright 2013 Nathaniel Schweinberg
 *
 * @wordpress-plugin
 * Plugin Name: Amazon S3 for Flowplayer 5
 * Plugin URI:  https://github.com/nathanielks/Flowplayer-Amazon-S3
 * Description: Enables the use of Amazon S3 signed urls for protected video streaming. Depends upon http://wordpress.org/support/plugin/flowplayer5
 * Version:     0.1.0
 * Author:      Nathaniel Schweinberg
 * Author URI:  http://fightthecurrent.org/
 * Text Domain: flowplayer-amazon-s3
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'plugins_loaded', array( 'Flowplayer5_Amazon_S3', 'get_instance' ) );

/**
 * Video Meta box class.
 *
 * @package Flowplayer5
 * @author  Nathaniel Schweinberg <nathaniel@fightthecurrent.org>
 */
class Flowplayer5_Amazon_S3 {

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    0.1.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'flowplayer-amazon-s3';

	/**
	 * Instance of this class.
	 *
	 * @since    0.1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.1.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Initialize the plugin
	 *
	 * @since     0.1.0
	 */
	public function __construct() {

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if( is_plugin_active( 'flowplayer5/flowplayer.php' ) ){

			if( is_admin() ){
				// Add some more setings
				add_filter( 'fp5_settings_general', array( $this, 'settings_general' ) );

				// Setup the meta boxes for the video and shortcode
				add_action( 'add_meta_boxes', array( $this, 'add_video_meta_box' ) );

				// Setup the function responsible for saving
				add_action( 'save_post', array( $this, 'save_fp5_video_details' ) );
			}

			// Filter video src
			add_filter( 'fp5_filter_video_src', array( $this, 'filter_video_src' ), 20, 3 );
			add_action( 'wp_enqueue_scripts', array( $this, 'remove_script_conflicts' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			add_action( 'admin_init', array( $this, 'nag_ignore' ) );
		}

	}

	function remove_script_conflicts(){
		// Popup Domination conflicts with our versions of Flowplayer
		wp_dequeue_script( 'flowplayer');
		wp_dequeue_script( 'flowplayer-ipad' );
	}

	function admin_notice() {
		global $current_user ;
			$user_id = $current_user->ID;
			/* Check that the user hasn't already clicked to ignore the message */
		if ( ! get_user_meta($user_id, $this->plugin_slug . '_ignore_notice') ) {
	/**
	 * Add admin notice
	 *
	 * @since     0.1.0
	 */
	public function admin_notice() {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		/* Check that the user hasn't already clicked to ignore the message */
		if ( ! get_user_meta( $user_id, $this->plugin_slug . '_ignore_notice' ) ) {
>>>>>>> e893894609febbb512f1ad26f9c9e314fe9344fc
			echo '<div class="error"><p>';
			if ( is_plugin_inactive( 'flowplayer5/flowplayer.php' ) ) {
				printf(
					__('You need to activate Flowplayer 5 for WordPress plugin. <a href="%1$s">Activate</a> | <a href="%2$s">Hide Notice</a>'),
					admin_url( 'plugins.php?s=Flowplayer+HTML5+for+WordPress' ),
					'?' . $this->plugin_slug . '_nag_ignore=true'
				);
			} else {
				printf(
					__('You need to install the Flowplayer 5 for WordPress plugin. <a href="%1$s">Install</a> | <a href="%2$s">Hide Notice</a>'),
					admin_url( 'plugin-install.php?tab=search&type=tag&s=flowplayer5&plugin-search-input=Search+Pluginsy' ),
					'?' . $this->plugin_slug . '_nag_ignore=true'
				);
			}
			echo "</p></div>";
		}
	}

	/**
	 * Ignore admin notice
	 *
	 * @since     0.1.0
	 */
	public function nag_ignore() {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		/* If user clicks to ignore the notice, add that to their user meta */
		if ( isset( $_GET[ $this->plugin_slug . '_nag_ignore'] ) && 'true' == $_GET[ $this->plugin_slug . '_nag_ignore'] ) {
			add_user_meta( $user_id, $this->plugin_slug . '_ignore_notice', 'true', true );
		}
		if ( isset( $_GET[ $this->plugin_slug . '_nag_ignore'] ) && 'false' == $_GET[ $this->plugin_slug . '_nag_ignore'] ) {
			delete_user_meta( $user_id, $this->plugin_slug . '_ignore_notice' );
		}
	}

	/**
	 * Register settings
	 *
	 * @since     0.1.0
	 */
	public function settings_general( $settings ){
		$settings['amazon_s3'] = array(
			'id'   => 'amazon_s3',
			'name' => '<strong>' . __( 'Amazon S3', $this->plugin_slug ) . '</strong>',
			'desc' => '',
			'type' => 'header'
		);
		$settings['amazon_s3_access_key'] = array(
			'id'   => 'amazon_s3_access_key',
			'name' => __( 'Access Key', $this->plugin_slug ),
			'desc' => '',
			'type' => 'text',
			'size' => 'regular'
		);
		$settings['amazon_s3_secret_access_key'] = array(
			'id'   => 'amazon_s3_secret_access_key',
			'name' => __( 'Secret Access Key', $this->plugin_slug ),
			'desc' => '',
			'type' => 'text',
			'size' => 'regular'
		);
		$settings['amazon_s3_region'] = array(
			'id'   => 'amazon_s3_region',
			'name' => __( 'S3 Region', $this->plugin_slug ),
			'desc' => __( 'Most commonly s3, but other regions are s3-us-west-2, etc. Don\'t change this if you don\'t know!', $this->plugin_slug ),
			'type' => 'text',
			'size' => 'regular',
			'std'  => 's3'
		);
		return $settings;
	}

	/**
	 * Registers the meta box for displaying the 'Flowplayer Amazon S3' in the post editor.
	 *
	 * @since      0.1.0
	 */
	public function add_video_meta_box() {

		add_meta_box(
			$this->plugin_slug . '_details',
			__( 'Flowplayer Amazon S3', $this->plugin_slug ),
			array( $this, 'display_video_meta_box' ),
			'flowplayer5',
			'normal',
			'default'
		);

	}

	/**
	 * Displays the meta box for displaying the 'Flowplayer Amazon S3'
	 *
	 * @since      0.1.0
	 */
	public function display_video_meta_box( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'fp5-amazon-s3-nonce' );
		$fp5_stored_meta = get_post_meta( $post->ID );
		?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><span class="fp5-row-title"><strong><?php _e( 'Enable Amazon S3', $this->plugin_slug )?></strong></span></th>
					<td>
						<label for="fp5-enable-s3">
							<input type="checkbox" name="fp5-enable-s3" id="fp5-enable-s3" value="true" <?php if ( isset ( $fp5_stored_meta['fp5-enable-s3'] ) ) checked( $fp5_stored_meta['fp5-enable-s3'][0], 'true' ); ?> />
							<?php _e( 'Turning this on will enable using Amazon S3 and add the necessary request credentials to the url', $this->plugin_slug ) ?>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="fp5-expire-time"><?php _e( 'Expire Time ( in minutes )', $this->plugin_slug )?></label></th>
					<td>
						<input class="input-text" type="text" name="fp5-expire-time" id="fp5-expire-time" size="70" value="<?php if ( isset ( $fp5_stored_meta['fp5-expire-time'] ) ) echo esc_attr( $fp5_stored_meta['fp5-expire-time'][0] ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	<?php
	}

	/**
	 * When the post is saved or updated, generates a short URL to the existing post.
	 *
	 * @param    int     $post_id    The ID of the post being save
	 * @since    0.1.0
	 */
	public function save_fp5_video_details( $post_id ) {

		if ( $this->user_can_save( $post_id, 'fp5-amazon-s3-nonce' ) ) {

			// Checks for input and saves
			if( isset( $_POST[ 'fp5-enable-s3' ] ) ) {
				update_post_meta(
					$post_id,
					'fp5-enable-s3',
					'true'
				);
			} else {
				update_post_meta(
					$post_id,
					'fp5-enable-s3',
					''
				);
			}

			// Checks for input and saves if needed
			if( isset( $_POST[ 'fp5-expire-time' ] ) ) {
				update_post_meta(
					$post_id,
					'fp5-expire-time',
					absint( $_POST['fp5-expire-time'] )
				);
			}
		}

	}

	/**
	 * Determines whether or not the current user has the ability to save meta data associated with this post.
	 *
	 * @param    int     $post_id    The ID of the post being save
	 * @param    string  $nonce      The nonce identifier associated with the value being saved
	 * @return   bool                Whether or not the user has the ability to save this post.
	 * @since    0.1.0
	 */
	private function user_can_save( $post_id, $nonce ) {

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], plugin_basename( __FILE__ ) ) ) ? true : false;

		// Return true if the user is able to save; otherwise, false.
		return ! ( $is_autosave || $is_revision) && $is_valid_nonce;

	}

	/**
	 * Filter hashed video links
	 *
	 * @since     0.1.0
	 */
	public function filter_video_src( $src, $format, $id ){
		$enabled = get_post_meta( $id, 'fp5-enable-s3', true );
		if( ! empty( $enabled ) ){
			$options    = get_option('fp5_settings_general');
			$access_key = apply_filters( 'fp5_amazon_s3_access_key', $options['amazon_s3_access_key'] );
			$secret_key = apply_filters( 'fp5_amazon_s3_secret_key', $options['amazon_s3_secret_access_key'] );
			$region     = apply_filters( 'fp5_amazon_s3_region', $options['amazon_s3_region'] );
			$expires    = get_post_meta( $id, 'fp5-expire-time', true );
			if( empty( $access_key ) && empty( $secret_key ) ){
				// Error! No keys!
				return new WP_Error( 'fp5-amazon-s3-no-key', __( 'Sorry, but you haven\'t entered your Amazon S3 Access Keys yet!', $this->plugin_slug ) );
			} else {
				$region = ( empty( $region ) ) ? 's3' : $region;
				// Add expires meta box
				return $this->format_s3_link( $access_key, $secret_key, $region, $src, $expires );
			}

		}
		return $src;
	}

	/**
	 * Create temporary URLs to your protected Amazon S3 files.
	 *
	 * @param string $accessKey Your Amazon S3 access key
	 * @param string $secretKey Your Amazon S3 secret key
	 * @param string $bucket The bucket (bucket.s3.amazonaws.com)
	 * @param string $path The target file path
	 * @param int $expires In minutes
	 * @return string Temporary Amazon S3 URL
	 * @see http://awsdocs.s3.amazonaws.com/S3/20060301/s3-dg-20060301.pdf
	 * @since 0.1.0
	 */
	public function format_s3_link( $accessKey, $secretKey, $region, $path, $expires = 3 ) {
		if( empty( $expires ) ) {
			$expires = 3;
		}
		// Calculate expiry time
		$expires = time() + intval( floatval( $expires ) * 60 );

		$parsed_url = parse_url( $path );
		$scheme = $parsed_url['scheme'];

		// Fix the path; encode and sanitize
		$path = str_replace( '%2F', '/', rawurlencode( $path = ltrim($parsed_url['path'], '/' ) ) );

		// Path for signature starts with the bucket
		//$signpath = '/'. $bucket .'/'. $path;
		$signpath = '/'. $path;

		// S3 friendly string to sign
		$signsz = implode( "\n", $pieces = array( 'GET', null, null, $expires, $signpath ) );

		// Calculate the hash
		$signature = $this->crypto_hmacSHA1($secretKey, $signsz);

		// Glue the URL ...
		$url = sprintf( '%s://%s.amazonaws.com/%s', $scheme, $region, $path );

		// ... to the query string ...
		$qs = http_build_query( $pieces = array(
			'AWSAccessKeyId' => $accessKey,
			'Expires'        => $expires,
			'Signature'      => $signature,
		));
		// ... and return the URL!
		$new_url = $url.'?'.$qs;
		//echo '<div style="display:none">' . $new_url . '</div>';
		return $new_url;
	}

	/**
	 * Calculate the HMAC SHA1 hash of a string.
	 *
	 * @param string $key The key to hash against
	 * @param string $data The data to hash
	 * @param int $blocksize Optional blocksize
	 * @return string HMAC SHA1
	 * @since 0.1.0
	 */
	public function crypto_hmacSHA1( $key, $data, $blocksize = 64 ) {
		if ( strlen( $key ) > $blocksize ) $key = pack( 'H*', sha1( $key ) );
		$key = str_pad( $key, $blocksize, chr( 0x00 ) );
		$ipad = str_repeat( chr( 0x36 ), $blocksize );
		$opad = str_repeat( chr( 0x5c ), $blocksize );
		$hmac = pack( 'H*', sha1(
			( $key ^ $opad ) . pack( 'H*', sha1(
			  ( $key ^ $ipad ) . $data
			))
		));
		return base64_encode($hmac);
	}

}
