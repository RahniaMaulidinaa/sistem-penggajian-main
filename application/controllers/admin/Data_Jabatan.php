<?php

class Data_Jabatan extends CI_Controller {

	public function __construct() {
		parent::__construct();

		if ($this->session->userdata('hak_akses') != '1') {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Anda Belum Login!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>');
			redirect('login');
		}
	}

	public function index() {
		$data['title'] = "Data Jabatan";
		$data['jabatan'] = $this->ModelPenggajian->get_data('data_jabatan')->result();

		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/jabatan/data_jabatan', $data);
		$this->load->view('template_admin/footer');
	}

	public function tambah_data() {
		$data['title'] = "Tambah Data Jabatan";

		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/jabatan/tambah_dataJabatan', $data);
		$this->load->view('template_admin/footer');
	}

	public function tambah_data_aksi() {
		$this->_rules(); // tetap bisa kosong

		if ($this->form_validation->run() == FALSE) {
			$this->tambah_data();
		} else {
			$data = array(
				'nama_jabatan'    => $this->input->post('nama_jabatan'),
				'gaji_pokok'      => $this->input->post('gaji_pokok') !== '' ? $this->input->post('gaji_pokok') : 0,
				'tj_transport'    => $this->input->post('tj_transport') !== '' ? $this->input->post('tj_transport') : 0,
				'uang_makan'      => $this->input->post('uang_makan') !== '' ? $this->input->post('uang_makan') : 0,
				'jenis_gaji'      => $this->input->post('jenis_gaji') !== '' ? $this->input->post('jenis_gaji') : 0,
				'tarif_borongan'  => $this->input->post('tarif_borongan') !== '' ? $this->input->post('tarif_borongan') : 0,
			);

			$this->ModelPenggajian->insert_data($data, 'data_jabatan');
			$this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Data berhasil ditambahkan!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>');
			redirect('admin/data_jabatan');
		}
	}

	public function update_data($id) {
		$where = array('id_jabatan' => $id);
		$data['jabatan'] = $this->db->get_where('data_jabatan', $where)->result();

		$data['title'] = "Update Data Jabatan";

		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/jabatan/update_dataJabatan', $data);
		$this->load->view('template_admin/footer');
	}

	public function update_data_aksi() {
		$id = $this->input->post('id_jabatan');
		$this->_rules();

		$jabatan_lama = $this->db->get_where('data_jabatan', ['id_jabatan' => $id])->row();

		if (!$jabatan_lama) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data tidak ditemukan.</div>');
			redirect('admin/data_jabatan');
			return;
		}

		$data = array(
			'nama_jabatan'    => $this->input->post('nama_jabatan') !== '' ? $this->input->post('nama_jabatan') : $jabatan_lama->nama_jabatan,
			'gaji_pokok'      => $this->input->post('gaji_pokok') !== '' ? $this->input->post('gaji_pokok') : $jabatan_lama->gaji_pokok,
			'tj_transport'    => $this->input->post('tj_transport') !== '' ? $this->input->post('tj_transport') : $jabatan_lama->tj_transport,
			'uang_makan'      => $this->input->post('uang_makan') !== '' ? $this->input->post('uang_makan') : $jabatan_lama->uang_makan,
			'jenis_gaji'      => $this->input->post('jenis_gaji') !== '' ? $this->input->post('jenis_gaji') : $jabatan_lama->jenis_gaji,
			'tarif_borongan'  => $this->input->post('tarif_borongan') !== '' ? $this->input->post('tarif_borongan') : $jabatan_lama->tarif_borongan,
		);

		$where = array('id_jabatan' => $id);
		$this->ModelPenggajian->update_data('data_jabatan', $data, $where);

		$this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Data berhasil diupdate!</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>');
		redirect('admin/data_jabatan');
	}

	public function delete_data($id) {
		$where = array('id_jabatan' => $id);
		$this->ModelPenggajian->delete_data($where, 'data_jabatan');
		$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Data berhasil dihapus!</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>');
		redirect('admin/data_jabatan');
	}

	public function _rules() {
		// Semua field boleh kosong, karena kita tangani default/manual
		$this->form_validation->set_rules('nama_jabatan', 'Nama Jabatan', 'trim');
		$this->form_validation->set_rules('gaji_pokok', 'Gaji Pokok', 'trim');
		$this->form_validation->set_rules('tj_transport', 'Tunjangan Transport', 'trim');
		$this->form_validation->set_rules('uang_makan', 'Uang Makan', 'trim');
		$this->form_validation->set_rules('jenis_gaji', 'Jenis Gaji', 'trim');
		$this->form_validation->set_rules('tarif_borongan', 'Tarif Borongan', 'trim');
	}
}
?>