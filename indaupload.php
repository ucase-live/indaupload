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
        add_action('admin_head', 'admin_stylesheet_tailwindcss');
        function admin_stylesheet_tailwindcss() {
            wp_enqueue_style("style-admin-tailwindcss-components", 'https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.6/components.min.css');
            wp_enqueue_style("style-admin-tailwindcss-utilities", 'https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.6/utilities.min.css');
        }

        add_action('admin_menu', 'my_admin_menu');
        function my_admin_menu() {
            add_menu_page('Импорт баллов', 'Импорт баллов', 'edit_others_posts', 'import-points.php', 'print_page_function');
            function print_page_function() {
                ?>
                <div class="wrap">
                    <h2>Загрузка файла импорта сотрудников и их баллов</h2>
                    <form class="mt-8" method='post' action='' name='myform' enctype='multipart/form-data'>
                        <div class="w-64 flex flex-wrap items-center justify-center bg-grey-lighter">
                            <label class="flex flex-col items-center px-4 py-6 mb-4 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-white">
                                <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                                </svg>
                                <span class="mt-2 text-base leading-normal">Выберите файл CSV</span>
                                <input type='file' name='file' class="hidden" />
                            </label>
                            <input class="border p-4 text-xl text-center" type='submit' name='submit_upload' value='Загрузить файл CSV'>
                        </div>
                    </form>
                </div>
                <?php
                if(isset($_POST['submit_upload'])) {

                    if($_FILES['file']['name'] != ''){
                        $uploadedfile = $_FILES['file'];

                        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
                        if(in_array($_FILES['file']['type'],$mimes)){
                            $upload_overrides = array( 'test_form' => false );

                            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                            $imageurl = "";
                            if ( $movefile && ! isset( $movefile['error'] ) ) {
                                $upload_dir = wp_upload_dir();
                                rename($movefile['file'], WPALLIMPORT_POINTS_PATH);
                                echo "Файл успешно загружен и будет импортирован автоматически по расписанию.";
                            } else {
                                echo $movefile['error'];
                            }
                        } else {
                            echo 'Загруженный файл имеет некорректный формат';
                        }

                    }

                }
            }
        }
    }
);
