<?php

namespace App;

class TutorialController
{
    var $bo;
    var $so;

    function __construct()
    {

    }

    function index()
    {
        $GLOBALS['bethlehem']['setting']['page_title'] = lang('Tutorial');
        $assign = array(
          'lang_title' => lang('Tutorial'),
          'lang_subtitle' => lang('Look for the Admin and User Manuals'),
          'link_user' => $GLOBALS['app']->link('/Tutorial/user'),
          'lang_subtitle1' => lang('使用者'),
          'lang_subtext1' => lang('如何在 CloudFiles 的雲端空間之內上傳並管理您的檔案。分享您的檔案給團隊裡的其它使用者。'),
          'link_admin' => $GLOBALS['app']->link('/Tutorial/admin'),
          'lang_subtitle2' => lang('管理員'),
          'lang_subtext2' => lang('如何在 CloudFiles 上把您的使用者管理的得心應手。新增、編輯或是刪除使用者。'),
        );

        $GLOBALS['app']->view($assign);
    }

    function user()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('User Manuals');
      $assign = array(
        'lang_title' => lang('User Manuals'),
        'lang_text1' => lang('How to Upload Files?'),
        'lang_text2' => lang('How to rename Files?'),
        'lang_text3' => lang('How to share Files or Folders with other team members?'),
        'lang_text4' => lang('How to view the historial records of specific Files?'),
        'lang_text5' => lang('How to create new Folder or Directory?'),
        'lang_text6' => lang('How to move Files or Folders to other Directories?'),
        'lang_text7' => lang('How to delete Files or Folders?'),
        'lang_text8' => lang('How to search for specific File or Folder names?'),
        'lang_text9' => lang('How to browse Files or Folders shared by other team members?'),
        'lang_text10' => lang('How to reset password?'),
        'lang_text11' => lang('How to change display language?'),
        'lang_text12' => lang('How to change the number of display records?'),
        'link_text1' => $GLOBALS['app']->link('/Tutorial/user1'),
        'link_text2' => $GLOBALS['app']->link('/Tutorial/user2'),
        'link_text3' => $GLOBALS['app']->link('/Tutorial/user3'),
        'link_text4' => $GLOBALS['app']->link('/Tutorial/user4'),
        'link_text5' => $GLOBALS['app']->link('/Tutorial/user5'),
        'link_text6' => $GLOBALS['app']->link('/Tutorial/user6'),
        'link_text7' => $GLOBALS['app']->link('/Tutorial/user7'),
        'link_text8' => $GLOBALS['app']->link('/Tutorial/user8'),
        'link_text9' => $GLOBALS['app']->link('/Tutorial/user9'),
        'link_text10' => $GLOBALS['app']->link('/Tutorial/user10'),
        'link_text11' => $GLOBALS['app']->link('/Tutorial/user11'),
        'link_text12' => $GLOBALS['app']->link('/Tutorial/user12'),
      );

      $GLOBALS['app']->view($assign);
    }

    function admin()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('Admin Manuals');
      $assign = array(
        'lang_title' => lang('Admin Manuals'),
        'lang_text1' => lang('How to Add Users?'),
        'lang_text2' => lang('How to Edit Users?'),
        'lang_text3' => lang('How to Delete Users?'),
        'lang_text4' => lang('How to check the the team space disk usage?'),
        'lang_text5' => lang('How to search for a specific user by keywords?'),
        'link_text1' => $GLOBALS['app']->link('/Tutorial/admin1'),
        'link_text2' => $GLOBALS['app']->link('/Tutorial/admin2'),
        'link_text3' => $GLOBALS['app']->link('/Tutorial/admin3'),
        'link_text4' => $GLOBALS['app']->link('/Tutorial/admin4'),
        'link_text5' => $GLOBALS['app']->link('/Tutorial/admin5'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user1()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to Upload Files?');
      $assign = array(
        'lang_title' => lang('How to Upload Files?'),
        'lang_text1' => lang('1. Click on the upload button on the page'),
        'lang_text2' => lang('2. Click on the upload block area or drag and drop files onto the area'),
        'lang_text3' => lang('3. Files that are in queue to upload or have done uploading will be displayed under the uplaod block respectively'),
        'lang_text4' => lang('4. Click on the Close button or press the "Esc" key to close the upload dialog'),
        'lang_text5' => lang('5. Uploaded files will be displayed in the file records'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user2()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to rename Files?');
      $assign = array(
        'lang_title' => lang('How to rename Files?'),
        'lang_text1' => lang('1. Click on the "more" button for the specific file on the page'),
        'lang_text2' => lang('2. Click on "rename" option in the drop down menu'),
        'lang_text3' => lang('3. Enter the desired new name for the file'),
        'lang_text4' => lang('4. Click on the "OK" button to save the new name and close the dialog'),
        'lang_text5' => lang('5. The selected file will be renamed to the new target name'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user3()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to share Files or Folders with other team members?');
      $assign = array(
        'lang_title' => lang('How to share Files or Folders with other team members?'),
        'lang_text1' => lang('1. Click on the "more" button for the specific file on the page'),
        'lang_text2' => lang('2. Click on "share" option in the drop down menu'),
        'lang_text3' => lang('3. Select and check the team members that you wish to share the file or the folder to'),
        'lang_text4' => lang('4. Click on the "OK" button to save the sharing and close the dialog'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user4()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to view the historial records of specific Files?');
      $assign = array(
        'lang_title' => lang('How to view the historial records of specific Files?'),
        'lang_text1' => lang('1. Click on the "more" button for the specific file on the page'),
        'lang_text2' => lang('2. Click on "log" option in the drop down menu'),
        'lang_text3' => lang('3. You will be able to see all the historical records related to the specific file and close to return to the original file list'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user5()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to create new Folder or Directory?');
      $assign = array(
        'lang_title' => lang('How to create new Folder or Directory?'),
        'lang_text1' => lang('1. Click on the New Folder icon to open the new folder dialog'),
        'lang_text2' => lang('2. Enter the name for the new folder'),
        'lang_text3' => lang('3. Click on "OK" to confirm'),
        'lang_text4' => lang('4. The new folder will appear on the files and directories list'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user6()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to move Files or Folders to other Directories?');
      $assign = array(
        'lang_title' => lang('How to move Files or Folders to other Directories?'),
        'lang_text1' => lang('1. Select and check the files and folders to move'),
        'lang_text2' => lang('2. Click on the Move icon'),
        'lang_text3' => lang('3. Click on and highlight the directory to move to; the highlighted folder will be colored in blue'),
        'lang_text4' => lang('4. Click on "OK" to confirm and move'),
        'lang_text5' => lang('5. The moved files and folders will appear under the target directory'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user7()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to delete Files or Folders?');
      $assign = array(
        'lang_title' => lang('How to delete Files or Folders?'),
        'lang_text1' => lang('1. Click on the "more" button for the specific file on the page'),
        'lang_text2' => lang('2. Click on the "Delete" button to delete the selected file'),
        'lang_text3' => lang('3. The concerned file will be deleted'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user8()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to search for specific File or Folder names?');
      $assign = array(
        'lang_title' => lang('How to search for specific File or Folder names?'),
        'lang_text1' => lang('1. Enter Search keywords in the top left of the file records'),
        'lang_text2' => lang('2. Click on the "Enter" key, and the matching files or folders will appear'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user9()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to browse Files or Folders shared by other team members?');
      $assign = array(
        'lang_title' => lang('How to browse Files or Folders shared by other team members?'),
        'lang_text1' => lang('1. Click on the "Team" option on the top right corner of the page'),
        'lang_text2' => lang('2. You will be able to find the files and folders shared by other team members here'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user10()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to reset password?');
      $assign = array(
        'lang_title' => lang('How to reset password?'),
        'lang_text1' => lang('1. Click on the "Settings" option on the top right corner of the page'),
        'lang_text2' => lang('2. Enter the new desired password twice and click on "Save" to save the password'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user11()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to change display language?');
      $assign = array(
        'lang_title' => lang('How to change display language?'),
        'lang_text1' => lang('1. Click on the "Team" option on the top right corner of the page'),
        'lang_text2' => lang('2. Select the desired display language and click on "Save" to save the preference'),
      );

      $GLOBALS['app']->view($assign);
    }

    function user12()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to change the number of display records?');
      $assign = array(
        'lang_title' => lang('How to change the number of display records?'),
        'lang_text1' => lang('1. Click on the "Team" option on the top right corner of the page'),
        'lang_text2' => lang('2. Enter the number of records desired to be displayed and click on "Save" to save the preference'),
      );

      $GLOBALS['app']->view($assign);
    }

    function admin1()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to Add Users?');
      $assign = array(
        'lang_title' => lang('How to Add Users?'),
        'lang_text1' => lang('1. Click on the add user button on the page'),
        'lang_text2' => lang('2. Enter the corresponding user information and click on "Save" to add the user'),
        'lang_text3' => lang('Reset Password: Reset the password to a temporary password; the user will receive the temporary password by E-mail and be asked to set a new one.'),
        'lang_text4' => lang('Account Name: The displayed name of the user.'),
        'lang_text5' => lang('Username: The username that the user will use to login to their account.'),
        'lang_text6' => lang('Usage Quota: A specific spage usage quota set for this user. If this is left empty, the user will see the team usage quota.'),
        'lang_text7' => lang('E-mail: The E-mail address used to contact the user for password reset and download notifications.'),
        'lang_text8' => lang('Set as Admin: The account role of the user. User can only see the file manager interface. Admin can see and edit user settings.'),
        'lang_text9' => lang('Account Language: The displayed language for the user.'),
        'lang_text10' => lang('Results per Page: The number of file or user records displayed for the user.'),
        'lang_text11' => lang('Account Status: The account status for the user. Enabled for active accounts. Disabled for deactivated accounts.'),
      );

      $GLOBALS['app']->view($assign);
    }

    function admin2()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to Edit Users?');
      $assign = array(
        'lang_title' => lang('How to Edit Users?'),
        'lang_text1' => lang('1. Click on the Pencil icon for the specific user on the page'),
        'lang_text2' => lang('2. Edit the corresponding user information and click on "Save" to confirm edit.'),
      );

      $GLOBALS['app']->view($assign);
    }

    function admin3()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to Delete Users?');
      $assign = array(
        'lang_title' => lang('How to Delete Users?'),
        'lang_text1' => lang('1. Click on the Trash Bin icon for the specific user on the page'),
        'lang_text2' => lang('2. Click on "OK" to confirm user delete'),
        'lang_text3' => lang('3. The user will be deleted from the system, the files and folders will be reassigned to the operated admin'),
      );

      $GLOBALS['app']->view($assign);
    }

    function admin4()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to check the the team space disk usage?');
      $assign = array(
        'lang_title' => lang('How to check the the team space disk usage?'),
        'lang_text1' => lang('1. Click on the wrench icon on the page'),
        'lang_text2' => lang('2. You will be able to edit the team/organization name and see the total disk space usage'),
      );

      $GLOBALS['app']->view($assign);
    }

    function admin5()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('How to search for a specific user by keywords?');
      $assign = array(
        'lang_title' => lang('How to search for a specific user by keywords?'),
        'lang_text1' => lang('1. Click on the Search Menu to select the user attribute that you wish to search for by keyword'),
        'lang_text2' => lang('2. Enter the keyword and press on "Enter" key, the matching users will come up'),
      );

      $GLOBALS['app']->view($assign);
    }

    function tutorial()
    {
      $GLOBALS['bethlehem']['setting']['page_title'] = lang('使用軟體介紹');
      $assign = array(
        'lang_title' => lang('使用軟體介紹'),
        'lang_subtitle' => lang('簡單的操作'),

        'lang_subtitle1' => lang('使用者登入'),
        'lang_subtext1' => lang('簡單可以客製化的登入介面，企業可以結合其它的單一登入服務。'),

        'lang_subtitle2' => lang('檔案介面'),
        'lang_subtext2' => lang('可以像在一般電腦上去瀏覽所有的檔案。<br />資料夾的架構<br />檔案的大小、上傳的時間<br />可以重新命名、移動或刪除檔案'),

        'lang_subtitle3' => lang('上傳優化'),
        'lang_subtext3' => lang('可以續傳（斷線也沒有關係）<br />托拉上傳<br />可以有上傳的動態'),

        'lang_subtitle4' => lang('團隊檔案'),
        'lang_subtext4' => lang('可以看到其它使用者所分享的檔案。<br />其它使用者分享檔案為唯讀<br />可以看到檔案所屬的作者<br />只要分享資料夾，資料夾下面的檔案會自動繼承'),

        'lang_subtitle5' => lang('管理員'),
        'lang_subtext5' => lang('管理員可以無限的新增使用者帳號，不需要擔心額外的收費。'),

        'lang_subtitle6' => lang('使用者管理'),
        'lang_subtext6' => lang('管理員可以針對每使用者設定：<br />空間使用上限、管理員權限、重設密碼<br />啟用、停用'),

        'lang_subtitle7' => lang('行動裝置優化'),
        'lang_subtext7' => lang('針對窄螢幕的行動裝置介面，可以輕鬆的在任何裝置上存取。'),

        'lang_subtitle8' => lang('檔案記錄'),
        'lang_subtext8' => lang('檔案上傳、編輯、下載都會留有記錄<br />下載的資訊也會留有記錄<br />可以持續追蹤檔案。'),


        'lang_trial' => lang('Trial'),
        'link_trial' => $GLOBALS['app']->link('/Home/eregister'),
      );

      $GLOBALS['app']->view($assign);
    }
}
