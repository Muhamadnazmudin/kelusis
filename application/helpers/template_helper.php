<?php
function template($view, $data = [])
{
    $CI =& get_instance();

    $CI->load->view('template/header', $data);
    $CI->load->view('template/sidebar', $data);
    $CI->load->view('template/topbar', $data);
    $CI->load->view($view, $data);
    $CI->load->view('template/footer', $data);
    $CI->load->view('template/script', $data);
}