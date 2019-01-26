<?php

namespace App;

use Includes\Controller;


class CommonController extends Controller
{

    function __construct($id=0, $params='')
    {
        parent::__construct($params);
    }

    function upload($type='', $app='Upload')
    {
        $request = GetVar('request', 'POST');

        if ($input = GetVar("upload_{$type}", 'FILE')) {
            $file_name = $input['name'];
            $file_type = $input['type'];
            $file_tmp_name = $input['tmp_name'];
            $file_error = $input['error'];
            $file_size = $input['size'];

            if ($file_error || empty($file_name) || empty($file_tmp_name) || empty($file_size)) {
                die('Error');
            }

            $upload_folder = $GLOBALS['bethlehem']['setting']['upload_folder'];
            $file_path = "/{$app}". "/". md5($file_name);
            while(file_exists("{$upload_folder}{$file_path}")) {
                $file_path .= "1";
            }

            move_uploaded_file($file_tmp_name, "{$upload_folder}{$file_path}");

            if ($request == 'ajax') {
                $output = ['link' => $GLOBALS['app']->link('/download.php', 'raw='.base64_encode($file_path))];

                header('Content-type: application/json');
                die(json_encode($output));
            }
        }
    }

    function printout($type='')
    {
        switch ($type) {
            case 'barcode':
                $barcode = CreateObject('Lib.Barcode', 'JPG');
                $ary_data = explode('-', $this->params[0]);
                $str_code_type = $ary_data[0];
                $int_item_id = $ary_data[1];

                if ($str_code_type == 'package') {
                    $ary_sacks = [];
                    $obj_item = CreateObject('Model.Sack');

                    foreach ($obj_item->searchAll(sprintf("`sack_container` = %d AND `sack_scanner` = 0", $int_item_id), 'sack_no', 'ASC') as $order => $item) {
                        $ary_sacks[] = [
                            'VAL_BARCODE_NUMBER' => $item['sack_no'],
                            'IMG_BARCODE' => $barcode->generate($item['sack_no']),
                            'IMG_TYPE' => $item['sack_type']
                        ];
                    }
                } else {
                    $obj_item = CreateObject('Model.'. ucfirst($str_code_type));
                    $obj_item->load($int_item_id);
                }
                break;
        }

        $assign = [
            'VAL_CODE_TYPE' => $str_code_type,
            'VAL_BARCODE_NUMBER' => $obj_item->getVar("{$str_code_type}_no"),
            'IMG_BARCODE' => empty($obj_item->getVar("{$str_code_type}_no")) ? '' : $barcode->generate($obj_item->getVar("{$str_code_type}_no")),
            'VAL_ID_CODE' => $obj_item->getVar("{$str_code_type}_id_code"),
            'ROW_PACKAGE' => $ary_sacks
        ];

        $GLOBALS['app']->view($assign, ['NO_NAVBAR' => True, 'NO_HTMLHEADER' => True, 'NO_HTMLFOOTER' => True]);
    }
}
