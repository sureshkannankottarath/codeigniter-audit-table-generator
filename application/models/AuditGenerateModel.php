<?php
defined('BASEPATH') or exit('No direct script access allowed');


class AuditGenerateModel extends CI_Model
{

  // generating the audit table and triggers for the selected tables
  public function auditGenerate($inputData)
  {
    $table_name = $inputData['table_name'];
    $db_name = $this->db->database;

    foreach ($inputData['table_name'] as $key => $n) {
      //Transaction Begins
      $this->db->trans_begin();

      //checking audit table needed
      if ($inputData['audit_table_v'][$key] == '1') {

        //extrating audit table needed table name
        $table_name = $inputData['table_name'][$key];

        //creating audit table from origin
        $this->db->query('CREATE TABLE audit_' . $table_name . '(SELECT * FROM ' . $table_name . ' WHERE 0=1)');
        $res = $this->db->query("SELECT k.COLUMN_NAME
        FROM information_schema.table_constraints t
        JOIN information_schema.key_column_usage k
        USING(constraint_name,table_schema,table_name)
        WHERE t.constraint_type='PRIMARY KEY'
          AND t.table_schema='" . $db_name . "'
          AND t.table_name='" . $table_name . "'");

        //get primary key column name
        $primary_key = $res->result_array()[0]['COLUMN_NAME'];

        //alerting audit table
        $this->db->query("ALTER TABLE audit_" . $table_name . " 
        CHANGE COLUMN " . $primary_key . " audit_id int NOT NULL AUTO_INCREMENT,
        ADD PRIMARY KEY (audit_id),
        ADD COLUMN element_id int AFTER audit_id,
        ADD COLUMN `action` enum('insert','update','delete')");

        
        //insert trigger
        $this->db->query('CREATE TRIGGER audit_' . $table_name . '_insert
          AFTER INSERT ON ' . $table_name . '
          FOR EACH ROW
            INSERT INTO  
            audit_' . $table_name . '
            (
              SELECT NULL AS audit_id,' . $table_name . '.*,"insert" AS action FROM ' . $table_name . ' WHERE ' . $table_name . '.' . $primary_key . ' = new.' . $primary_key . '
            )');
 


        // update trigger
        $this->db->query('CREATE TRIGGER audit_' . $table_name . '_update
          AFTER UPDATE ON ' . $table_name . '
          FOR EACH ROW
            INSERT INTO  
            audit_' . $table_name . '
            (
              SELECT NULL AS audit_id,' . $table_name . '.*,"update" AS action FROM ' . $table_name . ' WHERE ' . $table_name . '.' . $primary_key . ' = new.' . $primary_key . '
            )');


        
        //delete trigger
        $this->db->query('CREATE TRIGGER audit_' . $table_name . '_delete
          BEFORE DELETE ON ' . $table_name . '
          FOR EACH ROW
            INSERT INTO  
            audit_' . $table_name . '
            (
              SELECT NULL AS audit_id,' . $table_name . '.*,"delete" AS action FROM ' . $table_name . ' WHERE ' . $table_name . '.' . $primary_key . ' = old.' . $primary_key . '
            )');

        $this->db->insert('audit_tables_history', array('audit_table_name' => 'audit_' . $table_name, 'parent_table' => $table_name));
      }

      //checking transaction status
      if ($this->db->trans_status() === FALSE) {
        //reverting the changes
        $this->db->trans_rollback();
      } else {
        //transaction successfully completed
        $this->db->trans_commit();
      }
    }
  }
}
