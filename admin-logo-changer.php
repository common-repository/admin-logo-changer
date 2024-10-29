<?php
/**
 * @link              http://store.wphound.com/?plugin=admin-logo-changer
 * @since             1.0.0
 * @package           Admin_Logo_Changer
 *
 * @wordpress-plugin
 * Plugin Name:       Admin Logo Changer
 * Plugin URI:        http://store.wphound.com/?plugin=admin-logo-changer
 * Description:       This plugin will allow you to Add a logo to your login page.
 * Version:           1.0.0
 * Author:            WP Hound
 * Author URI:        http://www.wphound.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       admin-logo-changer
 */
 
if ( ! defined( 'ADMIN_LOGO_CHANGER_VERSION' ) ) {
	define( 'ADMIN_LOGO_CHANGER_VERSION', '1.0.0' );
}

if ( ! class_exists( 'WP_Admin_Logo_Changer' ) ) {
    class WP_Admin_Logo_Changer {

        public function __construct() {
            $logo_changer_options = get_option( 'wp_admin_logo_changer' );

            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            add_action( 'admin_init', array( $this, 'admin_init' ) );
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );

            if ( 'on' == $logo_changer_options['login'] ) {
            	add_action( 'login_enqueue_scripts', array( $this, 'login_enqueue_scripts' ) );
                add_filter( 'login_headertitle', array( $this, 'login_headertitle' ) );
                add_filter( 'login_headerurl', array( $this, 'login_headerurl' ) );
            }

        }

        public function admin_init() {
            register_setting( 'wp_admin_logo_changer', 'wp_admin_logo_changer', array( $this, 'wp_admin_logo_changer_validation' ) );

        }

    	public function wp_admin_logo_changer_validation( $alc_input ) {
    		$alc_input['login'] = ( empty( $alc_input['login'] ) ) ? '' : 'on';
    		$alc_input['image'] = esc_url( $alc_input['image'] );

    		return $alc_input;
    	}

        public function admin_menu() {
            add_options_page( __( 'Admin Logo Changer', 'admin-logo-changer' ), __( 'Admin Logo Changer', 'admin-logo-changer' ), 'manage_options', __FILE__, array( $this, 'logo_changer_options_page' ) );
        }


        public function login_headerurl() {
            return esc_url( home_url() );
        }


        public function login_headertitle() {
            return esc_attr( get_bloginfo( 'name' ) );
        }


        function login_enqueue_scripts() {
            $logo_changer_options = get_option( 'wp_admin_logo_changer' );
        	if ( $alc_image = $logo_changer_options['image' ] ) { ?>
			<style>
            body.login div#login h1 a {
                background-image: url(<?php echo esc_url( $alc_image ); ?>);
                background-size: 320px 80px;
                width: 100%;
                border-radius: 5px;
            }
            </style>
            <?php
            }
        }


        public function logo_changer_options_page() {?>
			<style>
			.alc_wrap{border:2px solid white; font-style:normal;}
			.alc_wrap h2{ padding: 0px 0px 0px 20px;}
			.alc_wrap table #logo-changer-image-container {padding-bottom: 15px;}
			.alc_wrap table tr th{padding: 20px 10px 20px 20px;}
			.alc_wrap table#logo-changer-table tr td a.set-image,.alc_wrap table#logo-changer-table tr td a.remove-image{ 
			background: #00858A; color: white;text-decoration: none;padding: 3px 3px;box-shadow: 0 1px 0px #006799;
			text-shadow: 0 -1px 1px #006799, 1px 0 1px #006799, 0 1px 1px #006799, -1px 0 1px #006799;
			border: 1px solid #0085ba;border-radius: 3px;}
			.alc_wrap table#logo-changer-table tr td p.description {padding-top: 10px;color: red;}
			table#logo-changer-table tr td div#logo-changer-image-container img { width: 320px; height: 80px; border-radius: 5px;}
			.alc_wrap input#submit {background: #00858A;margin: 0px 0px 0 25px;border-color: #00858a #00858a #00858a;
			box-shadow: 0 1px 0 #00858a;color: #fff;text-decoration: none;
			text-shadow: 0 -1px 1px #00858a, 1px 0 1px #00858a, 0 1px 1px #00858a, -1px 0 1px #00858a;}
			</style>
			<?php
            if ( ! current_user_can( 'manage_options' ) )
                wp_die( __( 'You do not have permissions to access this page.', 'admin-logo-changer' ) );

            $logo_changer_options = get_option( 'wp_admin_logo_changer' );
            $alc_image = ( $logo_changer_options['image'] ) ? '<img src="' . esc_url( $logo_changer_options['image'] ) . '" alt="" style="max-width: 100%;" />' : '';
            $display = ( $logo_changer_options['image'] ) ? '' : 'style="display: none;"';
        	?>
            <div class="wrap">
            <div class="alc_wrap">
                <h2><?php _e( 'Admin Logo Changer', 'admin-logo-changer' ); ?></h2>
                <form method="post" action="options.php">
                    <?php settings_fields( 'wp_admin_logo_changer' ); ?>

                    <table id="logo-changer-table" class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e( 'Set/Remove Logo :', 'admin-logo-changer' ); ?></th>
                            <td>
                                <input type="hidden" id="logo-changer-image" name="wp_admin_logo_changer[image]" value="<?php echo esc_url( $logo_changer_options['image'] ); ?>" />
                                <div id="logo-changer-image-container"><?php echo $alc_image; ?></div>
                                <a href="#" class="set-image"><?php _e( 'Set Image', 'admin-logo-changer' ); ?></a>&nbsp;&nbsp;&nbsp;<a href="#" class="remove-image" <?php echo $display; ?>><?php _e( 'Remove Image', 'admin-logo-changer' ); ?></a>
                                <br />
                                <p class="description"><?php _e( '*logo should be (320x80) px or else it will be resized on the login screen.', 'admin-logo-changer' ); ?></p>
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th scope="row"><?php _e( 'Logo Display Options :', 'admin-logo-changer' ); ?></th>
                            <td>
                                <fieldset>
                                	<label for="logo-changer-on-login">
                                	<input name="wp_admin_logo_changer[login]" id="logo-changer-on-login" type="checkbox" <?php checked( esc_attr( $logo_changer_options['login'] ), 'on' ); ?>>
                                	<?php _e( 'Display logo on login page', 'admin-logo-changer' ); ?></label>
                                </fieldset>
                            </td>
                        </tr>
                    </table>

                    <?php submit_button(); ?>
                </form>
            </div>
            </div>
         <?php
         }

        public static function activate() {
            $default_option = array(
                'login' => 'on',
                'image' => plugins_url( 'admin-logo-changer-images/admin-logo.png', __FILE__ )
            );

        	add_option( 'wp_admin_logo_changer', $default_option );
        }

        public static function deactivate() {
        	delete_option( 'wp_admin_logo_changer' );
        }


      public function admin_enqueue_scripts( $hook ) {
            $logo_changer_options = get_option( 'wp_admin_logo_changer' );

            if ( 'settings_page_admin-logo-changer/admin-logo-changer' == $hook ) {
                wp_enqueue_media();
                wp_enqueue_script( 'logo_changer_to_admin', plugins_url( 'admin-logo-changer-js/logo-changer-image.js', __FILE__ ), array( 'jquery', 'media-upload', 'media-views' ), ADMIN_LOGO_CHANGER_VERSION, true );
            }
			
        }

    } 
}

if ( class_exists( 'WP_Admin_Logo_Changer' ) ) {

    register_activation_hook( __FILE__, array( 'WP_Admin_Logo_Changer', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'WP_Admin_Logo_Changer', 'deactivate' ) );

    $wp_admin_logo_changer = new WP_Admin_Logo_Changer();

 
    if ( isset( $wp_admin_logo_changer ) ) {
        function logo_changer_plugin_settings_link( $links ) {
            $settings_link = '<a href="options-general.php?page=admin-logo-changer/admin-logo-changer.php">' . __( 'Settings', 'admin-logo-changer' ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }

        $plugin = plugin_basename( __FILE__ );
        add_filter( "plugin_action_links_$plugin", 'logo_changer_plugin_settings_link' );
    }
}