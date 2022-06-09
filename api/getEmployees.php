<?php
require $_SERVER['DOCUMENT_ROOT'] . "/sweetstream-backend/settings/init.php";

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
 
$employeesSql =
  "SELECT empId, empName, empDescription, empTitle, empLinkedIn, empImageName, empPhd, titleName FROM employees LEFT JOIN titles ON titles.titleId = empTitle";


$employees = $db->sql($employeesSql);

foreach ($employees as $employee) {
  $rolesSql = "SELECT roleName, roleId FROM employees_roles JOIN roles ON roles.roleId = role_id WHERE emp_id = " . $employee -> empId . " ORDER BY roleName";
  $roles = $db->sql($rolesSql);
  $employee -> roles = $roles;
  $employee -> empPhp = $employee -> empPhd === "0" ? false : true;
}

header("HTTP/1.1 200 Ok");
echo json_encode($employees);

?>