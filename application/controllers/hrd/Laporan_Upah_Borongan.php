<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_Upah_Borongan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if($this->session->userdata('hak_akses') != '3'){
            $this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda Belum Login!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('login');
        }
    }

    public function index() 
    {   
        $data['title'] = "Laporan Upah Pegawai Borongan";
        $this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/upah/laporan_upah_borongan', $data);
        $this->load->view('template_hrd/footer');
    }

    public function cetak_laporan_upah_borongan(){
        $data['title'] = "Cetak Laporan Upah Pegawai Borongan";
        if((isset($_POST['bulan']) && $_POST['bulan'] != '') && (isset($_POST['tahun']) && $_POST['tahun'] != '') && (isset($_POST['minggu']) && $_POST['minggu'] != '')){
            $bulan = $_POST['bulan'];
            $tahun = $_POST['tahun'];
            $minggu = $_POST['minggu'];
            $bulantahun = $bulan . $tahun;
        } else {
            $bulan = date('m');
            $tahun = date('Y');
            $minggu = 1;
            $bulantahun = $bulan . $tahun;
        }

        // Calculate the start and end dates for the selected week
        $start_day = ($minggu - 1) * 7 + 1;
        $end_day = $start_day + 6;
        $last_day_of_month = date('t', strtotime("$tahun-$bulan-01"));
        if ($end_day > $last_day_of_month) {
            $end_day = $last_day_of_month;
        }
        $start_date = "$tahun-$bulan-" . str_pad($start_day, 2, '0', STR_PAD_LEFT);
        $end_date = "$tahun-$bulan-" . str_pad($end_day, 2, '0', STR_PAD_LEFT);

        // Log the date range for debugging
        log_message('info', "Date range for minggu $minggu: $start_date to $end_date");

        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['minggu'] = $minggu;
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['cetak_gaji'] = $this->db->query("
            SELECT DISTINCT 
                data_pegawai.nik, 
                data_pegawai.nama_pegawai,
                data_pegawai.id_pegawai,
                data_pegawai.jenis_kelamin, 
                data_jabatan.nama_jabatan,
                data_jabatan.gaji_pokok,
                data_jabatan.tj_transport,
                data_jabatan.uang_makan,
                data_jabatan.tarif_borongan, 
                target_mingguan.target_mingguan,
                data_kehadiran.alpha,
                COALESCE((
                    SELECT SUM(ph.jumlah_unit) 
                    FROM produksi_harian ph 
                    WHERE ph.id_pegawai = data_pegawai.id_pegawai
                    AND ph.tanggal BETWEEN '$start_date' AND '$end_date'
                ), 0) as total_produksi
            FROM data_pegawai
            INNER JOIN (
                SELECT nik, bulan, MAX(alpha) as alpha
                FROM data_kehadiran
                WHERE bulan = '$bulantahun'
                GROUP BY nik, bulan
            ) data_kehadiran ON data_kehadiran.nik = data_pegawai.nik
            INNER JOIN data_jabatan ON data_jabatan.nama_jabatan = data_pegawai.jabatan
            LEFT JOIN (
                SELECT nik_pegawai, bulan_target, tahun_target, mingguke, MAX(target_mingguan) as target_mingguan
                FROM target_mingguan
                WHERE bulan_target = '$bulan' AND tahun_target = '$tahun' AND mingguke = '$minggu'
                GROUP BY nik_pegawai, bulan_target, tahun_target, mingguke
            ) target_mingguan ON target_mingguan.nik_pegawai = data_pegawai.nik
            WHERE data_jabatan.jenis_gaji = 'Borongan'
            ORDER BY data_pegawai.nama_pegawai ASC
        ")->result();

        // Log the data for debugging
        foreach ($data['cetak_gaji'] as $g) {
            log_message('info', "Pegawai: {$g->nama_pegawai}, NIK: {$g->nik}, Total Produksi: {$g->total_produksi}, Gaji Pokok: {$g->gaji_pokok}, Tj. Transport: {$g->tj_transport}, Uang Makan: {$g->uang_makan}");
        }

        $this->load->view('hrd/upah/cetak_upah_borongan', $data);
    }
}