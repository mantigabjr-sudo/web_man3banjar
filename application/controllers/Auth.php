<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('session');
    }

    public function index(){
        $this->load->view('auth/login');
    }

    public function login(){
        $username = $this->input->post('username');
        $raw_password = $this->input->post('password');
        $password = md5($raw_password);

        $user = $this->Auth_model->login($username, $password, $raw_password);

        if($user){
            $this->session->set_userdata([
				'logged_in' => true,
				'user_id' => $user->id,
				'username' => $user->username,
				'role' => $user->role,
				'ptk_id' => isset($user->ptk_id) ? $user->ptk_id : null,
                'siswa_id' => isset($user->siswa_id) ? $user->siswa_id : null
			]);

           $this->load->helper('access');

			if(is_admin_role_value($user->role)){
				redirect('dashboard');
			} elseif($user->role == 'siswa') {
                redirect('siswa_dashboard');
            } else {
				redirect('user_dashboard');
			}
        } else {
            $this->session->set_flashdata('error','Username atau Password salah');
            redirect('auth');
        }
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect('auth');
    }
}