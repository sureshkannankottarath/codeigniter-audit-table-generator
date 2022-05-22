
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuditTableGenerate extends CI_Controller
{

    function  __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->load->view('audit_tables');
    }


    function audit_generate()
    {
        $this->load->model('AuditGenerateModel');
        $res = $this->AuditGenerateModel->auditGenerate($_POST);
        return true;
    }


    function list_db_tables()
    {
        $data = $row = array();

        $this->load->model('DBTablesList');

        // Fetch member's records
        $tablesList = $this->DBTablesList->getRows($_POST);

        $i = $_POST['start'];
        foreach ($tablesList as $table) {
            $table_name = $table->table_name;
            $i++;

            $table_Val = $table_name . '<input type="hidden" name="table_name[]" value="' . $table_name . '">';

            $audit_Val =  '<input type="hidden" name="audit_table_v[]" id="audit_' . $table_name . '" value="0" /><input type="checkbox" id="audit_chk_' . $table_name . '" name="audit_table[]" value="1" onclick="change_value(\'t_'.$table_name.'\',this.id,\'audit\')">';

            $data[] = array($table_Val, $audit_Val);
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->DBTablesList->countAll($_POST),
            "recordsFiltered" => $this->DBTablesList->countFiltered($_POST),
            "data" => $data,
        );


        // Output to JSON format
        echo json_encode($output);
    }
}
