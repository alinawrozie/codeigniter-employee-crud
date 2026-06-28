<?php 
class Employee_model extends CI_Model{
    //add employee
    public function add_emp($data){
        $this->db->insert("employees_tbl", $data);
		if($this->db->affected_rows()===1)
		{
			return TRUE;
		}else 
		{
			return FALSE;
		}
    }
    //search employee
    public function searched_record($name)
	{
        if($name != '')
		{
            $this->db->select("*");
			$this->db->from("employees_tbl");
			$this->db->like("name", $name);
			$this->db->order_by('emp_id', 'DESC');
			$result=$this->db->get();
			if($result->num_rows()>0)
			{
				return $result->result();
			}else 
			{
				return FALSE;
			}
        }
	}
    //no. of rows 
	public function totalrow()
	{
		$result=$this->db->get("employees_tbl");
		return $result->num_rows();
	}
    //get records applied pagination
    public function get_records($limit, $offset)
	{
        $this->db->order_by('emp_id', 'DESC');
		$result = $this->db->get('employees_tbl', $limit, $offset);
        if($result->num_rows()>0)
		{
			return $result->result();
		}else 
		{
			return FALSE;
		}
	}
    //activate
	public function activate($i)
	{
		$this->db->set('status', 1);
		$this->db->where_in('emp_id', $i);
		$this->db->update('employees_tbl');
		if($this->db->affected_rows()>0)
		{
			return TRUE;
		}else 
		{
			return FALSE;
		}
	}
	//inactive
	public function in_activate($i)
	{
		$this->db->set('status', 0);
		$this->db->where_in('emp_id', $i);
		$this->db->update('employees_tbl');
		if($this->db->affected_rows()>0)
		{
			return TRUE;
		}else 
		{
			return FALSE;
		}
	}
	//delete
	public function delete_records($i)
	{
        //first delete image from root directory
		$this->db->where_in('emp_id', $i);
		$this->db->select('image');
		$result=$this->db->get('employees_tbl');
		foreach($result->result() as $row)
		{
			if (file_exists($row->image) && is_file($row->image)) {
				unlink($row->image);
			}
		}
		//delete record from db
		$this->db->where_in('emp_id', $i);
		$this->db->delete('employees_tbl');
		if($this->db->affected_rows()>0)
		{
			return TRUE;
		}else 
		{
			return FALSE;
		}
	}
    //get specific record for edit
	public function get_record($emp_id)
	{
		$this->db->where('emp_id', $emp_id);
		$result=$this->db->get('employees_tbl');
		if($result->num_rows()>0)
		{
			return $result->row();
		}else 
		{
			return false;
		}
	}
    //updating Employee 
	public function edit_emp($data, $emp_id)
	{
		$this->db->where('emp_id', $emp_id);
		$this->db->update('employees_tbl', $data);
		if($this->db->affected_rows()>0)
		{
			return TRUE;
		}else 
		{
			return FALSE;
		}
	}
}