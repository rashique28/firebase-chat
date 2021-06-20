<?php 

class Common_model extends CI_Model 
{
	function __construct() {
     	parent::__construct();
    }

	public function getRecords($table, $fields="", $condition="", $orderby="", $single_row=false,$groupby="",$offset=-1,$limit=10) {
		if($fields != "") {
			$this->db->select($fields);
		}

		if($groupby != "") {
			$this->db->group_by($groupby); 
		}
		 
		if($orderby != "") {
			$this->db->order_by($orderby); 
		}

		if($offset>-1) {
			$this->db->limit($limit,$offset);
		}
		
		if($condition != "") {
			$rs = $this->db->get_where($table,$condition);
		} else {
			$rs = $this->db->get($table);
		}
		
		if($single_row) {  
			return $rs->row_array();
		}
		return $rs->result_array();
	}

	public function getFieldValue($table, $fields="", $condition="") {
		if($fields != "") {
			$this->db->select($fields);
		}

		if($condition != "") {
			$rs = $this->db->get_where($table,$condition);
		} else {
			$rs = $this->db->get_where($table);
		}
		$result = $rs->row_array();
		return $result[$fields];
	}

	public function addEditRecords($table_name, $data_array, $where='') {
		if($table_name && is_array($data_array)) {
			$columns = $this->getTableFields($table_name);
			foreach($columns as $coloumn_data)
				$column_name[]=$coloumn_data['Field'];
					  
			foreach($data_array as $key=>$val) {
				if(in_array(trim($key),$column_name)) {
					$data[$key] = $val;
				}
			 }

			if($where == "") {	
				$query = $this->db->insert_string($table_name, $data);
				$this->db->query($query);
				return  $this->db->insert_id();
			} else {
				$query = $this->db->update_string($table_name, $data, $where);
				$this->db->query($query);
				return  $this->db->affected_rows();
			}
		}			
	}

	public function getNumRecords($table, $fields="", $condition="") {
		if($fields != "") {
			$this->db->select($fields);
		}
		if($condition != "") {
			$rs = $this->db->get_where($table,$condition);
		} else {
			$rs = $this->db->get($table);
		}		
		return $rs->num_rows();
	}
	

	// this function is used to get all the fields of a table.
	public function getTableFields($table_name) {
		$query = "SHOW COLUMNS FROM $table_name";
		$rs = $this->db->query($query);
		return $rs->result_array();
	}

	public function getUniqueUid() {
		$query = "SELECT uuid() as uuid";
		$rs = $this->db->query($query);
		$result = $rs->row_array();
		return $result['uuid'];
	}

	public function check_user_login() {
		if($this->session->userdata('uuid')) {
			return true;
		} else {
			redirect('');
		}
	}

	public function getChatId($user_1_uuid, $user_2_uuid)
	{
        $sql="SELECT chat_uuid FROM chat_record WHERE (user_1_uuid = '".$user_1_uuid."' AND user_2_uuid = '".$user_2_uuid."') OR (user_1_uuid = '".$user_2_uuid."' AND user_2_uuid = '".$user_1_uuid."') LIMIT 1";
        $query=$this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else return false;
	}




}

	