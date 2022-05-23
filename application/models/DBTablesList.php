<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class DBTablesList extends CI_Model
{

    function __construct()
    {
        // Set table name
        $this->table = 'information_schema.tables';
        // Set database name
        $this->user_db = $this->db->database;
        // Set orderable column fields
        $this->column_order = array(null);
        // Set default order
        $this->order = array('tb.table_name' => 'desc');
    }

    /*
     * Fetch members data from the database
     * @param $_POST filter data based on the posted parameters
     */
    public function getRows($postData)
    {
        $this->_get_datatables_query($postData);
        if ($postData['length'] != -1) {
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    /*
     * Count all records
     */
    public function countAll($postData)
    {

        $this->db->select('tb.table_name')
            ->from('information_schema.tables tb')
            ->join($this->user_db.'.audit_tables_history ath', 'tb.table_name = ath.parent_table', 'LEFT')
            ->where('tb.table_schema', $this->user_db)
            ->where('ath.parent_table', null)
            ->where('tb.table_type', 'BASE TABLE')
            ->not_like('tb.table_name', 'audit_', 'both');

        return $this->db->count_all_results();
    }

    /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData)
    {
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    private function _get_datatables_query($postData)
    {


        if ($postData['search']['value']) {
            $this->db->select('tb.table_name')
            ->from('information_schema.tables tb')
            ->join($this->user_db.'.audit_tables_history ath', 'tb.table_name = ath.parent_table', 'LEFT')
            ->where('tb.table_schema', $this->user_db)
            ->where('ath.parent_table', null)
            ->where('tb.table_type', 'BASE TABLE')
            ->not_like('tb.table_name', 'audit_', 'both')
            ->like('tb.table_name', $postData['search']['value'], 'tb');
        } else {
            $this->db->select('tb.table_name')
            ->from('information_schema.tables tb')
            ->join($this->user_db.'.audit_tables_history ath', 'tb.table_name = ath.parent_table', 'LEFT')
            ->where('tb.table_schema', $this->user_db)
            ->where('ath.parent_table', null)
            ->where('tb.table_type', 'BASE TABLE')
            ->not_like('tb.table_name', 'audit_', 'both');
        }






        if (isset($postData['order'])) {
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}
