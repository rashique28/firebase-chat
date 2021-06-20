<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class User extends CI_Controller {

	private $firebase;

	public function __construct() {
		parent::__construct();
		$this->load->model('Common_model');
		// $this->load->library('PHPExcel');
        $this->load->helper('url');
		

		$serviceAccount = ServiceAccount::fromJsonFile('my-chat-project-78b7d-firebase-adminsdk-3bskb-bb0d890d70.json');

		$this->firebase = (new Factory)
		     ->withServiceAccount($serviceAccount)
		     ->create();
	}

	public function index()
	{
		if (isset($_SESSION['user_uuid'])) {
			redirect("chat");
		}

		$this->load->view('index');
	}

	private function getUuid(){
		return $this->Common_model->getUniqueUid();
	}

	private function isExists($table, $key, $value){
		try{
			$exist = $this->Common_model->getRecords($table, 'uuid',array($key=>$value),"", true);			
			if (!empty($exist)) {
				return array('status'=> 303, 'message'=> $value.' already exists');
			}else{
				return array('status'=> 200, 'message'=> $value);
			}
		}catch(Exception $e){
			return array('status'=>405, 'message'=>$e->getMessage());
		}
		
	}

	public function createAccount(){

	    $fullname  =  $this->input->post('fullname');
	    $username  =  $this->input->post('username');
	    $password  =  $this->input->post('password');

		if (empty($fullname) || empty($username) || empty($password)) {
			echo json_encode(array('status'=> 303, 'message'=> 'Empty Fields')); exit;
		}else{
			
			$usernameResp = $this->isExists('users', 'username', $username);
			if ($usernameResp['status'] != 200) {
				echo json_encode($usernameResp); exit;
			}

			$password = password_hash($password, PASSWORD_BCRYPT, ['cost'=> 8]);
	
			$uuid = $this->getUuid();
			
			$insertRecord = array(
              	'uuid' => $uuid,
              	'fullname' => $fullname,
				'username' => $username,
				'password' => $password
			);
			if($this->Common_model->addEditRecords('users', $insertRecord)) {
				echo json_encode(array('status'=> 200, 'message'=> 'Account creatded Successfully..!')); exit;
			} else {
				echo json_encode(array('status'=> 303, 'message'=> 'Some error occured! Please try again.')); exit;
			}
		}
		
	}

	public function loginUser(){

	    $username  =  $this->input->post('username');
	    $password  =  $this->input->post('password');

		$row = $this->Common_model->getRecords('users', '*',array('username'=>$username),"", true);	

		if ($row) {
			if (password_verify($password, $row['password'])) {

                $login_session=array( 	
					'user_uuid'=>$row['uuid'],
					'username'=> $row['username'],
					'fullname'=> $row['fullname'],
				);
				$this->session->set_userdata($login_session);

				$ar = [];
				$ar['message'] =  'User Logged in Successfully';
				$ar['user_uuid'] = $row['uuid'];

				$additionalClaims = ['username'=> $row['username'], 'email'=> $row['email']];
				$customToken = $this->firebase->getAuth()->createCustomToken($ar['user_uuid'], $additionalClaims);

				$ar['token'] = (string)$customToken;
				
				echo json_encode(array('status'=> 200, 'message'=> $ar)); exit;

			}else{
				echo json_encode(array('status'=> 303, 'message'=> 'Password does not match')); exit;
			}
		}else{
			echo json_encode(array('status'=> 303, 'message'=> $username.' does not exists')); exit;
		}

	}

	public function createChatRecord(){
        $user_1_uuid  =  $this->input->post('user_1');
        $user_2_uuid  =  $this->input->post('user_2');

		$chatUuid = $this->Common_model->getChatId($user_1_uuid, $user_2_uuid);

		$ar = [];

		if (empty($user_1_uuid) || empty($user_2_uuid)) {
			echo json_encode(array('status' => 303, 'message'=> 'Invalid details')); exit();
		}

		$ar['user_1_uuid'] = $user_1_uuid;
		$ar['user_2_uuid'] = $user_2_uuid;

		if ($chatUuid) {
			$ar['chat_uuid'] = $chatUuid['chat_uuid'];
			echo json_encode(array('status'=>200, 'message'=> $ar)); exit();
		}else{
			$chat_uuid = $this->getUuid();
			$ar['chat_uuid'] = $chat_uuid;

			$insertRecord = array(
              	'chat_uuid' => $chat_uuid,
              	'user_1_uuid' => $user_1_uuid,
				'user_2_uuid' => $user_2_uuid
			);
			$this->Common_model->addEditRecords('chat_record', $insertRecord);
			echo json_encode(array('status'=> 200, 'message'=> $ar)); exit();
		}
	}

	public function getUsers(){
		$record = $this->Common_model->getRecords('users', 'uuid,fullname,username');	
		$ar = [];
		foreach ($record as $key => $row) {
			$ar[] = $row;
		}
		echo json_encode(array('status'=>200, 'message'=>['users'=>$ar])); exit;

		/*while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$ar[] = $row;
		}*/		
	}

	public function logout(){
		$username = $this->session->userdata('username');
		if (isset($username)) {
			
			$array_items = array('username' => '', 'user_uuid' => '');
			$this->session->unset_userdata($array_items);

			session_destroy();
		
			echo json_encode(array('status'=>200, 'message'=>'User Logout Successfully')); exit;
		}
		echo json_encode(array('status'=>303, 'message'=>'Logout Fail')); exit;

	}

	public function chat()
	{
		if (!isset($_SESSION['user_uuid'])) {
			redirect("/");
		}

		$this->load->view('chat');
	}


} // class end