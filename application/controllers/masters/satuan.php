<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Satuan extends CI_Controller
{
      function __construct()
      {
            parent::__construct();

            is_logged_in();

            if (is_username() == '') {
                  redirect('home');
            }

            if (is_maintenance_on() && is_posisi_id() != 1) {
                  redirect(base_url() . 'home/maintenance_mode');
            }

            $this->data['username'] = is_username();
            $this->data['user_menu_list'] = is_user_menu(is_posisi_id());
      }

      // register module
      function index()
      {
            redirect('admin');
      }

      function setting_link($string)
      {
            return rtrim(base64_encode($string), '=');
      }

      // list data 
      function daftar()
      {
            //apps
            $menu = is_get_url_trans($this->uri->segment(1));

            $data = array(
                  'nama_menu' => $menu[0],
                  'nama_submenu' => $menu[1],
            );

            //main view
            $data = array_merge($data, array(
                  'breadcrumb_title' => 'Master',
                  'breadcrumb_small' => 'Satuan',
                  'content' => 'masters/satuan/daftar_view',
                  'editor' => 'masters/satuan/editor_view',
            ));

            //data
            $data = array_merge($data, array(
                  'common_data' => $this->data,
                  'data_isi' => $this->data
            ));

            $this->load->view('admin/template', $data);
      }
}
