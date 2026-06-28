<?php 
class Employees extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->library(array("form_validation", "session", "pagination"));
        $this->load->helper(array("form","url"));
        $this->load->model("employee_model");
    }
    //adding Employee
    public function addEmployee(){
        $this->form_validation->set_rules("name", "Name", "trim|required");
        $this->form_validation->set_rules("dob", "DOB", "trim|required");
		$this->form_validation->set_rules("email", "Email", "trim|required|valid_email");
        $this->form_validation->set_rules("skills", "Skills", "trim|required");
        $this->form_validation->set_rules("mobile", "Mobile", "trim|required");
        $this->form_validation->set_rules("gender", "Gender", "trim|required");
		$this->form_validation->set_rules("address", "Address", "trim|required");
        $this->form_validation->set_rules("status", "Status", "trim|required");
		if($this->form_validation->run() === TRUE)
		{
            //image work
            if(!is_dir("images"))
            {
                mkdir("images", 0755, true);
            }
            $target_dir= "images/";
			$temp = explode(".", $_FILES["inpFile"]["name"]);
			$newfilename = rand(0, 10000) . '_pic' . '.' . end($temp);
			$target_file = $target_dir . $newfilename;
			move_uploaded_file($_FILES["inpFile"]["tmp_name"], $target_file);
            //image work end.
			$created_at=date("Y-m-d");
			$data = array(
                'name'              =>  $this->input->post("name", TRUE),
                'dob'			    =>	$this->input->post("dob", TRUE),
                'email'			    =>	$this->input->post("email", TRUE),
                'skills'			=>	$this->input->post("skills", TRUE),
                'mobile'		    =>	$this->input->post("mobile", TRUE),
				'gender'		    =>	$this->input->post("gender", TRUE),
                'address'		    =>	$this->input->post("address", TRUE),
                'status'		    =>	$this->input->post("status", TRUE),
                'image'		        =>	$target_file,
				'date_of_join'	    =>	$created_at
			);
			$status=$this->employee_model->add_emp($data);
			if($status === TRUE)
			{
				$this->session->set_tempdata("success","Employee Added Successfully.", 3);
                redirect(current_url());
			}else 
			{
				$this->session->set_tempdata("error","Unable to Add.", 3);
                redirect(current_url());
			}
		}else 
		{
			$this->load->view("add_employee");
		}
    }
    //Manage Employees
    public function manage(){
        //search
		$name=$this->input->post("search_text", TRUE);
		$records['data']=$this->employee_model->searched_record($name);
		if(count((array)$records['data'])>0)
		{
			$this->load->view('manage_employees', $records);
		}else {
			//pagination
			$no_row=$this->employee_model->totalrow();
			$config = array();
			$config["base_url"] 	    = base_url('employees/manage');
			$config["total_rows"] 	    = $no_row;
			$config["per_page"] 	    = 2;
			//pagination style 
			$config['full_tag_open']    = '<ul class="pagination">';
			$config['full_tag_close']   = '</ul>';
			$config['first_tag_open']   = "<li class='page-item'>";
			$config['first_tag_close']  = "</li>"; 
			$config['prev_tag_open']    = "<li class='page-item'>"; 
			$config['prev_tag_close']   = "</li>";
			$config['next_tag_open']    = "<li class='page-item'>";
			$config['next_tag_close']   = "</li>"; 
			$config['last_tag_open']    = "<li class='page-item'>"; 
			$config['last_tag_close']   = "</li>";
			$config['cur_tag_open']     = "<li class='page-item'><a class='page-link active' href='#'>"; 
			$config['cur_tag_close']    = "</a></li>";
			$config['num_tag_open']     = "<li class='page-item'>";
			$config['num_tag_close']    = "</li>";
			$config['attributes'] = array('class' => 'page-link');
			//initializing configs
			$this->pagination->initialize($config);
			// End pagination
			$records['data']=$this->employee_model->get_records($config['per_page'], $this->uri->segment(3));
			$this->load->view('manage_employees', $records);
		}
    }
    //bulk (Active, In-Active, Delete)
    public function bulk()
    {
        $ids=$this->input->post("ids[]");
        $i=array();
        foreach($ids as $id)
        {
            $i[]=$id;
        }
        //print_r($i);
        //activate;
        if($this->input->post("active_all"))
        {
            $status=$this->employee_model->activate($i);
            if($status === TRUE)
            {
                $this->session->set_tempdata('activated', 'Activated Successfully!', 3);
                redirect(base_url('employees/manage'));
            }else 
            {
                $this->session->set_tempdata('alreadyactivate', 'Already Activated!', 3);
                redirect(base_url('employees/manage'));
            }
        }
        //in-activate;
        if($this->input->post("in_active_all"))
        {
            $status=$this->employee_model->in_activate($i);
            if($status === TRUE)
            {
                $this->session->set_tempdata('in_activated', 'In-Activated Successfully!', 3);
                redirect(base_url('employees/manage'));
            }else 
            {
                $this->session->set_tempdata('alreadyinactivate', 'Already In-Activated!', 3);
                redirect(base_url('employees/manage'));
            }
        }
        //delete records
        if($this->input->post("delete_all"))
        {
            $status=$this->employee_model->delete_records($i);
            if($status === TRUE)
            {
                $this->session->set_tempdata('deleted', 'Deleted Successfully!', 3);
                redirect(base_url('employees/manage'));
            }else 
            {
                $this->session->set_tempdata('failed', 'Failed! Try again.', 3);
                redirect(base_url('employees/manage'));
            }
        }
    }
    //edit Employees
    public function edit($emp_id){
        $record['data']=$this->employee_model->get_record($emp_id);
		$this->form_validation->set_rules("name", "Name", "trim|required");
        $this->form_validation->set_rules("dob", "DOB", "trim|required");
		$this->form_validation->set_rules("email", "Email", "trim|required|valid_email");
        $this->form_validation->set_rules("skills", "Skills", "trim|required");
        $this->form_validation->set_rules("mobile", "Mobile", "trim|required");
        $this->form_validation->set_rules("gender", "Gender", "trim|required");
		$this->form_validation->set_rules("address", "Address", "trim|required");
        $this->form_validation->set_rules("status", "Status", "trim|required");
		if($this->form_validation->run() === TRUE)
		{
            // upload
			$target_file=$this->input->post("old_img");
			$temp = explode(".", $_FILES["inpFile"]["name"]);
			$img=$_FILES['inpFile']['name'];
			if($img)
			{
				if (file_exists($target_file) && is_file($target_file))
				{
					unlink($target_file);
				}
				$target_dir = "images/";
				$newfilename = rand(0, 10000) . '_pic' . '.' . end($temp);
				$target_file = $target_dir . $newfilename;
				// print_r($target_file);exit();
				move_uploaded_file($_FILES["inpFile"]["tmp_name"], $target_file);
			}else 
			{
				$target_file=$this->input->post("old_img");
			}
			$data = array(
                'name'              =>  $this->input->post("name", TRUE),
                'dob'			    =>	$this->input->post("dob", TRUE),
                'email'			    =>	$this->input->post("email", TRUE),
                'skills'			=>	$this->input->post("skills", TRUE),
                'mobile'		    =>	$this->input->post("mobile", TRUE),
				'gender'		    =>	$this->input->post("gender", TRUE),
                'address'		    =>	$this->input->post("address", TRUE),
                'status'		    =>	$this->input->post("status", TRUE),
                'image'		        =>	$target_file
			);
			$status=$this->employee_model->edit_emp($data, $emp_id);
			if($status === TRUE)
			{
				$this->session->set_tempdata("updated","Employee Information Updated Successfully.", 3);
                redirect(base_url('employees/manage'));
			}else 
			{
				$this->session->set_tempdata("error","Unable to Edit.", 3);
                redirect(current_url());
			}
		}else 
		{
			$this->load->view('edit_emp', $record);
		}
    }
}
?>