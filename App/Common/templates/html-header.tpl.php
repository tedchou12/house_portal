<!DOCTYPE html>
<html>
<head>
  <title><?= $this->VARS['lang_title'] ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="keywords" content="<?= $this->VARS['lang_keywords'] ?>" />
  <meta name="description" content="<?= $this->VARS['lang_description'] ?>" />
  <meta property="og:title" content="<?= $this->VARS['lang_ogtitle'] ?>" />
  <meta property="og:description" content="<?= $this->VARS['lang_ogdescription'] ?>" />
  <meta property="og:image" content="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/android-chrome-192x192.png" />
  <!-- //for-mobile-apps -->
  <link href="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all" />
  <link href="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/css/style.css" rel="stylesheet" type="text/css" media="all" />
  <!-- js -->
  <script src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/js/jquery-1.11.1.min.js"></script>
  <script src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/js/jquery.blockUI.js"></script>
  <script src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/js/bootstrap.min.js"></script>
  <!-- //js -->
  <!-- animation-effect -->
  <link rel="stylesheet" href="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/css/font-awesome/css/font-awesome.min.css" />
  <link href="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/css/animate.min.css" rel="stylesheet" />
  <script src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/js/wow.min.js"></script>
  <!-- favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/apple-touch-icon.png" />
  <link rel="icon" type="image/png" href="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/favicon-16x16.png" sizes="16x16" />
  <link rel="manifest" href="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/manifest.json" />
  <link rel="mask-icon" href="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/safari-pinned-tab.svg" color="#5bbad5" />
  <script>
   new WOW().init();
  </script>
  <!-- //animation-effect -->
</head>
<body>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-84734904-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-84734904-1');
  </script>
