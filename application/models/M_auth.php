<?php
class M_auth extends CI_Model {

    public function login($username)
    {
        return $this->db->get_where('users', [
            'username' => $username
        ])->row();
    }

}