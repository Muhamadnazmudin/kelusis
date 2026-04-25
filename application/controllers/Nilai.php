<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if(!$this->session->userdata('role') || $this->session->userdata('role') != 'admin'){
            redirect('login');
        }
    }

    public function index()
{
    $this->db->select('
        siswa.id,
        siswa.nama,
        kelas.nama_kelas,
        siswa.jurusan,
        mata_pelajaran.id as mapel_id,
        nilai.nilai
    ');
    $this->db->from('nilai');
    $this->db->join('siswa', 'siswa.id = nilai.siswa_id');
    $this->db->join('kelas', 'kelas.id = siswa.id_kelas', 'left');
    $this->db->join('mata_pelajaran', 'mata_pelajaran.id = nilai.mapel_id');
    $this->db->order_by('siswa.nama', 'ASC');

    // ✅ HANYA SEKALI GET
    $query = $this->db->get()->result();

    // ======================
    // 🔥 PIVOT
    // ======================
    $data_nilai = [];

    foreach($query as $row){
        if(!isset($data_nilai[$row->id])){
            $data_nilai[$row->id] = [
                'nama' => $row->nama,
                'kelas' => $row->nama_kelas,
                'jurusan' => $row->jurusan
            ];
        }

        $data_nilai[$row->id][$row->mapel_id] = $row->nilai;
    }

    $data['title'] = 'Nilai';
    $data['nilai'] = $data_nilai;
    $data['mapel'] = $this->db->get('mata_pelajaran')->result();
    foreach($data_nilai as $id => $d){

    $total = 0;
    $jumlah = 0;

    foreach($mapel = $this->db->get('mata_pelajaran')->result() as $m){
        if(isset($d[$m->id])){
            $total += $d[$m->id];
            $jumlah++;
        }
    }

    $data_nilai[$id]['rata2'] = $jumlah ? round($total / $jumlah, 2) : 0;
}

    template('admin/nilai/index', $data);
}
    public function tambah()
{
    if($_POST){

        $siswa_id = $this->input->post('siswa');

        $mapel = $this->db->get('mata_pelajaran')->result();

        foreach($mapel as $m){

            $nilai = $this->input->post('nilai_'.$m->id);

            // cek kalau sudah ada → update
            $cek = $this->db->get_where('nilai', [
                'siswa_id' => $siswa_id,
                'mapel_id' => $m->id
            ])->row();

            if($cek){
                $this->db->where('id', $cek->id)->update('nilai', [
                    'nilai' => $nilai
                ]);
            } else {
                $this->db->insert('nilai', [
                    'siswa_id' => $siswa_id,
                    'mapel_id' => $m->id,
                    'nilai' => $nilai
                ]);
            }
        }

        redirect('nilai');
    }

    $data['siswa'] = $this->db->get('siswa')->result();
    $data['mapel'] = $this->db->get('mata_pelajaran')->result();

    template('admin/nilai/tambah', $data);
}
public function edit($id)
{
    $data['siswa'] = $this->db->get_where('siswa', ['id'=>$id])->row();
    $data['mapel'] = $this->db->get('mata_pelajaran')->result();
    $data['nilai'] = $this->db->get_where('nilai', ['siswa_id'=>$id])->result();

    template('admin/nilai/edit', $data);
}
public function hapus($id)
{
    $this->db->delete('nilai', ['siswa_id'=>$id]);
    redirect('nilai');
}
public function import()
{
    if(isset($_FILES['file']['name'])){

        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");

        fgetcsv($handle); // skip header

        while(($row = fgetcsv($handle, 1000, ",")) !== FALSE){

            $nisn = $row[0];
            $mapel_id = $row[1];
            $nilai = $row[2];

            $siswa = $this->db->get_where('siswa',['nisn'=>$nisn])->row();

            if($siswa){

                $cek = $this->db->get_where('nilai', [
                    'siswa_id'=>$siswa->id,
                    'mapel_id'=>$mapel_id
                ])->row();

                if($cek){
                    $this->db->where('id',$cek->id)->update('nilai',[
                        'nilai'=>$nilai
                    ]);
                } else {
                    $this->db->insert('nilai',[
                        'siswa_id'=>$siswa->id,
                        'mapel_id'=>$mapel_id,
                        'nilai'=>$nilai
                    ]);
                }
            }
        }

        fclose($handle);

        redirect('nilai');
    }

    template('admin/nilai/import');
}
}