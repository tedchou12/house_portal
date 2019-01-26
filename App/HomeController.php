<?php

namespace App;

class HomeController
{
    var $bo;
    var $so;

    function __construct()
    {

    }

    function index()
    {
        $GLOBALS['bethlehem']['setting']['page_title'] = '';
        $assign = array(
          'URL_RESOURCE_DIR' => $GLOBALS['app']->link('/Resources'),
          'lang_title1' => lang('事業カテゴリー'),
          'lang_subtitle1' => lang('We Tailor Our Service to Your Need'),
          'lang_ftitle1' => lang('Cheap Annual Fee'),
          'lang_ftitle2' => lang('Unlimited Users'),
          'lang_ftitle3' => lang('Resumable Upload'),
          'lang_ftitle4' => lang('File Sharing'),
          'lang_trial' => lang('Try Now'),
          'lang_early_bird' => lang('Go Early Bird'),
          'lang_ftext1' => lang('We have the lowest annual fee, our plan start from $600NT/year.'),
          'lang_ftext2' => lang('We don\'t count our fee by the number of users, you can have as many you want.'),
          'lang_ftext3' => lang('Resumable upload so you don\'t have to worry about unstable network, you can upload files up to 5GB with any plan.'),
          'lang_ftext4' => lang('Sharing files with as many users as you like, permission control with your files.'),
          'lang_text1' => lang('File Transfer allows Enterprises to Utilize cloud Securely and Flexibly'),
          'lang_text2' => lang('We specialize in B2B solutions and building information system for enterprises; your company can utilize the scalable cloud storage cheaply and securely.'),
          'link_trial' => $GLOBALS['app']->link('/Home/eregister'),
          'link_pricing' => $GLOBALS['app']->link('/Home/pricing'),
        );

        $GLOBALS['app']->view($assign);
    }

    function about()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('弊社について');
      $assign = array(
        'lang_title' => lang('弊社について'),

      );

      $GLOBALS['app']->view($assign);
    }

    function contact()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('お問合せ');
      if (isset($_POST['submit'])) {
        $captcha_input = $_POST['captcha_input'];
        $captcha_validation = $_POST['captcha_validation'];

        $captcha = CreateObject('Lib.Captcha');
        if (empty($captcha_input)) {
          $msg_type = 'danger';
          $msg = lang('You must input Captcha');
        }

        if (empty($captcha_validation)) {
          $msg_type = 'danger';
          $msg = lang('You must input Captcha');
        }

        if ($captcha->validate($captcha_input, $captcha_validation) == true) {
          $mailer = CreateObject('Lib.Mailer');

          if ($_POST['email'] && ($_POST['subject'] || $_POST['message'])) {
            if ($mailer->send('webmaster@3d-products.com', $_POST['subject'], $_POST['message'], $_POST['email'], $_POST['name']) === true) {
              $msg_type = 'success';
              $msg = lang('Thank you for contacting us! Please wait for our reply.');
            } else {
              $msg_type = 'danger';
              $msg = lang('Sorry, some error happened while sending.');
            }
          }
        } else {
          $msg_type = 'danger';
          $msg = lang('Your verification Captcha was incorrect.');
        }
      }
      $captcha = CreateObject('Lib.Captcha', BETHLEHEM_ROOT_DIR .'/Resources/images/characters');
      $captcha_data = $captcha->generate(4);

      $assign = array(
        'lang_title' => lang('Contact Us'),
        'lang_name' => lang('Name'),
        'lang_email' => lang('Email'),
        'lang_subject' => lang('Subject'),
        'lang_message' => lang('Message...'),
        'lang_submit' => lang('Send'),
        'info_email' => lang('webmaster@3d-products.com'),
        'msg' => ($msg) ? sprintf('<div class="message alert alert-%s" style="text-align: left;">%s<a href="#" class="close" data-dismiss="alert" aria-label="close" title="Close">×</a></div>', $msg_type, $msg) : '',
        //'info_address' => lang('No 87 13F-1, Liuhe Road<span>320 Taoyuan City, Taiwan</span>'),
        'info_address' => lang('https://www.3d-products.com'),
        'info_phone1' => lang('+886 (09) 3007 8033'),
        'info_phone2' => lang('+886 (03) 280 7671'),
        'info_phone_locale' => lang('（台灣）'),
        'VAL_CAPTCHA_PICTURE' => $captcha_data['captcha_picture'],
        'VAL_CAPTCHA_VALIDATION' => $captcha_data['captcha_validate'],
      );

      $GLOBALS['app']->view($assign);
    }

    function eregister()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('車両查定');
      $captcha = CreateObject('Lib.Captcha', BETHLEHEM_ROOT_DIR .'/Resources/images/characters');
      $captcha_data = $captcha->generate(4);

      $makers = array('トヨタ'        => lang('トヨタ'));

      $makers_list = '';
      foreach ($makers as $val => $label) {
        $makers_list .= sprintf('<option value="%s">%s</option>', $val, $label);
      }

      $makers_types = array('FJクルーザー'        => lang('FJクルーザー'));

      $makers_types_list = '';
      foreach ($makers_types as $val => $label) {
        $makers_types_list .= sprintf('<option value="%s">%s</option>', $val, $label);
      }

      $makers_types_year = array('2014年/平成26年'        => lang('2014年/平成26年'));

      $makers_types_year_list = '';
      foreach ($makers_types_year as $val => $label) {
        $makers_types_year_list .= sprintf('<option value="%s">%s</option>', $val, $label);
      }

      $years = array('~5000'        => lang('~5000'));

      $years_list = '';
      foreach ($years as $val => $label) {
        $years_list .= sprintf('<option value="%s">%s</option>', $val, $label);
      }

      $colors = array('白'        => lang('白'));

      $colors_list = '';
      foreach ($colors as $val => $label) {
        $colors_list .= sprintf('<option value="%s">%s</option>', $val, $label);
      }

      $shifts = array('オートマチック'    => lang('オートマチック'),
                      'マニュアル'       => lang('マニュアル'));

      $shifts_list = '';
      foreach ($shifts as $val => $label) {
        $shifts_list .= sprintf('<option value="%s">%s</option>', $val, $label);
      }

      $assign = array(
        'lang_title' => lang('Trial Register'),
        'lang_subtitle' => lang('We Tailor our Service to Your Needs'),
        'lang_subtitle1' => lang('Personal'),
        'lang_subtitle2' => lang('Enterprise'),
        'lang_subtext1' => lang('Are you a sales and your company has not assigned you a CRM software to use? Are you a sales and you feel like the company’s CRM software is too difficult to learn and more sophisticated than necessary? We are here to offer you the perfect solution for personal purpose CRM software hosted on our cloud; you don’t need to pay for LitoCRM and you can master it on the first time. You can start using LitoCRM by signing up or directly login with your Facebook account.'),
        'lang_subtext2' => lang('Are you a micro enterprise founder that has a sales team to be managed? Do you pay thousands of dollars for a CRM software that you do not use extensively and is more sophisticated than necessary? We are here to offer you the perfect solution for personal purpose CRM software hosted on our cloud; you can register a subdomain under 3d-products.com or direct a domain of your desire to LitoCRM for your own sales team and your enterprise. Your company don’t need to pay a penny and we will host your cloud CRM for you.'),
        'lang_register' => lang('Register'),
        'lang_name' => lang('Company Name'),
        'lang_email' => lang('Company Email'),
        'lang_code' => lang('Company Domain'),
        'lang_industry' => lang('Company Industry'),
        'hint_name' => lang('My Corporation Inc.'),
        'hint_email' => lang('adam.smith@mycorporation.com'),
        'lang_user_name' => lang('Your Name'),
        'hint_user_name' => lang('Kevin Wang'),
        'hint_code' => lang('mycorporation.com'),
        'hint_phone' => lang('09-1234-5678'),
        'lang_submit' => lang('Register'),
        'lang_year' => lang('Company Established'),
        'lang_headcount' => lang('Number of Employees'),
        'lang_refer' => lang('Referred by'),
        'lang_phone' => lang('Phone Number'),
        'lang_reason' => lang('Why do you like us?'),
        'val_makers' => $makers_list,
        'val_makers_types' => $makers_types_list,
        'val_makers_types_year' => $makers_types_year_list,
        'val_dists' => $years_list,
        'val_colors' => $colors_list,
        'val_shifts' => $shifts_list,
        'lang_text' => lang('LitoCRM is free for any micro enterprise to use. We are currently open to registration for subdomains under 3d-products.com, but if you are interested in using your own domain name please feel free to contact us and let us know. You can contact us through the <a href="'.$GLOBALS['app']->link('/Home/contact').'">contact us</a> page or send an E-mail to <a href="mailto:webmaster@3d-products.com">webmaster@3d-products.com</a>.'),
        'VAL_CAPTCHA_PICTURE' => $captcha_data['captcha_picture'],
        'VAL_CAPTCHA_VALIDATION' => $captcha_data['captcha_validate'],
        'lang_captch' => lang('Captcha'),
      );

      $GLOBALS['app']->view($assign);
    }

    function newsletter()
    {
      $response = array('failure' => 1, 'success' => 0);
      if (isset($_POST['submit']) && $_POST['submit']) {
        $google_api = CreateObject('Lib.google_api');
        $file_id = '1Yy5knxiBngWQ9rl8_oF58b0Fduqjrf-U8kk8a2OzYF4';
        $data = $google_api->get_file($file_id);
        $data_index = count($data) + 2;
        $data = array($_POST['email'], date('Y-m-d H:i:s'));
        $google_api->write_file($file_id, $data_index, $data);

        $response['success'] = 1;
        $response['failure'] = 0;
        $response['callback'] = array('message' => 'Thank you for subscribing!');
      }

      header('Content-type: application/json');
      echo json_encode($response);
    }
}
