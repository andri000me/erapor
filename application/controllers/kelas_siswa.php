<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_siswa extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (! $this->session->userdata('logged_in') || $this->session->userdata('logged_in') == null) {
			redirect('/');
		}
		$this->load->model('service_model');
		$this->load->model('kelas_siswa_model');
	}

	public function getAllKelasSiswa()
	{
		if(! $this->input->is_ajax_request()) {
			$data['title'] = '404 Page Not Found';
    		$this->load->view('error404_view',$data);
    		return;
		}

		$data = $this->kelas_siswa_model->getAllKelasSiswa($this->input->get('id_siswa'));
		$r = array('data' => $data );

		echo json_encode($r);
	}

	public function addKelasSiswa()
	{
		if(! $this->input->is_ajax_request()) {
			$data['title'] = '404 Page Not Found';
    		$this->load->view('error404_view',$data);
    		return;
		}

		$r = array('status' => false, 'error' => '' );

		if ($this->input->post('th_ajar') == '' || $this->input->post('id_kelas') == '') {
			$r['error'] = 'Isi semua data!';
			echo json_encode($r);
			return;
		}

		$data = array(
			'th_ajar' => $this->input->post('th_ajar'), 
			'id_kelas' => $this->input->post('id_kelas'),
			'id_siswa' => $this->input->post('id_siswa')
		);
		$data = $this->service_model->escape_array($data);
		if ($this->kelas_siswa_model->addKelasSiswa($data)) {
			$r['status'] = true;
		} else {
			$r['error'] = 'Gagal menambahkan kelas';
		}

		echo json_encode($r);
	}

	public function deleteKelasSiswa()
	{
		if(! $this->input->is_ajax_request()) {
			$data['title'] = '404 Page Not Found';
    		$this->load->view('error404_view',$data);
    		return;
		}

		$r = array('status' => false, 'error' => '');

		if ($this->input->post('id') == '') {
			$r['error'] = 'Id tidak ada!';
			echo json_encode($r);
			return;
		}

		if ($this->kelas_siswa_model->deleteKelasSiswa($this->input->post('id'))) {
			$r['status'] = true;
		} else {
			$r['error'] = 'Gagal menghapus kelas';
		}

		echo json_encode($r);
	}

	public function editKelas()
	{
		if(! $this->input->is_ajax_request()) {
			$data['title'] = '404 Page Not Found';
    		$this->load->view('error404_view',$data);
    		return;
		}

		$r = array('status' => false, 'error' => '');
		if ($this->input->post('id') == '') {
			$r['error'] = 'Isi semua data yang diperlukan!';
			echo json_encode($r);
			return;
		}

		$this->kelas_siswa_model->deleteKelasSiswaBySiswaId($this->input->post('id'));

		foreach ($this->input->post('kelas') as $kelas) {
			$data = array('id_kelas' => $kelas, 'id_siswa' => $this->input->post('id'));
			$data = $this->service_model->escape_array($data);
			if ($this->kelas_siswa_model->addKelasSiswa($data)) {
				$r['status'] = true;
			} else {
				$r['error'] = 'Gagal menambahkan kelas';
			}
		}

		echo json_encode($r);
	}

	public function getThAjar()
	{
		if(! $this->input->is_ajax_request()) {
			$data['title'] = '404 Page Not Found';
    		$this->load->view('error404_view',$data);
    		return;
		}
		
		$r = $this->kelas_siswa_model->getThAjar();

		echo json_encode($r);
	}
}

/* End of file kelas_siswa.php */
/* Location: ./application/controllers/kelas_siswa.php */