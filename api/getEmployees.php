<?php
require $_SERVER['DOCUMENT_ROOT'] . "/sweetstream_backend/settings/init.php";

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
 
// Retrieve a lead by ID that has been passed as query parameter;
if (true) {
  $employeesSql =
    "SELECT empId, empName, empDescription, empTitle, empLinkedIn, empImageName, empPhd, titleName FROM employees LEFT JOIN titles ON titles.titleId = empTitle";

  // Fetch the employees;
  $employees = $db->sql($employeesSql);

  foreach ($employees as $employee) {
    $rolesSql = "SELECT roleName, roleId FROM employees_roles JOIN roles ON roles.roleId = role_id WHERE emp_id = " . $employee -> empId;
    $roles = $db->sql($rolesSql);
    $employee -> roles = $roles;
    $employee -> empPhp = $employee -> empPhd === "0" ? false : true;
  }


  header("HTTP/1.1 200 Ok");
  echo json_encode($employees);

} else {
  header('HTTP/1.1 400 Bad Request');
  $error["errorMessage"] = "No lead id was sent in the request.";
  echo json_encode($error);
}
?>