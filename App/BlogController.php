<?php

namespace App;

class BlogController
{
    var $bo;
    var $so;

    function __construct()
    {

    }

    function index()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('Business Cloud Storage Solutions');
      $assign = array(
        'lang_title' => lang('Business Cloud Storage Solutions'),
        'lang_text1' => lang('我公司裡的員工數很多，但是每個人用到的空間不多，一般的雲端空間按帳號數計費，好不划算。'),
        'lang_text2' => lang('我常常在外面跑業務，用手機的網路來存取檔案，檔案上傳到一半網路就斷了，該怎麼辦？'),
        'lang_text3' => lang('我需要分享檔案給需要的同事，但是一般的服務在分享上面會有次數的上限。'),
        'link_text1' => $GLOBALS['app']->link('/Blog/post1'),
        'link_text2' => $GLOBALS['app']->link('/Blog/post2'),
        'link_text3' => $GLOBALS['app']->link('/Blog/post3'),
      );

      $GLOBALS['app']->view($assign);
    }

    function post1()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('我公司裡的員工數很多，但是每個人用到的空間不多，一般的雲端空間按帳號數計費，好不划算。');
      $assign = array(
        'lang_title' => lang('我公司裡的員工數很多，但是每個人用到的空間不多，一般的雲端空間按帳號數計費，好不划算。'),
        'lang_text1' => lang('市場上80%以上的雲端軟體都是採使用者數付費機制的。這種付費機制並不一定符合很多公司內部的使用情境，很多大工廠的使用者如果只是偶爾用到卻要每個月付高昂的費用的話，其實是不符合公司的成本效益的。反而，很多公司會採用帳號共用、借用等情況；這可能會導至違反合約和走在法律的灰色地帶等。我們深知這些公司的需求，所以打算改革這個雲端軟體的市場。CloudFiles 是一個以雲端空間來計價的雲端軟體，真的是用多少付多少的價格讓您的公司擁遠使用最便宜、優質的服務。您的公司員工有再多也不要緊，我們不按使用者帳號數計費。您可以建無限數量的使用者供公司的需求或喜好。您甚至不必擔心要刪除帳號，更不必擔心錯誤產生的意外費用！'),
      );

      $GLOBALS['app']->view($assign);
    }

    function post2()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('我常常在外面跑業務，用手機的網路來存取檔案，檔案上傳到一半網路就斷了，該怎麼辦？');
      $assign = array(
        'lang_title' => lang('我常常在外面跑業務，用手機的網路來存取檔案，檔案上傳到一半網路就斷了，該怎麼辦？'),
        'lang_text1' => lang('身為一位業務，您可能常常在外面使用很不穩定的手機網路來上傳您的檔案，或是您可能有時上傳一個大檔案到一半需要移動、需要開會、或是需要上飛機。一般的各大知名雲端空間不提供給使用者可以續傳的服務，但是續傳不失為一個在這時常更移動和更緊密的工作環境所需要的功能和特色。CloudFiles 提供傳用者可以續傳的功能。您的大檔案上傳到一半時，不論是網路斷線了、需要去開會或是需要上飛機時，您都可以在您下次方便的時候再繼續。'),
        'lang_text2' => lang('相較一般的 2GB 的單檔上限，我們讓您可以上傳 5GB 的檔案！有些即使能夠上傳更大的單檔，如果沒有續傳的功能，您可能不一定可以方便上傳到這麼大的檔案。我們的繼傳讓您可以確實有效的利用這麼大的單檔案上限。'),
      );

      $GLOBALS['app']->view($assign);
    }

    function post3()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('我需要分享檔案給需要的同事，但是一般的服務在分享上面會有次數的上限。');
      $assign = array(
        'lang_title' => lang('我需要分享檔案給需要的同事，但是一般的服務在分享上面會有次數的上限。'),
        'lang_text1' => lang('現在市場上大部份的雲端服務場商都不是針對檔案外部共享這一塊來研發雲端檔案的協作軟體的。要不是有針對一個檔案的下載上限、或是檔案在下載之後並沒有留下任何的下載記錄。CloudFiles 的雲端檔案共享空間讓您的企業能夠有全方位的檔案方享解決方案，您不只能和企業內部的人員隨意的分享您想分享的檔案，在檔案被下載之後您也會留有檔案被哪一位使用者、哪一個 IP 位置或是什麼時間被下載的。CloudFiles 也能讓您和企業外部的人員分享檔案，外部的人員可以在不登入的情況下下載檔案。在和企業外部的人分享檔案時，CloudFiles 在下載方面是沒有下載的次數限制的，所以您也可以以 CloudFiles 在發佈宣傳企劃等檔案。'),
      );

      $GLOBALS['app']->view($assign);
    }
}
