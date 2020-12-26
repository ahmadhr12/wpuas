<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
	function construct()
	{
		parent:: __construct();
		#cek Login
		if($this->session->userdate('status') != "login"){
			redirect(base_url(). 'welcome?pesan=belumlogin');
		}
	}

	function index(){
		$data['transaksi'] = $this->db->query("SELECT * FROM transaksi ORDER BY id_pinjam DESC LIMIT 10")->result();
		$data['buku'] = $this->db->query("SELECT * FROM buku ORDER BY id_buku DESC LIMIT 10")->result();

		$this->load->view('anggota/header');
		$this->load->view('anggota/index',$data);
		$this->load->view('anggota/footer');
	}

	function logout(){
		$this->session->sess_destroy();
		redirect(base_url().'welcome?pesan=logout');
	}

 	function ganti_password(){
 		$this->load->view('anggota/header');
 		$this->load->view('anggota/ganti_password');
 		$this->load->view('anggota/footer');
	}

 	function ganti_password_act(){
 		$pass_baru = $this->input->post('pass_baru');
 		$ulang_pass = $this->input->post('ulang_pass');

 		$this->form_validation->set_rules('pass_baru','Password Baru','required|matches[ulang_pass}');
 		$this->form_validation->set_rules('ulang_pass','Ulangi Password Baru','required');
 		if($this->form_validation->run() != false){
 			$data = array('password' =>md5($pass_baru));
 			$w = array('id_anggota' => $this->session->userdata('id'));
 			$this->M_perpus->update_data($w,$data,'anggota');
 			redirect(base_url().'anggota/ganti_password?pesan=berhasil');
 		}else{
 			$this->load->view('anggota/header');
 			$this->load->view('anggota/ganti_password');
 			$this->load->view('anggota/footer');
 			}
	}

	function buku(){
		$data['buku'] = $this->M_perpus->get_data('buku')->result();
		$this->load->view('anggota/header');
 		$this->load->view('anggota/buku',$data);
 		$this->load->view('anggota/footer');
	}

		function peminjaman(){
				$data['peminjaman'] = $this->db->query("SELECT * FROM transaksi T, buku B, anggota A WHERE T.id_buku = B.id_buku and T.id_anggota = A.id_anggota")->result();
				$this->load->view('anggota/header');
				$this->load->view('anggota/peminjaman',$data);
				$this->load->view('aanggota/footer');
			}
	
		function tambah_peminjaman(){
				$w = array('status_buku'=>'1');
				$data['buku'] = $this->M_perpus->edit_data($w,'buku')->result();
				$data['anggota'] = $this->M_perpus->get_data('anggota')->result();
				$data['peminjaman'] = $this->M_perpus->get_data('transaksi')->result();
					$this->load->view('anggota/header');
					$this->load->view('anggota/tambah_peminjaman',$data);
					$this->load->view('aanggota/footer');
			}
	
		function tambah_peminjaman_act(){
				$tgl_pencatatan = date('Y-m-d H:i:s');
				$anggota = $this->input->post('anggota');
				$buku = $this->input->post('buku');
				$tgl_pinjam = $this->input->post('tgl_pinjam');
				$tgl_kembali = $this->input->post('tgl_kembali');
				$denda = $this->input->post('denda');
			$this->form_validation->set_rules('anggota','Anggota','required');
			$this->form_validation->set_rules('buku','Buku','required');
			$this->form_validation->set_rules('tgl_pinjam','Tanggal Pinjam','required');
			$this->form_validation->set_rules('tgl_kembali','Tanggal Kembali','required');
			$this->form_validation->set_rules('denda','Denda','required');
			if($this->form_validation->run() != false){
				$data = array(
				'tgl_pencatatan' => $tgl_pencatatan,
				'id_anggota' => $anggota,
				'id_buku' => $buku,
				'tgl_pinjam' => $tgl_pinjam,
				'tgl_kembali' => $tgl_kembali,
				'denda' => $denda,
				'tgl_pengembalian' => '0000-00-00',
				'total_denda' => '0',
				'status_pengembalian' =>'0',
				'status_peminjaman' =>'0'
			);
				$this->M_perpus->insert_data($data,'transaksi');
				$d = array('status_buku' =>'0','tgl_input' =>substr($tgl_pencatatan,0,10));
				$w = array('id_buku' => $buku);
				$this->M_perpus->update_data('buku',$d,$w);
				redirect(base_url().'anggota/peminjaman');
			}else{
				$w = array('status_buku' => '1');
				$data['buku'] = $this->M_perpus->edit_data($w,'buku')->result();
				$data['anggota'] = $this->M_perpus->get_data('anggota')->result();
					$this->load->view('anggota/header');
					$this->load->view('anggota/tambah_peminjaman',$data);
					$this->load->view('anggota/footer');
			}
			}

 ?>