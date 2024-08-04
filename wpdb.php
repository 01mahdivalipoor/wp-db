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
            $this->show();
        }
        
        public function insert() {
            global $wpdb;
            $table_name = $wpdb->prefix . 'posts';

            //check wp_insert_post() function
            $data = array(
                'post_title' => 'Example Post',
                'post_content' => 'Decription',
            );

            $success = $wpdb->insert($table_name, $data);

            if ($success) {
                $lastID = $wpdb->insert_id;
            } else {
                $lastID = 'ERR';
            }

            echo 'last id:' . $lastID;
        }

        public function show() {
            global $wpdb;

            // show errors
            $wpdb->show_errors();

            $posts = $wpdb->get_results(
                "SELECT post_title FROM $wpdb->posts
                 WHERE post_status = 'publish'
                 AND post_type = 'post'
                 ORDER BY post_date ASC LIMIT 0,4"
                 );

            var_dump($posts);
        }

        public function show_row() {
            global $wpdb;

            // show errors
            $wpdb->show_errors();

            $row = $wpdb->get_row(
                "SELECT * FROM $wpdb->posts
                 WHERE post_status = 'publish'
                 AND post_type = 'post'
                 ORDER BY comment_count DESC LIMIT 0,1"
                 );

            var_dump($row);
        }

        public function show_col() {
            global $wpdb;

            // show errors
            $wpdb->show_errors();

            $cols = $wpdb->get_col(
                "SELECT post_title FROM $wpdb->posts
                 WHERE post_status = 'publish'
                 AND post_type = 'post'
                 ORDER BY comment_count DESC LIMIT 0,4"
                 );

            var_dump($cols);
        }

        public function show_var() {
            global $wpdb;

            // show errors
            $wpdb->show_errors();

            $regis_date = $wpdb->get_var(
                "SELECT user_registered FROM $wpdb->users
                 WHERE user_login = 'root'"
                 );

            var_dump($regis_date);
        }

        public function prepare_test() {
            global $wpdb;

            // show errors
            $wpdb->show_errors();

            $user = 'root';
            $regis_date = $wpdb->prepare(
                "SELECT user_registered FROM $wpdb->users
                 WHERE user_login = %s",$user
                 );
            
            $res = $wpdb->get_var($regis_date);

            var_dump($res);
        }

        // $wpdb->insert
        // $wpdb->update
        // $wpdb->delete

        // $wpdb->num_rows
        // $wpdb->rows_affected
        // $wpdb->insert_id
        // $wpdb->prefix
        // $wpdb->{core tables}

    }
    
    $restCall = new DbCall;
}
