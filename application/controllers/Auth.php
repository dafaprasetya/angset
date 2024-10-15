<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
		
    }
	public function index()
	{
		$this->load->model('modeluser');
		if ($this->session->userdata('is_login')) {
			redirect('home');
		}
        $data['judul'] = 'Login';
		$this->load->view('auth/login', $data);
	}
	public function login() {
        $this->load->model('ModelUser');
		if ($this->session->userdata('is_login')) {
            redirect('home');
		}
		$nik = $this->input->post('nik');
		$password = $this->input->post('password');
        $query = $this->db->get_where('user', array('nik'=>$nik));
        $data_user = $query->row();
		if ($this->ModelUser->login($nik, $password)) {
            redirect('home');
		}else{
            echo var_dump($data_user);
            // $this->session->set_flashdata('error', 'password salah');
			// redirect('auth');
		}
	}
    public function daftar()
	{
		$this->load->model('modeluser');
		if ($this->session->userdata('is_login')) {
			redirect('home');
		}
        $data['judul'] = 'Register';
		$this->load->view('auth/register', $data);
	}
    public function register() {
		$this->load->model('ModelUser');
		if ($this->session->userdata('is_login')) {
			redirect('home');
		}
		$this->form_validation->set_rules('nik', 'nik','trim|required|min_length[1]|max_length[255]');
		$this->form_validation->set_rules('nama', 'nama','trim|required|min_length[1]|max_length[255]');
		$this->form_validation->set_rules('email', 'email','trim|required|min_length[1]|max_length[255]|is_unique[user.email]');
		$this->form_validation->set_rules('password', 'password','trim|required|min_length[1]|max_length[255]');
		if ($this->form_validation->run())
		   {
			$nik = $this->input->post('nik');
			$nama = $this->input->post('nama');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$role = 'user';
			$this->ModelUser->register($nik, $nama, $email, $password);
			$this->session->set_flashdata('success_register',$nama);
			redirect('auth/login');
		}
		else
		{
			$this->session->set_flashdata('error', validation_errors());
			redirect('auth/register');
		}
	}
}