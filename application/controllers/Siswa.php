<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    // ======================
    // LIST DATA
    // ======================
    public function index()
    {
        $this->db->select('siswa.*, kelas.nama_kelas, tahun_ajaran.tahun');
        $this->db->join('kelas','kelas.id=siswa.id_kelas','left');
        $this->db->join('tahun_ajaran','tahun_ajaran.id=siswa.id_tahun','left');

        $data['siswa'] = $this->db->get('siswa')->result();

        template('admin/siswa/index', $data);
    }

    // ======================
    // TAMBAH
    // ======================
    public function tambah()
{
    if($_POST){

        $nisn = $this->input->post('nisn');

        // 🔥 CEK DULU SEBELUM INSERT
        $cek = $this->db->get_where('siswa', ['nisn'=>$nisn])->row();
        if($cek){
            $this->session->set_flashdata('error','NISN sudah terdaftar');
            redirect('siswa/tambah');
        }
        $config['upload_path'] = './uploads/foto/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;

        $this->load->library('upload', $config);

        $foto = 'default.png';

        if($this->upload->do_upload('foto')){
            $foto = $this->upload->data('file_name');
        }
        // ======================
        // ✅ INSERT SISWA
        // ======================
       $this->db->insert('siswa', [
    'nisn' => $nisn,
    'nis' => $this->input->post('nis'),
    'nama' => $this->input->post('nama'),
    'jenis_kelamin' => $this->input->post('jk'),
    'tempat_lahir' => $this->input->post('tempat_lahir'),
    'tanggal_lahir' => $this->input->post('tanggal_lahir'),
    'nama_ortu' => $this->input->post('nama_ortu'),
    'jurusan' => $this->input->post('jurusan'),
    'rata_nilai' => $this->input->post('rata_nilai'),
    'id_kelas' => $this->input->post('kelas'),
    'id_tahun' => $this->input->post('tahun'),
    'foto' => $foto // 🔥 INI YANG KURANG
]);

        // 🔥 AMBIL ID SETELAH INSERT
        $id_siswa = $this->db->insert_id();

        // 🧪 DEBUG (boleh test dulu)
        // echo $id_siswa; die;

        // ======================
        // ✅ INSERT USER
        // ======================
        $this->db->insert('users', [
            'username' => $nisn,
            'password' => password_hash($nisn, PASSWORD_DEFAULT),
            'role' => 'siswa',
            'nisn' => $nisn
        ]);

        // ======================
        // 🔥 AMBIL TANGGAL
        // ======================
        $tgl = $this->db
            ->get_where('pengaturan', ['nama_pengaturan' => 'tanggal_pengumuman'])
            ->row();

        $tanggal_kelulusan = $tgl 
            ? date('Y-m-d', strtotime($tgl->value)) 
            : date('Y-m-d');

        // ======================
        // 🔥 INSERT KELULUSAN
        // ======================
        $this->db->insert('kelulusan', [
            'id_siswa' => $id_siswa,
            'status' => 'lulus',
            'nomor_skl' => null,
            'tanggal_lulus' => $tanggal_kelulusan,
            'keterangan' => null
        ]);

        redirect('siswa');
    }

    $data['kelas'] = $this->db->get('kelas')->result();
    $data['tahun'] = $this->db->get('tahun_ajaran')->result();

    template('admin/siswa/tambah', $data);
}
    // ======================
    // EDIT
    // ======================
    public function edit($id)
{
    if($_POST){

        // ambil data lama
        $siswa = $this->db->get_where('siswa',['id'=>$id])->row();

        // config upload
        $config['upload_path'] = './uploads/foto/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;

        $this->load->library('upload', $config);

        $foto = $siswa->foto; // default = foto lama

        // kalau upload baru
        if($this->upload->do_upload('foto')){
            
            // hapus foto lama (kecuali default)
            if($siswa->foto != 'default.png' && file_exists('./uploads/foto/'.$siswa->foto)){
                unlink('./uploads/foto/'.$siswa->foto);
            }

            $foto = $this->upload->data('file_name');
        }

        // update data
        $this->db->where('id',$id)->update('siswa', [
            'nis' => $this->input->post('nis'),
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jk'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'nama_ortu' => $this->input->post('nama_ortu'),
            'jurusan' => $this->input->post('jurusan'),
            'rata_nilai' => $this->input->post('rata_nilai'),
            'id_kelas' => $this->input->post('kelas'),
            'id_tahun' => $this->input->post('tahun'),
            'foto' => $foto // 🔥 WAJIB
        ]);

        redirect('siswa');
    }

    $data['siswa'] = $this->db->get_where('siswa',['id'=>$id])->row();
    $data['kelas'] = $this->db->get('kelas')->result();
    $data['tahun'] = $this->db->get('tahun_ajaran')->result();

    template('admin/siswa/edit', $data);
}

    // ======================
    // HAPUS
    // ======================
    public function hapus($id)
{
    $siswa = $this->db->get_where('siswa',['id'=>$id])->row();

    // hapus dari kelulusan dulu
    $this->db->delete('kelulusan', ['id_siswa' => $id]);

    // hapus user
    $this->db->delete('users',['nisn'=>$siswa->nisn]);

    // hapus siswa
    $this->db->delete('siswa',['id'=>$id]);

    redirect('siswa');
}
    public function upload_foto_massal()
{
    if(isset($_FILES['zip']['name'])){

        $zip_name = $_FILES['zip']['tmp_name'];

        $zip = new ZipArchive;
        if($zip->open($zip_name) === TRUE){

            $extract_path = FCPATH.'uploads/tmp/';
            if(!is_dir($extract_path)){
                mkdir($extract_path, 0777, true);
            }

            $zip->extractTo($extract_path);
            $zip->close();

            $files = scandir($extract_path);

            $berhasil = 0;
            $gagal = 0;

            foreach($files as $file){

                if($file == '.' || $file == '..') continue;

                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $nisn = pathinfo($file, PATHINFO_FILENAME);

                // cek siswa
                $siswa = $this->db->get_where('siswa', ['nisn'=>$nisn])->row();

                if($siswa){

                    $new_name = $nisn.'.'.$ext;
                    $source = $extract_path.$file;
                    $dest = FCPATH.'uploads/foto/'.$new_name;

                    rename($source, $dest);

                    // update DB
                    $this->db->where('nisn', $nisn)->update('siswa', [
                        'foto' => $new_name
                    ]);

                    $berhasil++;
                } else {
                    $gagal++;
                }
            }

            // bersihkan folder tmp
            array_map('unlink', glob($extract_path.'*.*'));

            $this->session->set_flashdata('success',
                "Upload selesai: $berhasil berhasil, $gagal gagal"
            );

        } else {
            $this->session->set_flashdata('error','Gagal membuka ZIP');
        }

        redirect('siswa');
    }
}
public function view($id)
{
    $data['siswa'] = $this->db
        ->select('siswa.*, kelas.nama_kelas, tahun_ajaran.tahun')
        ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
        ->join('tahun_ajaran', 'tahun_ajaran.id = siswa.id_tahun', 'left')
        ->where('siswa.id', $id)
        ->get('siswa')
        ->row();

    template('admin/siswa/view', $data);
}
}