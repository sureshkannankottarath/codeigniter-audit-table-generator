<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'AuditTableGenerate';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['audit'] = "AuditTableGenerate/index";
$route['generateAuditTables'] = "AuditTableGenerate/audit_generate";
$route['list_db_tables'] = "AuditTableGenerate/list_db_tables";
