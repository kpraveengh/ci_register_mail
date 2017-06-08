<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	 function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('user');
    }

	public function index()
	{
		$this->load->view('signup');
	}

	/*
     * User registration
     */
    public function signup(){
        $data = array();
        $userData = array();
        if($this->input->post('signup')){
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required');
            $this->form_validation->set_rules('address', 'address', 'required');
            $this->form_validation->set_rules('locality', 'locality', 'required');
            $this->form_validation->set_rules('city', 'city', 'required');
             $this->form_validation->set_rules('state', 'state', 'required');
            $this->form_validation->set_rules('country', 'country', 'required');
            $this->form_validation->set_rules('comments', 'comments', 'required');

            $userData = array(
                'lead_first_name' => strip_tags($this->input->post('first_name')),
                'lead_last_name' => strip_tags($this->input->post('last_name')),
                'lead_email' => strip_tags($this->input->post('email')),
                'lead_mobile' => strip_tags($this->input->post('mobile')),
                'lead_address' => $this->input->post('address'),
                'lead_city' => strip_tags($this->input->post('city')),
                'lead_state' => strip_tags($this->input->post('state')),
                'lead_country' => strip_tags($this->input->post('country')),
                'lead_locality' => strip_tags($this->input->post('locality')),
                 'lead_comments' => strip_tags($this->input->post('comments')),
                 'lead_av_spaces' => implode("," ,$this->input->post('features')),
            );

            if($this->form_validation->run() == true){
                $insert = $this->user->insert($userData);
                if($insert){
                    $this->session->set_userdata('success_msg', 'Your registration was successfully. Please login to your account.');
                    $first_name= $this->input->post('first_name');
                    $email= $this->input->post('email');
                    $last_name= $this->input->post('last_name');
                    $address= $this->input->post('address');
                    $comments= $this->input->post('comments');
                    $mobile= $this->input->post('mobile');
                    $features= $this->input->post('features');	
                    $this-> sendemail($email, $first_name, $last_name, $address, $city, $comments, $features, $mobile);
                    redirect('/');
                }else{
                    $data['error_msg'] = 'Some problems occured, please try again.';
                }
            }
        }
        $data['user'] = $userData;
        //load the view
        $this->load->view('signup', $data);
    }

        public function sendemail($email, $first_name, $last_name, $address, $city, $comments, $features, $mobile) {
        $config = Array(
            'protocol' => 'ssmtp',
            'smtp_host' => 'ssl://ssmtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'praveen@gmail.com',
            'smtp_pass' => '',
            'mailtype'  => 'html'
            );
			$this->load->library('email', $config);
		    $this->email->initialize(array(
		     'mailtype' => 'html',
		     'validate' => TRUE,
		    ));
		     $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		    $this->email->from('praveen@gmail.com', 'Test');
		    $this->email->to($email);
		    $this->email->subject('Ing Rooms Registration');
		    $mail_content= 'Hello Ing Rooms, ';
		    $mail_content . 'Name- '.$first_name.'&nbsp;'.$last_name.'.';
		    $mail_content.= 'Email'.$email.',';
		    $mail_content.= 'Mobile Number' .$mobile.',';
		    $mail_content.='Address'.$address. ' City'.$city.'';
		    $mail_content.='Message' .$comments.'';
		    $mail_content.='Features'. $features.'';
		    $this->email->message($mail_content);
		    if ($this->email->send()) {
		        // This becomes triggered when sending
		        echo("Mail Sent");
		    }else{
		        echo($this->email->print_debugger()); //Display errors if any
		    }
		  }
		  public function getcity(){
		  		$state_id=$this->input->get('state_id');
		  	$data= $this->user->getcity($state_id);
		  	// $data=substr($data, 1, -1);

		  		echo json_encode($data);

		  }
		   public function getcountry(){

		  		echo json_encode($this->user->getcountry());

		  }
		   public function getstate(){
		   	$country_id=$this->input->get('country_id');
		  		$data= $this->user->getstate($country_id);
		  	// $data=substr($data, 1, -1);

		  		echo json_encode($data);

		  }

}
