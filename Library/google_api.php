<?php
namespace Library;

class google_api
{
  var $var;

  function __construct()
  {
    $path_src = dirname(__FILE__) .'/google-api/vendor/autoload.php';

    if (file_exists($path_src)) {
      require_once $path_src;
    } else {
      die('Google API has not been installed');
    }

    $this->authorization();
  }

  function authorization()
  {
    $this->client = new \Google_Client();
    $this->client->setApplicationName('Google Sheets API PHP Quickstart');
    $this->client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
    $this->client->setAuthConfig('Library/client_secret.json');
    $this->client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = 'Library/credentials.json';
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        $authUrl = $this->client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $this->client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($this->client->isAccessTokenExpired()) {
        $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($this->client->getAccessToken()));
    }
  }

  function get_file($file_id='')
  {
    $sheets = new \Google_Service_Sheets($this->client);

    $range = 'A2:C99';
    $data = array();
    $rows = $sheets->spreadsheets_values->get($file_id, $range, ['majorDimension' => 'ROWS']);
    if (isset($rows['values'])) {
      foreach ($rows['values'] as $row) {
        /*
        * If first column is empty, consider it an empty row and skip (this is just for example)
        */
        if (empty($row[0])) {
          continue;
        }

        $data[] = $row;
      }
    }

    return $data;
  }

  function write_file($file_id='', $data_index=0, $data)
  {
    // $gdrive_url = 'https://docs.google.com/document/d/';
    $sheets = new \Google_Service_Sheets($this->client);
    /*
    * Now for each row we've seen, lets update the I column with the current date
    */
    $updateRange = 'A'.$data_index;
    $updateBody = new \Google_Service_Sheets_ValueRange([
        'range' => $updateRange,
        'majorDimension' => 'ROWS',
        'values' => array('values' => $data),
    ]);
    $sheets->spreadsheets_values->update(
        $file_id,
        $updateRange,
        $updateBody,
        array('valueInputOption' => 'USER_ENTERED')
    );
  }
}
?>
