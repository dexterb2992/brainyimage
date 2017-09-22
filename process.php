<?php
namespace App;

error_reporting(1);

require_once "conf.php";

use \App\lib\BrainyImage;

class AjaxProcessImage
{
    public function __construct()
    {
        $this->brainyImage = new BrainyImage();

        if (isset($_POST['q'])) {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = trim($_POST[$key]);
            }
            switch ($_POST['q']) {
                case 'upload':
                    $this->upload($_FILES);
                    break;
                
                case 'compress':
                    $filename = $_POST['filename'];
                    if ($filename == '**from_url**') {
                        $filename = basename($_POST['src']);
                    }

                    $this->compress($_POST['src'], $filename);
                    break;

                case 'retry':
                    $this->retry($_POST['src'], basename($_POST['src']));
                    break;

                case 'register':
                    $this->register($_POST);
                    break;
            }
        }
    }

    private function upload($files)
    {
        if (isset($files['img_file'])) {
            $response = $this->brainyImage->upload($files);
        } else {
            $response = array("error" => true, "msg" => "No image received.");
        }

        echo json_encode($response);
    }

    private function compress($src, $filename = "")
    {
        $res = $this->brainyImage->compressUploaded($src, $filename);

        echo is_array($res) ? json_encode($res) : $res;
    }

    private function retry($src, $filename = "")
    {
        return $this->compress($src, $filename);
    }

    // POST DATA
    private function register($input)
    {
        //
    }
}

new AjaxProcessImage();
