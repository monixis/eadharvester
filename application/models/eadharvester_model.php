<?php
class eadharvester_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }


    public function insert_institute($data,$table){

        $this ->db ->trans_start();

        $this->db->insert($table, $data);

        if ($this->db->affected_rows() > 0) {
            $rId = 'request_id';
            $this->db->trans_complete();
            $maxval = $this->getmaxid($rId, $table);
            return $maxval;

        }else {
            return $this->db->_error_message().print_r("");
        }


    }

    public function insert_val_log($data,$table){

        $this ->db ->trans_start();

        $this->db->insert($table, $data);

        if ($this->db->affected_rows() > 0) {
            $rId = 'log_id';
            $this->db->trans_complete();
            $maxval = $this->getmaxid($rId, $table);
            return $maxval;

        }else {
            return $this->db->_error_message().print_r("");
        }


    }

    public function getmaxid($col, $table)
    {
        $this->db->select_max($col);
        $query = $this->db->get($table);
        foreach ($query->result() as $row) {
            $maxval = $row->$col;

        }
        return $maxval;
    }


    public function getResults($reqId){


        $this ->db ->trans_start();
        $sql = "SELECT * FROM request_val_log where request_val_log.req_id = '$reqId' ORDER BY request_val_log.log_id DESC";
        $results = $this->db->query($sql, array($reqId));
        if($results != null) {
            return $results->result();
        }
        else{

            return 0;
        }
    }
}

