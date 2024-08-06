<?php
/**
 * Plugin Name:       wpdb
 * Plugin URI:        https://mahdivalipoor.ir/plugins/wpdb/
 * Description:       wpdb
 * Version:           0.9.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mahdi Valipoor
 * Author URI:        https://mahdivalipoor.ir/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 */

defined('ABSPATH') or die;

if (!class_exists('DbCall')) {
    class DbCall 
    {      
        public function __construct()
        {
            add_action( 'admin_menu', array($this,'registerWpdbAdminMenu') );
            add_action( 'admin_init', array($this,'registerWpdbSettings') );
            add_action( 'admin_init', array($this,'insert_data') );
            register_activation_hook( __FILE__ , [ $this, 'create_table' ] );
        }

        public function registerWpdbAdminMenu() {
            add_menu_page(
                'wpdb',
                'wpdb',
                'manage_options',
                'wpdb',
                array($this,'WPDBpage')
            );
        }

        public function WPDBpage() {
            ?>

            <div class="wrap">
                <h1>WPDB</h1>
                <form action="options.php" method="post" novalidate="novalidate">
                        <?php settings_fields( 'wpdb_settings' ); ?>
                        <table class="form-table" role="presentation">
                            <?php do_settings_fields( 'wpdb_settings', 'default' ); ?>
                        </table>
                        <?php submit_button(); ?>
                </form>
            </div>

            <?php
                $this->show();
        }

        public function registerWpdbSettings() {
            $NameArgs = array(
                array(
                'type' => 'string',
                'default' => NULL,
                ),
                array(
                    'type' => 'number',
                    'default' => NULL,
                )
            );
            
            register_setting('wpdb_settings', 'wpdb_field_1', $NameArgs[0]);
            register_setting('wpdb_settings', 'wpdb_field_2', $NameArgs[1]);


            add_settings_field( 'wpdb_field_1', esc_html__( 'Full Name', 'default' ), [$this,'wpdb_settings_field_callback'], 'wpdb_settings' );
            add_settings_field( 'wpdb_field_2', esc_html__( 'Age', 'default' ), [$this,'wpdb_settings_field_callback2'], 'wpdb_settings' );
        }

        public function wpdb_settings_field_callback() {
            $name = get_option( 'wpdb_field_1' );
            echo '<input type="text" name="wpdb_field_1" value="' . esc_attr( $name ) . '">';
            
        }

        public function wpdb_settings_field_callback2(){
            $age = get_option( 'wpdb_field_2' );
            echo '<input type="number" name="wpdb_field_2" value="' . esc_attr( $age ) . '">';
        }

        public function insert_data() {
            if(isset($_POST['submit'])){
                global $wpdb;
                $name = get_option( 'wpdb_field_1' );
                $age = get_option( 'wpdb_field_2' );



                $table_name = $wpdb->prefix . 'data_call';

                //check wp_insert_post() function
                $data = array(
                    'FullName' => $_POST['wpdb_field_1'],
                    'Age' => $_POST['wpdb_field_2'],
                );

                $wpdb->insert($table_name, $data);

                // $done var
            }
        }

        /*
            CREATE TABLE
        */
        function create_table() {
            global $wpdb;
        
            $table_name = $wpdb->prefix . 'data_call';
        
            $charset_collate = $wpdb->get_charset_collate();
        
            $sql = "CREATE TABLE $table_name (
                id int NOT NULL AUTO_INCREMENT,
                FullName varchar(255) NOT NULL,
                Age int,
                PRIMARY KEY (id)
            ) $charset_collate;";
        
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta( $sql );
        }

        public function show() {
            global $wpdb;

            // show errors
            $wpdb->show_errors();
            $table_name = $wpdb->prefix . 'data_call';
            $posts = $wpdb->get_results(
                "SELECT * FROM $table_name"
                );

            foreach ($posts as $post) {
               echo $post->FullName . " | " . $post->Age . "<br/>";
            }
        }
    }
    
    $restCall = new DbCall;
}
