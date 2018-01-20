<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('Satpam');
	}

	public function index() {
		$this->load->view('welcome_message');
	}

	public function login(){
		$email = $this->input->post('email');
		$pass = $this->input->post('pass');

		if ($this->satpam->CekLogin($email, $pass)) {
			$this->session->set_flashdata('success', 'Anda berhasil login');
			redirect(base_url('welcome/member_area'));
		} else{
			$this->session->set_flashdata('fail', 'user password anda salah. Ulangi lagi.');
			redirect(base_url());
		}
	}

	public function daftar() {
		$user_name = $this->input->post('user_name');
		$email = $this->input->post('email');
		$pass = $this->input->post('pass');
		$role = $this->input->post('role');

		if ($this->input->post('autologin') == 1) {
			$autologin = true;
		} else {
			$autologin = false;
		}

		if($this->satpam->BuatUserBaru($user_name, $email, $pass, $role, $autologin)) {
			$this->session->set_flashdata('success', 'user berhasil di tambahkan');
			redirect(base_url());
		} else {
			$this->session->set_flashdata('fail', 'Respon error atau Email sudah terdaftar. Ulangi lagi.');
			redirect(base_url());
		}
	}

	public function member_area() {
		if($this->satpam->cekStatus()) {
			$data['info'] = $this->satpam->cekInfo();
			$this->load->view('member_area', $data);
		} else {
			$this->session->set_flashdata('fail', 'anda belum login.');
			redirect(base_url());
		} 
	}

	public function gantiemail() {
		$id = $this->input->post('id');
		$email = $this->input->post('email');

		if ($this->satpam->edit_email($id, $email, $autologin)) {
			$this->session->set_flashdata('success', 'user berhasil di tambahkan');
			redirect(base_url('welcome/member_area'));
		}
	}

	public function hapus($id) {
		if($this->satpam->hapus($id))
			$this->session->set_flashdata('success', 'user berhasil di hapus');
			redirect(base_url());
	}

	public function logOut() {
		$this->satpam->logout();
		$this->session->set_flashdata('success', 'Log Out berhasil');
		redirect(base_url());
	}
}
