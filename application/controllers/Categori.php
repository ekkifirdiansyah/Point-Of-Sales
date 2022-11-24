<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categori extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		check_not_login();
		$this->load->model('categori_m');
	}

	public function index()
	{
		$data['row'] = $this->categori_m->get();
		$this->template->load('template', 'product/categori/categori_data', $data);
	}

	public function add()
	{
		$categori = new stdClass();
		$categori->categori_id = null;
		$categori->name = null;
		$data = array(
			'page' => 'add',
			'row' => $categori
		);
		$this->template->load('template', 'product/categori/categori_form', $data);
	}

	public function edit($id)
	{
		$query = $this->categori_m->get($id);
		if($query->num_rows() > 0) {
			$categori = $query->row();
			$data = array(
				'page' => 'edit',
				'row' => $categori
			);
			$this->template->load('template', 'product/categori/categori_form', $data);
		} else {
			echo "<script>alert('Data tidak ditemukan');</script>";
			echo "<script>window.location='".site_url('categori')."';</script>";
		}
	}

	public function process()
	{
		$post = $this->input->post(null, TRUE);
		if(isset($_POST['add'])) {
			$this->categori_m->add($post);
		} else if(isset($_POST['edit'])) {
			$this->categori_m->edit($post);
		}

		if($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('success', 'Data berhasil disimpan');
		}
		redirect('categori');
	}

	public function del($id)
	{
		$this->categori_m->del($id);
		if($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('success', 'Data berhasil dihapus');
		}
		redirect('categori');
	}
}
