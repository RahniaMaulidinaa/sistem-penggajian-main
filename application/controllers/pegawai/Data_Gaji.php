<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Gaji extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('hak_akses') != '2') {
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
        $data['title'] = "Data Gaji";
        $nik = $this->session->userdata('nik');
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();

        // Cek jenis gaji pegawai
        $this->db->select('data_jabatan.jenis_gaji');
        $this->db->from('data_pegawai');
        $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
        $this->db->where('data_pegawai.nik', $nik);
        $row = $this->db->get()->row();
        $data['is_borongan'] = isset($row->jenis_gaji) && $row->jenis_gaji == 'Borongan';

        if ($data['is_borongan']) {
            // Gaji borongan
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_pegawai.id_pegawai, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_jabatan.tarif_borongan, target_mingguan.id, target_mingguan.target_mingguan, target_mingguan.bulan_target, target_mingguan.tahun_target, target_mingguan.mingguke, COALESCE(data_kehadiran.alpha, 0) as alpha');
            $this->db->from('data_pegawai');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->join('target_mingguan', 'target_mingguan.nik_pegawai = data_pegawai.nik');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik AND data_kehadiran.bulan = CONCAT(LPAD(target_mingguan.bulan_target, 2, "0"), target_mingguan.tahun_target)', 'left');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->group_by('target_mingguan.id, target_mingguan.bulan_target, target_mingguan.tahun_target, target_mingguan.mingguke');
            $this->db->order_by('target_mingguan.tahun_target DESC, target_mingguan.bulan_target DESC, target_mingguan.mingguke DESC');
            $gaji_result = $this->db->get()->result();

            // Tambahkan total_produksi dan filter hanya yang > 0
            $data['gaji'] = [];
            foreach ($gaji_result as $g) {
                $bulan = str_pad($g->bulan_target, 2, '0', STR_PAD_LEFT);
                $tahun = $g->tahun_target;
                $minggu = $g->mingguke;

                // Hitung rentang tanggal minggu dengan lebih akurat
                $first_day_of_month = new DateTime("$tahun-$bulan-01");
                $start_date = clone $first_day_of_month;
                $start_date->modify('+' . (($minggu - 1) * 7) . ' days');
                $end_date = clone $start_date;
                $end_date->modify('+6 days');
                $last_day_of_month = (new DateTime("$tahun-$bulan-01"))->modify('last day of this month');
                if ($end_date > $last_day_of_month) {
                    $end_date = $last_day_of_month;
                }

                $start_date_str = $start_date->format('Y-m-d');
                $end_date_str = $end_date->format('Y-m-d');

                // Log id_pegawai untuk debug
                log_message('info', "Checking id_pegawai: {$g->id_pegawai} for NIK: {$nik}");

                // Ambil total produksi
                $this->db->select('COALESCE(SUM(jumlah_unit), 0) as total_produksi');
                $this->db->from('produksi_harian');
                $this->db->where('id_pegawai', $g->id_pegawai);
                $this->db->where("tanggal BETWEEN '$start_date_str' AND '$end_date_str'");
                $produksi_query = $this->db->get();
                $total_produksi = $produksi_query->row()->total_produksi;
                $g->total_produksi = $total_produksi;

                // Query terpisah untuk logging detail produksi
                $this->db->select('tanggal, jumlah_unit');
                $this->db->from('produksi_harian');
                $this->db->where('id_pegawai', $g->id_pegawai);
                $this->db->where("tanggal BETWEEN '$start_date_str' AND '$end_date_str'");
                $produksi_detail = $this->db->get();

                // Log detail produksi
                if ($produksi_detail->num_rows() > 0) {
                    foreach ($produksi_detail->result() as $row) {
                        log_message('info', "Produksi ditemukan: id_pegawai: {$g->id_pegawai}, tanggal: {$row->tanggal}, jumlah_unit: {$row->jumlah_unit}");
                    }
                } else {
                    log_message('info', "Tidak ada data produksi untuk id_pegawai: {$g->id_pegawai}, rentang: $start_date_str to $end_date_str");
                }

                // Logging untuk debug
                log_message('info', "NIK: {$nik}, Periode: $bulan-$tahun Minggu ke-$minggu, Total Produksi: $total_produksi, Date Range: $start_date_str to $end_date_str, Target ID: {$g->id}");

                // Hanya tambahkan entri jika total_produksi > 0
                if ($total_produksi > 0) {
                    $data['gaji'][] = $g;
                } else {
                    log_message('info', "Periode $bulan-$tahun Minggu ke-$minggu untuk NIK {$nik} tidak ditampilkan karena total_produksi = 0");
                }
            }

            // Log jumlah periode yang ditampilkan
            log_message('info', 'Jumlah periode gaji borongan untuk NIK ' . $nik . ' setelah filter (produksi > 0): ' . count($data['gaji']));
        } else {
            // Gaji bulanan (tetap tidak diubah)
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_kehadiran.alpha, data_kehadiran.bulan, data_kehadiran.id_kehadiran');
            $this->db->from('data_pegawai');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->order_by('data_kehadiran.bulan DESC');
            $data['gaji'] = $this->db->get()->result();
        }

        $this->load->view('template_pegawai/header', $data);
        $this->load->view('template_pegawai/sidebar');
        $this->load->view('pegawai/data_gaji', $data);
        $this->load->view('template_pegawai/footer');
    }

    public function cetak_slip($id) {
        $data['title'] = 'Cetak Slip Gaji';
        $nik = $this->session->userdata('nik');
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();

        // Cek jenis gaji
        $this->db->select('data_jabatan.jenis_gaji');
        $this->db->from('data_pegawai');
        $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
        $this->db->where('data_pegawai.nik', $nik);
        $row = $this->db->get()->row();
        $data['is_borongan'] = isset($row->jenis_gaji) && $row->jenis_gaji == 'Borongan';

        if ($data['is_borongan']) {
            // Slip borongan
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_pegawai.id_pegawai, data_jabatan.nama_jabatan, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_jabatan.tarif_borongan, target_mingguan.target_mingguan, target_mingguan.bulan_target, target_mingguan.tahun_target, target_mingguan.mingguke, COALESCE(data_kehadiran.alpha, 0) as alpha');
            $this->db->from('data_pegawai');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->join('target_mingguan', 'target_mingguan.nik_pegawai = data_pegawai.nik');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik AND data_kehadiran.bulan = CONCAT(LPAD(target_mingguan.bulan_target, 2, "0"), target_mingguan.tahun_target)', 'left');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->where('target_mingguan.id', $id);
            $this->db->group_by('target_mingguan.id, target_mingguan.bulan_target, target_mingguan.tahun_target, target_mingguan.mingguke');
            $data['print_slip'] = $this->db->get()->row();

            if ($data['print_slip']) {
                // Hitung rentang tanggal
                $bulan = str_pad($data['print_slip']->bulan_target, 2, '0', STR_PAD_LEFT);
                $tahun = $data['print_slip']->tahun_target;
                $minggu = $data['print_slip']->mingguke;

                // Hitung rentang tanggal minggu dengan lebih akurat
                $first_day_of_month = new DateTime("$tahun-$bulan-01");
                $start_date = clone $first_day_of_month;
                $start_date->modify('+' . (($minggu - 1) * 7) . ' days');
                $end_date = clone $start_date;
                $end_date->modify('+6 days');
                $last_day_of_month = (new DateTime("$tahun-$bulan-01"))->modify('last day of this month');
                if ($end_date > $last_day_of_month) {
                    $end_date = $last_day_of_month;
                }

                $start_date_str = $start_date->format('Y-m-d');
                $end_date_str = $end_date->format('Y-m-d');

                // Ambil total produksi
                $this->db->select('COALESCE(SUM(jumlah_unit), 0) as total_produksi');
                $this->db->from('produksi_harian');
                $this->db->where('id_pegawai', $data['print_slip']->id_pegawai);
                $this->db->where("tanggal BETWEEN '$start_date_str' AND '$end_date_str'");
                $total_produksi_result = $this->db->get()->row();
                $data['print_slip']->total_produksi = $total_produksi_result->total_produksi;

                // Cek apakah total_produksi > 0 untuk slip
                if ($data['print_slip']->total_produksi == 0) {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Data slip gaji tidak dapat dicetak karena total produksi 0!</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                        </div>');
                    redirect('pegawai/data_gaji');
                }

                // Debug
                log_message('info', "Slip for NIK: {$data['print_slip']->nik}, Total Produksi: {$data['print_slip']->total_produksi}, Tarif Borongan: {$data['print_slip']->tarif_borongan}, Date Range: $start_date_str to $end_date_str, Target ID: {$id}");
            }

            if (!$data['print_slip']) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Data slip gaji tidak ditemukan!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    </div>');
                redirect('pegawai/data_gaji');
            }

            $this->load->view('pegawai/cetak_slip_gaji_borongan', $data);
        } else {
            // Slip bulanan 
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_jabatan.nama_jabatan, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_kehadiran.alpha, data_kehadiran.bulan');
            $this->db->from('data_pegawai');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->where('data_kehadiran.id_kehadiran', $id);
            $data['print_slip'] = $this->db->get()->row();

            if (!$data['print_slip']) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Data slip gaji tidak ditemukan!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    </div>');
                redirect('pegawai/data_gaji');
            }

            $this->load->view('pegawai/cetak_slip_gaji', $data);
        }
    }
}