<?php
class Data_Penggajian_Borongan extends CI_Controller {

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
        $data['title'] = "Data Gaji Pegawai Borongan";
        if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
            $minggu = isset($_GET['minggu']) ? $_GET['minggu'] : 1;
            $bulantahun = $bulan . $tahun;
        } else {
            $bulan = date('m');
            $tahun = date('Y');
            $minggu = 1;
            $bulantahun = $bulan . $tahun;
        }

        // Calculate the start and end dates for the selected week
        $start_day = ($minggu - 1) * 7 + 1; // Start day of the week (e.g., 1, 8, 15, 22)
        $end_day = $start_day + 6; // End day of the week (e.g., 7, 14, 21, 28)

        // Adjust end_day to not exceed the last day of the month
        $last_day_of_month = date('t', strtotime("$tahun-$bulan-01"));
        if ($end_day > $last_day_of_month) {
            $end_day = $last_day_of_month;
        }

        $start_date = "$tahun-$bulan-" . str_pad($start_day, 2, '0', STR_PAD_LEFT);
        $end_date = "$tahun-$bulan-" . str_pad($end_day, 2, '0', STR_PAD_LEFT);

        // Log the date range for debugging
        log_message('info', "Date range for minggu $minggu: $start_date to $end_date");

        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        // Query dengan subquery untuk total produksi mingguan berdasarkan rentang tanggal
        $data['gaji'] = $this->db->query("
            SELECT DISTINCT 
                data_pegawai.id_pegawai, -- Ditambahkan untuk logging
                data_pegawai.nik, 
                data_pegawai.nama_pegawai,
                data_pegawai.jenis_kelamin, 
                data_jabatan.nama_jabatan, 
                data_jabatan.gaji_pokok, 
                data_jabatan.tj_transport, 
                data_jabatan.uang_makan, 
                data_jabatan.jenis_gaji, 
                data_jabatan.tarif_borongan, 
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
            WHERE data_jabatan.jenis_gaji = 'Borongan'
            ORDER BY data_pegawai.nama_pegawai ASC
        ")->result();

        // Log the gaji data for debugging
        foreach ($data['gaji'] as $g) {
            log_message('info', "Pegawai: {$g->nama_pegawai}, NIK: {$g->nik}, ID Pegawai: {$g->id_pegawai}, Total Produksi: {$g->total_produksi}");
        }

        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['minggu'] = $minggu;

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/gaji/data_gaji_borongan', $data);
        $this->load->view('template_admin/footer');
    }

    public function cetak_gaji() {
        $data['title'] = "Cetak Daftar Gaji Pegawai Borongan";
        if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
            $minggu = isset($_GET['minggu']) ? $_GET['minggu'] : 1;
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

        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['minggu'] = $minggu;
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['cetak_gaji'] = $this->db->query("
            SELECT DISTINCT 
                data_pegawai.nik, 
                data_pegawai.nama_pegawai,
                data_pegawai.jenis_kelamin, 
                data_jabatan.nama_jabatan, 
                data_jabatan.gaji_pokok, 
                data_jabatan.tarif_borongan, 
                data_jabatan.tj_transport, 
                data_jabatan.uang_makan, 
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
            WHERE data_jabatan.jenis_gaji = 'Borongan'
            ORDER BY data_pegawai.nama_pegawai ASC
        ")->result();
        
        $this->load->view('admin/gaji/cetak_gaji_borongan', $data);
    }
}