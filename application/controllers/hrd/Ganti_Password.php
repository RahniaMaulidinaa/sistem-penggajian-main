<?php
class Ganti_Password extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('hak_akses') != '3') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
               
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('login');
        }
    }

    public function index() {
        $data['title'] = "Form Ganti Password";

        $this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/ganti_password', $data);
        $this->load->view('template_hrd/footer');
    }

    public function ganti_password_aksi() {
        $passBaru = $this->input->post('passBaru');
        $ulangPass = $this->input->post('ulangPass');

        $this->form_validation->set_rules('passBaru', 'password baru', 'required|matches[ulangPass]');
        $this->form_validation->set_rules('ulangPass', 'ulangi password baru', 'required');

        if ($this->form_validation->run() != FALSE) {
            $data = array('password' => md5($passBaru));
            $id = array('id_pegawai' => $this->session->userdata('id_pegawai'));
            $this->ModelPenggajian->update_data('data_pegawai', $data, $id);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Password berhasil diganti!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('login');
        } else {
            $data['title'] = "Form Ganti Password";

            $this->load->view('template_hrd/header', $data);
            $this->load->view('template_hrd/sidebar');
            $this->load->view('hrd/ganti_password', $data);
            $this->load->view('template_hrd/footer');
        }
    }
}
?>