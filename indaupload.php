<?php
/**
 * Plugin Name:     Upload csv plugin
 * Plugin URI:      https://ucase.live
 * Plugin Prefix:   TD
 * Description:     Plugin for upload csv-files
 * Author:          Ucase
 * Author URI:      https://ucase.live
 * Text Domain:     indaupload
 * Domain Path:     /languages
 * Version:         0.1.0
 */

use Backyard\Application;

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
require dirname( __FILE__ ) . '/vendor/autoload.php';
}

$app = Application::get();
$indauploadPlugin = $app->loadPlugin( __DIR__, __FILE__, 'config' );

$indauploadPlugin->onActivation(
    function() use ( $indauploadPlugin ) {
        // Do something on activation here like update_option()
    }
);

$indauploadPlugin->onDeactivation(
    function() use ( $indauploadPlugin ) {
        // Do something on deactivation here
    }
);

$indauploadPlugin->boot(
    function( $plugin ) {
        add_action('admin_menu', 'my_admin_menu');
        function my_admin_menu() {
            add_menu_page('Импорт баллов', 'Импорт баллов', 'edit_others_posts', 'import-points.php', 'print_page_function');
            function print_page_function() {
                if(isset($_POST['submit_upload'])) {

                    if($_FILES['file']['name'] != ''){
                        $uploadedfile = $_FILES['file'];
                        $upload_overrides = array( 'test_form' => false );

                        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                        $imageurl = "";
                        if ( $movefile && ! isset( $movefile['error'] ) ) {
                            $upload_dir = wp_upload_dir();
                            rename($movefile['file'], $upload_dir['basedir']  . WPALLIMPORT_POINTS_PATH . 'points.csv');
                            echo "Файл успешно загружен и будет импортирован автоматически по расписанию.";
                        } else {
                            echo $movefile['error'];
                        }
                    }

                }
                ?>
                <form method='post' action='' name='myform' enctype='multipart/form-data'>
                    <table>
                        <tr>
                            <td>Upload file</td>
                            <td><input type='file' name='file'></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type='submit' name='submit_upload' value='Загрузить файл формата CSV'></td>
                        </tr>
                    </table>
                </form>
                <?php
            }
        }
    }
);
