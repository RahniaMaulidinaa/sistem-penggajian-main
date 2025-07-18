<?php
class Dashboard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('hak_akses') != '3'){
            $this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
               
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('login');
        }
    }

    
public function index()
{
    $data['title'] = "Dashboard HRD";

    $data['pegawai'] = $this->db->count_all('data_pegawai');
    $data['hrd'] = $this->db->where('jabatan', 'HRD')->count_all_results('data_pegawai');
    $data['jabatan'] = $this->db->count_all('data_jabatan');
    $data['kehadiran'] = $this->db->count_all('data_kehadiran');

    $data['jml_laki'] = $this->db->where('jenis_kelamin', 'Laki-laki')->count_all_results('data_pegawai');
    $data['jml_perempuan'] = $this->db->where('jenis_kelamin', 'Perempuan')->count_all_results('data_pegawai');

    $data['pegawai_tetap'] = $this->db->where('status', 'pegawai Tetap')->count_all_results('data_pegawai');
    $data['pegawai_tidak_tetap'] = $this->db->where('status', 'pegawai Tidak Tetap')->count_all_results('data_pegawai');

    $this->load->view('template_hrd/header', $data);
    $this->load->view('template_hrd/sidebar');
    $this->load->view('hrd/dashboard_hrd', $data);
    $this->load->view('template_hrd/footer');
}

    public function detail_hrd() {
        $data['title'] = "Detail HRD";
        $data['hrd'] = $this->db->query("SELECT * FROM data_pegawai WHERE jabatan = 'HRD'")->result();
        $this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/detail_hrd', $data);
        $this->load->view('template_hrd/footer');
    }

    public function detail_jabatan() {
        $data['title'] = "Detail Jabatan";
        $data['jabatan'] = $this->db->get('data_jabatan')->result();
        $this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/detail_jabatan', $data);
        $this->load->view('template_hrd/footer');
    }

    public function detail_kehadiran() {
        $data['title'] = "Detail Kehadiran";
        $data['kehadiran'] = $this->db->get('data_kehadiran')->result();
        $this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/detail_kehadiran', $data);
        $this->load->view('template_hrd/footer');
    }

    public function laporan_upah() {
        $data['title'] = "Laporan Upah Bulanan";
        $data['laporan'] = $this->db->get('data_upah')->result();
        $this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/upah/laporan_upah', $data);
        $this->load->view('template_hrd/footer');
    }

    public function ganti_password() {
        $data['title'] = "Ganti Password";
        $this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/Ganti_password', $data);
        $this->load->view('template_hrd/footer');
    }
}
?>