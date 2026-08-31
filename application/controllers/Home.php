<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function index(){

        $data['banner_slider'] = [];

        if($this->db->table_exists('website_banner')){
            $data['banner_slider'] = $this->db
                ->where('status', 'Published')
                ->order_by('urutan', 'ASC')
                ->order_by('id', 'DESC')
                ->limit(6)
                ->get('website_banner')
                ->result();
        }

        $this->db->where('status_berita', 'Published');

        if($this->db->field_exists('is_featured', 'berita')){
            $this->db->order_by('is_featured', 'DESC');
        }

        if($this->db->field_exists('featured_order', 'berita')){
            $this->db->order_by('featured_order', 'ASC');
        }

        if($this->db->field_exists('featured_at', 'berita')){
            $this->db->order_by('featured_at', 'DESC');
        }

        $data['berita'] = $this->db
            ->order_by('published_at', 'DESC')
            ->order_by('created_at', 'DESC')
            ->get('berita')
            ->result();

        $data['profil_website'] = $this->db
            ->limit(1)
            ->get('website_profil')
            ->row();

        $profil = $data['profil_website'];

        $data['nsm'] = !empty($profil->nsm)
            ? $profil->nsm
            : '-';

        $data['npsn'] = !empty($profil->npsn)
            ? $profil->npsn
            : '-';

        $data['video_profil'] = $this->db
            ->where('status', 'Published')
            ->order_by('updated_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('website_video')
            ->row();

        $data['pamflet'] = $this->db
            ->where('status', 'Published')
            ->order_by('tanggal', 'DESC')
            ->order_by('created_at', 'DESC')
            ->limit(6)
            ->get('website_pamflet')
            ->result();

        $data['galeri'] = $this->db
            ->where('status', 'Published')
            ->order_by('tanggal', 'DESC')
            ->order_by('created_at', 'DESC')
            ->limit(8)
            ->get('website_galeri')
            ->result();

        $this->db->where('tampil_website', 1);
        $this->db->group_start();
        $this->db->where('status_aktif', 'Aktif');
        $this->db->or_where('status_aktif', '');
        $this->db->or_where('status_aktif IS NULL', null, false);
        $this->db->group_end();

        $data['ptk_website'] = $this->db
            ->order_by('urutan_website', 'ASC')
            ->order_by('nama_lengkap', 'ASC')
            ->get('ptk')
            ->result();

        $data['kepala_madrasah'] = null;

        $settings = $this->db
            ->limit(1)
            ->get('settings')
            ->row();

        if($settings && !empty($settings->kepala_madrasah_ptk_id)){
            $data['kepala_madrasah'] = $this->db
                ->where('id', $settings->kepala_madrasah_ptk_id)
                ->get('ptk')
                ->row();
        }

        $this->load->view('public/home', $data);
    }

}