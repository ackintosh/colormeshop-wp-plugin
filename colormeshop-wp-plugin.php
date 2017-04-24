<?php
/**
 * Plugin Name: カラーミーショップ Wordpressプラグイン
 * Plugin URI: http://
 * Description: カラーミーショップAPIと連携して商品詳細ページを自動生成します
 * Version: 0.1
 * Author: GMO Pepabo, Inc.
 * Author URI: https://pepabo.com/
 * License: GPL2
 */

require_once( "vendor/autoload.php" );

use Pimple\Container;

class Colormeshop_wp_plugin {
	private $token;
	private $client_id;
	private $client_secret;
	private $target_id;

	/**
     * DI コンテナ
	 *
	 * @var Pimple\Container
	 */
	private $container;

	public function __construct() {
		$this->initializeContainer();
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );

		add_action( 'init', array( $this, 'manage_item_routes' ) );
		add_action( 'init', array( $this, 'custom_rewrite_tag' ), 10, 0 );
		register_activation_hook( __FILE__, array( $this, 'flush_application_rewrite_rules' ) );
		add_action( 'template_redirect', array( $this, 'front_controller' ) );
		add_action( 'colormeshop_item', array( $this, 'show_product' ) );
		add_action( 'colormeshop_category', array( $this, 'show_category' ) );
		add_shortcode( 'cart_button', array( $this, 'show_cart_button' ) );
		add_shortcode( 'authentication_link', array( $this, 'show_authentication_link' ) );

		add_action( 'wp_ajax_colormeshop_callback', array( $this, 'colormeshop_callback' ) );

		remove_action( 'wp_head', '_wp_render_title_tag', 1 );

		$options = get_option( 'colorme_wp_settings' );

		if ( $options ) {
			$this->client_id     = array_key_exists( 'client_id', $options ) ? $options['client_id'] : '';
			$this->client_secret = array_key_exists( 'client_secret', $options ) ? $options['client_secret'] : '';
		}
	}

	public function colormeshop_callback() {
		$provider         = new \Pepabo\OAuth2\Client\Provider\ColorMeShop( [
			'clientId'     => $this->client_id,
			'clientSecret' => $this->client_secret,
			'redirectUri'  => admin_url( 'admin-ajax.php?action=colormeshop_callback' ),
		] );
		$access_token     = $provider->getAccessToken( 'authorization_code', [ 'code' => $_GET['code'] ] );
		$options          = get_option( 'colorme_wp_settings' );
		$options['token'] = $access_token->getToken();
		update_option( "colorme_wp_settings", $options, true );

		header( "Location: " . admin_url( '?page=colorme_wp_settings' ), true );

		return;
	}

	public function manage_item_routes() {
		add_rewrite_rule( '^item/([^/]+)/?', 'index.php?colorme_item=$matches[1]', 'top' );
	}

	public function custom_rewrite_tag() {
		add_rewrite_tag( '%colorme_item%', '([^&]+)' );
	}

	public function flush_application_rewrite_rules() {
		$this->manage_item_routes();
		flush_rewrite_rules();
	}

	public function front_controller() {
		global $wp_query;
		if ( isset( $wp_query->query_vars['colorme_item'] ) ) {
			$this->target_id = $wp_query->query_vars['colorme_item'];
			do_action( 'colormeshop_item' );
		}
	}

	public function show_product() {
		$response = $this->fetch_product( $this->target_id );

		if ( $response->product ) {
			$product = $response->product;

			add_action( 'wp_head', array( $this, 'product_title_tag' ) );
			do_action( 'wp_head', $product->name );

			include plugin_dir_path( __FILE__ ) . '/templates/item.php';
		}
	}

	public function fetch_product( $id ) {
		$url      = "https://api.shop-pro.jp/v1/products/$id.json";
		$response = wp_remote_get( $url, array( 'headers' => array( 'Authorization' => "Bearer " . $this->container['token'] ) ) );
		$content  = json_decode( $response["body"] );

		return $content;
	}

        public function fetch_shop() {
		$url      = "https://api.shop-pro.jp/v1/shop.json";
		$response = wp_remote_get( $url, array( 'headers' => array( 'Authorization' => "Bearer " . $this->container['token'] ) ) );
		$content  = json_decode( $response["body"] );

		return $content->shop;
        }

	public function show_cart_button( $atts, $content = null ) {
		$filteredAtts = shortcode_atts(
			array( 'product_id' => $this->target_id ),
			$atts
		);

		return "<script type='text/javascript' src='" . $this->fetch_shop()->url . "/?mode=cartjs&pid=" . $filteredAtts['product_id'] . "&style=washi&name=n&img=n&expl=n&stock=n&price=n&inq=n&sk=n' charset='euc-jp'></script>";
	}

	public function show_authentication_link( $attr, $content = null ) {
		$provider = new \Pepabo\OAuth2\Client\Provider\ColorMeShop( [
			'clientId'     => $this->client_id,
			'clientSecret' => $this->client_secret,
			'redirectUri'  => admin_url( 'admin-ajax.php?action=colormeshop_callback' ),
		] );

		return '<a href="' . $provider->getAuthorizationUrl( array( 'scope' => [ 'read_products write_products' ] ) ) . '">カラーミーショップアカウントで認証する</a>';
	}

	public function product_title_tag( $title ) {
		echo '<title>' . $title . ' | ' . get_bloginfo() . '</title>';
	}

	// 設定ページ関連
	public function add_plugin_page() {
		add_menu_page( 'カラーミーショップ', 'カラーミーショップ', 'manage_options', 'colorme_wp_settings', array(
			$this,
			'create_admin_page'
		) );
	}

	public function page_init() {
		register_setting( 'colorme_wp_settings', 'colorme_wp_settings', array( $this, 'settings_sanitize' ) );
		add_settings_section( 'general', '基本設定', '', 'colorme_wp_settings' );
		add_settings_field( 'token', 'トークン', array(
			$this,
			'token_setting_callback'
		), 'colorme_wp_settings', 'general' );
		add_settings_field( 'client_id', 'クライアントID', array(
			$this,
			'client_id_setting_callback'
		), 'colorme_wp_settings', 'general' );
		add_settings_field( 'client_secret', 'クライアントシークレット', array(
			$this,
			'client_secret_setting_callback'
		), 'colorme_wp_settings', 'general' );
	}

	public function create_admin_page() {
		include dirname( __FILE__ ) . '/templates/settings.php';
	}

	public function token_setting_callback() {
		?>
        <input type="text" id="message" name="colorme_wp_settings[token]" value="<?php esc_attr_e( $this->container['token'] ) ?>"/>
        <br/>
		<?php

	}

	public function client_id_setting_callback() {
		?>
        <input type="text" id="message" name="colorme_wp_settings[client_id]"
               value="<?php esc_attr_e( $this->client_id ) ?>"/><br/>
		<?php

	}

	public function client_secret_setting_callback() {
		?>
        <input type="text" id="message" name="colorme_wp_settings[client_secret]"
               value="<?php esc_attr_e( $this->client_secret ) ?>"/><br/>
		<?php

	}

	public function settings_sanitize( $inputs ) {
		return $inputs;
	}

	/**
	 * @return void
	 */
	private function initializeContainer()
	{
		$container = new Container();
		$container['token'] = $container->factory(function ($c) {
			$options = get_option( 'colorme_wp_settings' );
			return array_key_exists( 'token', $options ) ? $options['token'] : '';
		});

		$this->container = $container;
	}
}

$colorme = new Colormeshop_wp_plugin();
