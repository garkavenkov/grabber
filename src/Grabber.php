<?php

namespace Garkavenkov\Grabber;

class Grabber {

    public static function getFile($source, $fileName = null, $dest = null) {

        // Check wether "curl" extension is loaded or not.
        try {
            if (!extension_loaded('curl')) {
                throw new \Exception("Extension 'curl' for PHP is not installed.");
            }
        } catch (Exception $e) {
            echo $e->getMessage(). PHP_EOL;
            exit();
        }

        // Check $source
        try {
            if (!$source) {
                throw new \Exception("Не указана ссылка на скачиваемый файл.");
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

        // Form destination folder for images
        if (!$dest) {
            $dest = "img/";
        } else {
            $dest = "img/" . $dest;
        }

        // Check whether destination folder exsits or not.
        // If does not exist try make one.
        if (!is_dir($dest)) {
            try {
                if (!(mkdir($dest, 0777, true))) {
                    throw new Exception("Cannot create a directory '$dest'");
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                exit();
            }
        }

        // Form destination file name for image.
        $fileName =  $dest . "/" . $fileName;
        // Try to open file for write
        try {
            if (!($fp = fopen($fileName, "w"))) {
                throw new Exception("Cannot create file '$fileName'");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

        // Initialize a cURL session.
        // Download and save file.
        try {
            if (!($ch = curl_init($source))) {
                throw new Exception("Cannot initialize a session with remote host", 1);
            }
            echo "Download '$fileName' .... ";
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            echo json_decode('"\u2713"') . PHP_EOL;
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

    }


}
