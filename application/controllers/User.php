<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if($this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    // ======================
    // LIST USER
    // ======================
    public function index()
    {
        $data['user'] = $this->db->get('users')->result();
        template('admin/user/index', $data);
    }

    // ======================
    // TAMBAH USER
    // ======================
    public function tambah()
    {
        if($_POST){

            $this->db->insert('users', [
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role' => $this->input->post('role')
            ]);

            redirect('user');
        }

        template('admin/user/tambah');
    }

    // ======================
    // EDIT USER
    // ======================
    public function edit($id)
    {
        if($_POST){

            $data = [
                'username' => $this->input->post('username'),
                'role' => $this->input->post('role')
            ];

            // kalau password diisi
            if($this->input->post('password')){
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }

            $this->db->where('id',$id)->update('users',$data);

            redirect('user');
        }

        $data['u'] = $this->db->get_where('users',['id'=>$id])->row();

        template('admin/user/edit', $data);
    }

    // ======================
    // HAPUS USER
    // ======================
    public function hapus($id)
    {
        $this->db->delete('users',['id'=>$id]);
        if($this->session->userdata('id') == $id){
    die('Tidak bisa hapus akun sendiri');
}
        redirect('user');
    }
    
}