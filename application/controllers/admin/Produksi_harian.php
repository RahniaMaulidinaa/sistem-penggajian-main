<?php
class Produksi_Harian extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('hak_akses') != '1') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda Belum Login!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('login');
        }
    }

    public function index() {
        $data['title'] = "Data Produksi Harian";
        $data['produksi'] = $this->db->get('produksi_harian')->result();
        $data['pegawai'] = $this->db->get('data_pegawai')->result_array();
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/produksi_harian', $data);
        $this->load->view('template_admin/footer');
    }

    public function tambah() {
        $id_pegawai = $this->input->post('id_pegawai');
        $tanggal = $this->input->post('tanggal');
        $jumlah_unit = $this->input->post('jumlah_unit');

        // Validasi input
        if (empty($id_pegawai) || empty($tanggal) || $jumlah_unit <= 0) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Data tidak valid!</strong> Pastikan semua field terisi dengan benar.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('admin/produksi_harian');
        }

        $data = array(
            'id_pegawai' => $id_pegawai, // Harus sesuai dengan nik dari data_pegawai
            'tanggal' => $tanggal,
            'jumlah_unit' => $jumlah_unit
        );

        $this->db->insert('produksi_harian', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Data berhasil ditambahkan!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
        }
        redirect('admin/produksi_harian');
    }

    public function edit($id) {
        $data['title'] = "Edit Data Produksi Harian";
        $data['produksi'] = $this->db->get_where('produksi_harian', ['id_produksi' => $id])->row();
        if (!$data['produksi']) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Data tidak ditemukan!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('admin/produksi_harian');
        }
        $data['pegawai'] = $this->db->get('data_pegawai')->result_array();
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/edit_produksi_harian', $data);
        $this->load->view('template_admin/footer');
    }

    public function update() {
        $id = $this->input->post('id_produksi');
        $id_pegawai = $this->input->post('id_pegawai');
        $tanggal = $this->input->post('tanggal');
        $jumlah_unit = $this->input->post('jumlah_unit');

        // Validasi input
        if (empty($id_pegawai) || empty($tanggal) || $jumlah_unit <= 0) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Data tidak valid!</strong> Pastikan semua field terisi dengan benar.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('admin/produksi_harian');
        }

        $data = array(
            'id_pegawai' => $id_pegawai,
            'tanggal' => $tanggal,
            'jumlah_unit' => $jumlah_unit
        );

        $this->db->where('id_produksi', $id);
        $this->db->update('produksi_harian', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Data berhasil diupdate!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
        }
        redirect('admin/produksi_harian');
    }

    public function hapus($id) {
        $this->db->delete('produksi_harian', ['id_produksi' => $id]);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Data berhasil dihapus!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
        }
        redirect('admin/produksi_harian');
    }
}