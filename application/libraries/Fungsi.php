<?php

Class Fungsi {

    protected $ci;

    public function __construct() {
        $this->ci =& get_instance();
    }

    function user_login() {
        $this->ci->load->model('users_m');
        $user_id = $this->ci->session->userdata('userid');
        $user_data = $this->ci->users_m->get($user_id)->row();
        return $user_data;
    }

    function PdfGenerator($html, $filename, $paper, $orientation) {
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        $dompdf->stream($filename, array('Attachment' => 0));
    }
}