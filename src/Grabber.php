<?php

namespace WebUtils;

class Grabber 
{
    public static function getFile($source, $dir=null, $name=null, $verbose = false)
    {
       
        // Check $source
        try {
            if (!$source) {
                throw new \Exception("There is no link for download.");
            }
            $file_info = pathinfo($source);

        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

        
        if ($dir) {
            if (!is_dir($dir)) {
                try {
                    if (!(mkdir($dir, 0777, true))) {
                        throw new Exception("Cannot create a directory '$dir'");
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                    exit();
                }
            }
        } 

        $file_name = '';
        if (!$name) {
            $file_name = $dir . '/' . $file_info['basename'];
        } else {
            $file_name = $dir . '/' . $name . '.' .$file_info['extension'];
        }
        
        try {
            if (!($fp = fopen($file_name, "w"))) {
                throw new Exception("Cannot create file '$file_name'");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }

        try {
            if (!($ch = curl_init($source))) {
                throw new Exception("Cannot initialize a session with remote host", 1);
            }           

            if ($verbose) {
                echo "Downloading '$file_name' .... ";
            }
            // if(!@copy($source,$file_name))
            //     {
            //         $errors= error_get_last();
            //         echo "COPY ERROR: ".$errors['type'];
            //         echo "<br />\n".$errors['message'];
            //     } else {
            //         echo "File copied from remote!";
            //     }
            
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            curl_close($ch);
            // $fp = file_get_contents($source);
            // \file_put_contents($file_name, file_get_contents($source));
            fclose($fp);

            if ($verbose) {
                echo json_decode('"\u2713"') . PHP_EOL;
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }
}