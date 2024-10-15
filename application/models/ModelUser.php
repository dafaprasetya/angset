<?php 
    class ModelUser extends CI_Model 
    {
        
        const SESSION_KEY = 'userId';
        public function __construct()
        {
            parent::__construct();
        }
    
        function register($nik, $nama, $email, $password)
        {
            $data_user = array(
                'nik' => $nik,
                'nama'=>$nama,
                'email'=>$email,
                'password'=>password_hash($password,PASSWORD_DEFAULT),
            );
            $this->db->insert('user',$data_user);
        }
        public function login($nik, $password) {
            $query = $this->db->get_where('user', array('nik'=>$nik));
            if ($query->num_rows() > 0) {
                $data_user = $query->row();
                if (password_verify($password, $data_user->password)) {
                    $this->session->set_userdata('nik', $data_user->userId);
                    $this->session->set_userdata('nama', $data_user->nama);
                    $this->session->set_userdata('email', $data_user->email);
                    $this->session->set_userdata('is_login', TRUE);
                    
                }else {
                    return FALSE;
                }
            }else{
                
                return FALSE;
            }

        }
        public function logout()
        {
            $this->session->unset_userdata('nik');
            $this->session->unset_userdata('nama');
            $this->session->unset_userdata('email');
            $this->session->unset_userdata('is_login');
            $this->session->session_destroy();
        }
    }
?>