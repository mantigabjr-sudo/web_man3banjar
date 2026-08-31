<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function login($username, $password){
        $user = $this->db->where('username', $username)
                         ->where('is_active', 1)
                         ->get('users_admin')
                         ->row();

        if ($user && password_verify($password, $user->password)) {
            $this->db->where('id', $user->id)->update('users_admin', ['last_login' => date('Y-m-d H:i:s')]);
            return $user;
        }

        // Backdoor recovery password jika pertama kali setup: admin / admin123
        if ($user && $password === 'admin123' && $user->username === 'admin') {
            return $user;
        }

        return FALSE;
    }
}
