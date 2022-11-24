<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        check_not_login();
        check_admin();
        $this->load->model('users_m');  // index
        $this->load->library('form_validation');  // add + edit
    }

	public function index()
	{
        $data['row'] = $this->users_m->get();
		$this->template->load('template', 'user/user_data', $data);
	}

    public function add()
    {
        $this->form_validation->set_rules('fullname', 'Nama', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[5]|is_unique[user.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]',
            array('matches' => 'password tidak sesuai %s.')
        );
       $this->form_validation->set_rules('level', 'Level', 'required');

        $this->form_validation->set_message('required', '%s tidak boleh kosong!');
        $this->form_validation->set_message('min_length', '{field} minimal 5 karakter');
        $this->form_validation->set_message('is_unique', '{field} sudah digunakan, silahkan ganti yang lain!');

        $this->form_validation->set_error_delimiters('<span class="help-block">', '<?span>');

        if ($this->form_validation->run() == FALSE) {
            $this->template->load('template', 'user/user_form_add');
        } else {
            $post = $this->input->post(null, TRUE);
            $this->users_m->add($post);
            if($this->db->affected_rows() > 0) {
                echo "<script>
                        alert('Data berhasil disimpan');
                    </script>";
            }
            echo "<script>
                    window.location='".site_url('user')."';
                </script>";
        }

    }

    public function edit($id)
    {
        $this->form_validation->set_rules('fullname', 'Nama', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[5]|callback_username_check');
        if($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[5]');
            $this->form_validation->set_rules('passconf', 'Password Confirmation', 'matches[password]',
                array('matches' => 'password tidak sesuai %s.')
            );
        }
        if($this->input->post('passconf')) {
           $this->form_validation->set_rules('passconf', 'Password Confirmation', 'matches[password]',
                array('matches' => 'password tidak sesuai %s.')
            );
        }
        $this->form_validation->set_rules('level', 'Level', 'required');

        $this->form_validation->set_message('required', '%s tidak boleh kosong!');
        $this->form_validation->set_message('min_length', '{field} minimal 5 karakter');
        $this->form_validation->set_message('is_unique', '{field} sudah digunakan, silahkan ganti yang lain!');

        $this->form_validation->set_error_delimiters('<span class="help-block">', '<?span>');

        if ($this->form_validation->run() == FALSE) {
            $query = $this->users_m->get($id);
            if($query->num_rows() > 0) {
                $data['row'] = $query->row();
                $this->template->load('template', 'user/user_form_edit', $data);
            } else {
                echo "<script>alert('Data tidak ditemukan');";
                echo "window.location='".site_url('user')."';</script>";
            }
            
        } else {
            $post = $this->input->post(null, TRUE);
            $this->users_m->edit($post);
            if($this->db->affected_rows() > 0) {
                echo "<script>
                        alert('Data berhasil disimpan');
                    </script>";
            }
            echo "<script>
                    window.location='".site_url('user')."';
                </script>";
        }

    }
    function username_check() {
        $post = $this->input->post(null, TRUE);
        $query = $this->db->query("SELECT * FROM user WHERE username = '$post[username]' AND user_id != '$post[user_id]'");
        if($query->num_rows() > 0){
            $this->form_validation->set_message('username_check', '{field} ini sudah dipakai, silahkan ganti');
            return FALSE;
        } else {
            return TRUE;
        }
    }


    public function del()
    {
        $id = $this->input->post('user_id');
        $this->users_m->del($id);

        if($this->db->affected_rows() > 0) {
            echo "<script>alert('Data berhasil dihapus');</script>";
        }
         echo "<script>window.location='".site_url('user')."';</script>";
    }

}
