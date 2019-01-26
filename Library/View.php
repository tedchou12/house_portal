<?php

namespace Library;
require_once dirname(__FILE__) .'/Savant3/Savant3.php';

class View
{
    var $template;

    function __construct($app='')
    {
        if (!$app) {
            $app = $GLOBALS['app']->route->app;
        }

        $this->template = new \Savant3(array(
            'template_path' => sprintf('%s/%s/templates', BETHLEHEM_APP_DIR, $app),
            'resource_path' => sprintf('%s/%s/templates/images', BETHLEHEM_APP_DIR, $app),
        ));
    }

    function assign($key='', $value='')
    {
        if ($key) {
            $this->template->assign($key, $value);
        }
    }

    function setTemplate($name='')
    {
        if ($name) {
            $this->name = $name;
            $this->template->setTemplate($this->name);
        }
    }

    function display($return_html=False)
    {
        if ($return_html === True) {
            return $this->template->fetch($this->name);
        }

        echo $this->template->fetch($this->name);
    }

    function displayHtmlHeader()
    {
        $t = new \Savant3();
        $t = new \Savant3(array(
            'template_path' => sprintf('%s/%s/templates', BETHLEHEM_APP_DIR, 'Common'),
            'resource_path' => sprintf('%s/%s/templates/images', BETHLEHEM_APP_DIR, 'Common'),
        ));
        $t->setTemplate('html-header.tpl.php');

        $keywords = array('CloudFiles',
                          '雲端空間',
                          '雲端硬碟',
                          '空間',
                          '線上空間',
                          '線上硬碟',
                          '企業軟體',
                          'enterprise software',
                          'cloud storage',
                          'cloud drive',
                          'storage',
                          'drive',
                          'online storage');

        $assigne = array(
            'lang_title' => ($GLOBALS['bethlehem']['setting']['page_title']) ? sprintf('%s | %s', $GLOBALS['bethlehem']['setting']['page_title'], lang('Unix estate Inc.')) : lang('Unix estate Inc.'),
            'lang_description' => lang('CloudFiles is a tailored cloud storage solution for commercial use; it can help you manage all your files cheaply and securely in the cloud.'),
            'lang_keywords' => implode(', ', $keywords),
            'lang_ogtitle' => lang('CloudFiles - The Cheapest Cloud Storage Service'),
            'lang_ogdescription' =>lang('CloudFiles is the cheapest cloud storage solution. Get yours today!'),
            'URL_ROOT_DIR' => $GLOBALS['app']->link('/'),
            'URL_RESOURCE_DIR' => $GLOBALS['app']->link('/Resources'),
            'TXT_CURRENT_APP' => $GLOBALS['app']->route->app,
        );

        $t->assign('VARS', $assigne);
        return $t->fetch('html-header.tpl.php');
    }

    function displayHtmlFooter()
    {
        $t = new \Savant3();
        $t = new \Savant3(array(
            'template_path' => sprintf('%s/%s/templates', BETHLEHEM_APP_DIR, 'Common'),
            'resource_path' => sprintf('%s/%s/templates/images', BETHLEHEM_APP_DIR, 'Common'),
        ));
        $t->setTemplate('html-footer.tpl.php');

        $assigne = array('link_about' => $GLOBALS['app']->link('/Home/about'),
                        'link_contact' => $GLOBALS['app']->link('/Home/contact'),
                        'link_facebook' => 'https://www.facebook.com/3dproductscom/',
                        'link_twitter' => 'https://twitter.com/3d_products',
                        'lang_faq' => lang('FAQ'),
                        'lang_terms' => lang('Terms &amp; Conditions'),
                        'lang_privacy' => lang('Privacy Policy'),
                        'lang_information' => lang('Information'),
                        'lang_sitemap' => lang('Site Map'),
                        'lang_blog' => lang('Blog'),
                        'lang_news' => lang('News'),
                        'lang_about' => lang('About'),
                        'lang_newsletter' => lang('Newsletter'),
                        'lang_newsletter_text' => lang('Please enter your E-mail Address here to subscribe to our latest updates. You will be informed of new product features, discounts or campaigns.'),//Proin congue metus mi, nec tempor tellus consectetur eget. Cum sociis natoque penatibus et magnis dis parturient montes.'),
                        'placeholder_newsletter' => lang('Enter Your Email'),
                        'lang_subscribe' => lang('Subscribe'),
                        'lang_tutorial' => lang('Manuals'),
                        'lang_features' => lang('Features'),
                        'lang_partners' => lang('Partners'),
                        'link_blog' => $GLOBALS['app']->link('/Blog'),
                        'link_tutorial' => $GLOBALS['app']->link('/Tutorial/index'),
                        'link_features' => $GLOBALS['app']->link('/Home/features'),
                        'link_partners' => $GLOBALS['app']->link('/Home/partners'),
                        'link_about' => $GLOBALS['app']->link('/Home/about'),
                        'URL_ROOT_DIR' => $GLOBALS['app']->link('/'),
                        'TXT_CURRENT_APP' => $GLOBALS['app']->route->app,
                        'TXT_APP_MESSAGE' => $GLOBALS['app']->session->getMessage(),
                        'URL_APP_ADD' => $GLOBALS['app']->link("/{$GLOBALS['app']->route->app}/add"),
                        'URL_APP_EDIT' => $GLOBALS['app']->link("/{$GLOBALS['app']->route->app}/edit"),
                        'URL_APP_DELETE' => $GLOBALS['app']->link("/{$GLOBALS['app']->route->app}/delete"),
                        'URL_RESOURCE_DIR' => $GLOBALS['app']->link('/Resources'),
                    );

        $t->assign('VARS', $assigne);
        $t->assign('LANGS', $GLOBALS['app']->i18n->string_data);
        return $t->fetch('html-footer.tpl.php');
    }

    function displayNavBar()
    {
        $t = new \Savant3();
        $t = new \Savant3(array(
            'template_path' => sprintf('%s/%s/templates', BETHLEHEM_APP_DIR, 'Common'),
            'resource_path' => sprintf('%s/%s/templates/images', BETHLEHEM_APP_DIR, 'Common'),
        ));
        // //if ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/Home' || $_SERVER['REQUEST_URI'] == '/Home/') {
        //   $t->setTemplate('navbar-home.tpl.php');
        // } else {
        //   $t->setTemplate('navbar.tpl.php');
        // }

        $langs = array(array('link' => 'https://www.3d-products.com/zh/'.$GLOBALS['app']->route->app.'/'.$GLOBALS['app']->route->action, 'label' => '中文', 'selected' => ($GLOBALS['app']->lang == 'zh') ? true : false,),
                       array('link' => 'https://www.3d-products.com/en/'.$GLOBALS['app']->route->app.'/'.$GLOBALS['app']->route->action, 'label' => 'English', 'selected' => ($GLOBALS['app']->lang == 'en') ? true : false,),
                       array('link' => 'https://www.3d-products.com/ja/'.$GLOBALS['app']->route->app.'/'.$GLOBALS['app']->route->action, 'label' => '日本語', 'selected' => ($GLOBALS['app']->lang == 'ja') ? true : false,));

        $assigne = array(
            'lang_home' => lang('Home'),
            'lang_service' => lang('Pricing'),
            'lang_contact' => lang('Contact'),
            'lang_lang' => lang('Language'),
            'lang_login' => lang('Login'),
            'lang_eregister' => lang('Trial'),
            'link_eregister' => $GLOBALS['app']->link('/Home/eregister'),
            'link_home' => $GLOBALS['app']->link('/'),
            'link_about' => $GLOBALS['app']->link('/Home/about'),
            'link_contact' => $GLOBALS['app']->link('/Home/contact'),
            'link_login' => 'https://cloud-files.3d-products.com/login.php',
            'links_lang' => $langs,

            'URL_ROOT_DIR' => $GLOBALS['app']->link('/'),
            'URL_RESOURCE_DIR' => $GLOBALS['app']->link('/Resources'),
            //'HTML_NAVBAR' => implode('', $menu),
        );

        $t->assign('VARS', $assigne);
        //if ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/Home' || $_SERVER['REQUEST_URI'] == '/Home/') {
          //$t->setTemplate('navbar-home.tpl.php');
          //return $t->fetch('navbar-home.tpl.php');
        //} else {
          //$t->setTemplate('navbar.tpl.php');
          return $t->fetch('navbar.tpl.php');
        //}

    }
}
