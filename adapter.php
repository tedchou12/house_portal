<?php

$feedback = [
    'success' => 0,
    'failure' => 1,
    'callback' => []
];

define('LITOCRM_API_URL', 'http://api.3d-products.com');

require_once dirname(__FILE__) .'/Bootstrap/Autoload.php';

$method = GetVar('method', 'POST');
$captcha_input = GetVar('captcha_input', 'POST');
$captcha_validation = GetVar('captcha_validation', 'POST');

$captcha = CreateObject('Lib.Captcha');
$request = CreateObject('Lib.Request');

if (empty($captcha_input)) {
    $feedback['callback'] = ['message' => 'validation is require'];
    header('Content-type: application/json');
    die(json_encode($feedback));
}

if (empty($captcha_validation)) {
    $feedback['callback'] = ['message' => 'exception (validation)'];
    header('Content-type: application/json');
    die(json_encode($feedback));
}

if ($captcha->validate($captcha_input, $captcha_validation) == False) {
    $feedback['callback'] = ['message' => 'validation is invalid'];
    header('Content-type: application/json');
    die(json_encode($feedback));
}

if (empty($method)) {
    $feedback['callback'] = ['message' => 'data is invalid'];
} else {
    $method = strtolower($method);
    switch ($method) {
        case 'create_org':
            // $request->setUrl(LITOCRM_API_URL ."/{$method}.php");
            // $request->setData($_POST);
            // $response = $request->send();
            // header('Content-type: application/json');
            // die($response);
            $response['success'] = 0;
            $response['failure'] = 1;

            $mailer = CreateObject('Lib.Mailer');
            $message = '';
            foreach ($_POST as $key => $val) {
              $message .= $key . ': ' . $val .'<br />';
            }
            if ($mailer->send('webmaster@3d-products.com', 'Trial Requested', $message) === true) {
              $response['success'] = 1;
              $response['failure'] = 0;
              $response['callback'] = ['message' => 'Thank you for register, we will contact you shortly!'];
            }

            $google_api = CreateObject('Lib.google_api');
            $file_id = '1DHkpVKZzOaFfJBK3Q_gMM258Rgu4zOMjRvARf2fEYpc';
            $data = $google_api->get_file($file_id);
            $data_index = count($data) + 2;
            $data = array($_POST['org_name'], $_POST['org_email'], $_POST['org_refer'], date('Y-m-d H:i:s'));
            $google_api->write_file($file_id, $data_index, $data);
            header('Content-type: application/json');
            die(json_encode($response));
            break;
    }
}

header('Content-type: application/json');
die(json_encode($feedback));
