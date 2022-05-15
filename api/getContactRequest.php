<?php
require $_SERVER['DOCUMENT_ROOT'] . "/sweetstream_backend/settings/init.php";

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
 
// Retrieve a lead by ID that has been passed as query parameter;
if (isset($_GET["id"])) {
  $leadSql =
    "SELECT * FROM leads WHERE leadId = " . $_GET["id"] . " LIMIT 1";

  // Fetch the lead;
  $leads = $db->sql($leadSql);
  $lead = $leads[0];

  $lead->leadEmailPref = $lead->leadEmailPref === "0" ? false : true;
  $lead->leadPhonePref = $lead->leadPhonePref === "0" ? false : true;

  // Return the lead, which is index 0 of the array of leads;
  header("HTTP/1.1 200 Ok");
  echo json_encode($lead);
} else {
  header('HTTP/1.1 400 Bad Request');
  $error["errorMessage"] = "No lead id was sent in the request.";
  echo json_encode($error);
}
?>