<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class Authentication extends REST_Controller {

    public function __construct() 
    { 
         
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Auth');
         header('Access-Control-Allow-Origin: *');

        header('Access-Control-Allow-Credentials: true');

        header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT,DELETE');

        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, TOKEN , Authoriz');

        header('Content-Type: application/json; charset=UTF8');
        header('Content-Type: text/html; charset=UTF-8');

        date_default_timezone_set('Asia/Calcutta');

        $this->output->set_content_type('application/json');


        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
        header('HTTP/1.1 200 OK');
        die();
        }
        
    }

    public function genrate_token($tokenData){
        $str=implode(',', $tokenData);
        $token= base64_encode($str);
        return $token;
    }

    public function registration_post() 
    {      
        $post = file_get_contents('php://input');
        $val = json_decode($post);

        $username         = $val->username;
        $email            = $val->email;
        $password         = $val->password;
        $phone_number     = $val->mobile;
      

        if(!empty($username) && !empty($email) && !empty($password)  && !empty($phone_number))
        {
            $whereEmail  = array('useremail' => $email);
            $checkEmail = $this->Auth->singleRowdata($whereEmail,'user');
            if ($checkEmail) {
                $this->response([
                        'status' => FALSE,
                        'message' => 'Email already exists.',
                ], REST_Controller::HTTP_OK);
                
            }else{ 
                $userData = array(
                    'username' => $username,
                    'useremail' => $email,
                    'password' => md5($password),
                    'usermobile'=>$phone_number
                ); 

                $result = $this->Auth->insert($userData,'user'); 

                if ($result) {
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Success',
                    ], REST_Controller::HTTP_OK);
                }else{
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Failed to create user',
                    ], REST_Controller::HTTP_OK);
                }
            }
        }
        else 
        {
            $this->response([
            'status' => FALSE,
            'message' => 'Provide proper information ',
            ], REST_Controller::HTTP_OK);
        }       
    }


    public function login_post() 
    {  
        $post = file_get_contents('php://input');
        $val = json_decode($post);
        $email          = $val->email;
        $password       = $val->password;
        
        if(!empty($email) && !empty($password))
        {
            if(!empty($email))
            {
                $whereEmail = array('useremail' => $email);
            }
            
            $checkEmail = $this->Auth->singleRowdata($whereEmail,'user');
            if(!empty($checkEmail))
            {
                $whereData = array('useremail' => $email,'password' => md5($password));
                $login = $this->Auth->singleRowdata($whereData,'user');

                if ($login) {

                    $whereNewID = array('user_id' => $login->user_id);

                    $data   = $this->Auth->singleRowdata($whereNewID,'user');

                    if ($data) {
                        $tokenData = array();
                        $tokenData['usermobile'] = $data->usermobile; 
                        $tokenData['pwd']        = $data->password; 
                        $token      = $this->genrate_token($tokenData);

                        $this->response([
                                'status' => TRUE,
                                'token' => $token,
                                'data' => $data,
                                'message' => 'Login successfully',
                        ], REST_Controller::HTTP_OK);
                       
                    }else{
                        $this->response([
                            'status' => False,
                            'message' => 'Failed',
                        ], REST_Controller::HTTP_OK);
                    }                   
                }else{
                    $this->response([
                        'status' => False,
                        'message' => 'Incorrect credential',
                        
                    ], REST_Controller::HTTP_OK);
                }                

            }

            else
            {
                $this->response([
                    'status' => False,
                    'message' => 'Email incorrect',
                    
                ], REST_Controller::HTTP_OK);
            }
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => "Provide email and password.",
               
            ], REST_Controller::HTTP_OK);
        }
    }

}
