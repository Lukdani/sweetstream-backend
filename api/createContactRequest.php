<?php
require $_SERVER['DOCUMENT_ROOT'] . "/sweetstream-backend/settings/init.php";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
  header('Access-Control-Allow-Headers: token, Content-Type');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  header('Content-Type: text/plain');
  die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$postData = json_decode(file_get_contents('php://input'));
$leadId;

if (!empty($postData)) {
  if (empty($postData->email) && empty($postData->phone)) {
    header('HTTP/1.1 400 Bad Request');
    $error["errorMessage"] = "Neither email address or phone number was found in the request.";
    echo json_encode($error);
    return;
  } 

    $leadSql =
      "INSERT INTO leads (leadName, leadEmail, leadPhone, leadEmailPref, leadPhonePref) VALUES(:leadName, :leadEmail, :leadPhone, :leadEmailPref, :leadPhonePref)";
    $leadBind = [
      ":leadName" => $postData->name,
      ":leadEmail" => $postData->email,
      ":leadPhone" => $postData->phone,
      ":leadEmailPref" => $postData->preferEmail ? 1 : 0,
      ":leadPhonePref" => $postData->preferPhone ? 1 : 0,
    ];
    $db->sql($leadSql, $leadBind, false);

    $leadIdSql = "SELECT leadId FROM leads ORDER BY leadId DESC LIMIT 1";

    $leadId = $db->sql($leadIdSql, null)[0];

  if (!isset($leadId)) {
    header('HTTP/1.1 500 Internal Server Error');
    $error["errorMessage"] =
      "There was an internal server error when retrieving the leadId";
    echo json_encode($error);
    return;
  }

  header("HTTP/1.1 201 Created");
  echo json_encode($leadId->leadId);

} else {
  header('HTTP/1.1 400 Bad Request');
  $error["errorMessage"] = "No data was found in the request.";
  echo json_encode($error);
}
?>