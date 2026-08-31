<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berita extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url','form','access']);
    }

    private function can_manage_berita(){
        return $this->session->userdata('logged_in') && can_admin_menu('berita');
    }

    private function increment_view_count($berita){
        if(!$berita || !$this->db->field_exists('view_count', 'berita')){
            return $berita;
        }

        // Preview admin tidak dihitung sebagai pembaca publik.
        if($this->can_manage_berita()){
            return $berita;
        }

        if($this->db->field_exists('status_berita', 'berita') && $berita->status_berita != 'Published'){
            return $berita;
        }

        $this->db->set('view_count', 'view_count + 1', false);

        if($this->db->field_exists('last_viewed_at', 'berita')){
            $this->db->set('last_viewed_at', date('Y-m-d H:i:s'));
        }

        $this->db->where('id', $berita->id);
        $this->db->update('berita');

        $berita->view_count = isset($berita->view_count) ? ((int)$berita->view_count + 1) : 1;
        $berita->last_viewed_at = date('Y-m-d H:i:s');

        return $berita;
    }

    public function index(){

        if(!$this->can_manage_berita()){
            show_error('Anda tidak memiliki akses kelola berita.', 403);
        }

        $status = $this->input->get('status');
        $bulan = $this->input->get('bulan');
        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');

        $this->db->from('berita');

        if(!empty($status)){
            $this->db->where('status_berita',$status);
        }

        if(!empty($tanggal_awal) || !empty($tanggal_akhir)){
            if(!empty($tanggal_awal)){
                $this->db->where('DATE(created_at) >=', $tanggal_awal);
            }
            if(!empty($tanggal_akhir)){
                $this->db->where('DATE(created_at) <=', $tanggal_akhir);
            }
        } elseif(!empty($bulan)){
            $this->db->where('DATE_FORMAT(created_at,"%Y-%m") =', $bulan, false);
        }

        $data['berita'] = $this->db
            ->order_by('created_at','DESC')
            ->get()
            ->result();

        $data['filter_status'] = $status;
        $data['filter_bulan'] = $bulan;
        $data['filter_tanggal_awal'] = $tanggal_awal;
        $data['filter_tanggal_akhir'] = $tanggal_akhir;

        $data['total_semua'] = $this->db->count_all('berita');

        $data['total_published'] = $this->db
            ->where('status_berita','Published')
            ->count_all_results('berita');

        $data['total_draft'] = $this->db
            ->where('status_berita','Draft')
            ->count_all_results('berita');

        if($this->db->field_exists('view_count', 'berita')){
            $views = $this->db
                ->select_sum('view_count', 'total_views')
                ->get('berita')
                ->row();
            $data['total_views'] = $views && $views->total_views ? (int)$views->total_views : 0;
        } else {
            $data['total_views'] = 0;
        }

        $this->load->view('berita/index',$data);
    }

    public function add(){

        if(!$this->can_manage_berita()){
            show_error('Anda tidak memiliki akses kelola berita.', 403);
        }

        $gambar = '';

        if(!empty($_FILES['gambar']['name'])){
            $config['upload_path'] = './assets/news/';
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size'] = 4096;
            $config['encrypt_name'] = TRUE;

            if(!is_dir($config['upload_path'])){
                mkdir($config['upload_path'],0777,true);
            }

            $this->load->library('upload',$config);

            if($this->upload->do_upload('gambar')){
                $upload = $this->upload->data();
                $gambar = $upload['file_name'];
            } else {
                $this->session->set_flashdata('error',$this->upload->display_errors('',''));
                redirect('berita');
            }
        }

        $this->db->insert('berita',[
            'judul' => $this->input->post('judul'),
            'isi' => $this->input->post('isi'),
            'gambar' => $gambar,
            'status_berita' => 'Draft',
            'published_at' => null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $berita_id = $this->db->insert_id();

        $this->upload_multi_berita($berita_id);
        $this->generate_pamflet_file($berita_id, false);
        $this->session->set_flashdata('success','Berita berhasil disimpan sebagai Draft');

        redirect('berita');
    }

    public function publish($id){

        if(!$this->can_manage_berita()){
            show_error('Anda tidak memiliki akses kelola berita.', 403);
        }

        $berita = $this->db
            ->where('id',$id)
            ->get('berita')
            ->row();

        if(!$berita){
            show_404();
        }

        $this->db->where('id',$id);
        $this->db->update('berita',[
            'status_berita' => 'Published',
            'published_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success','Berita berhasil dipublish');
        redirect('berita');
    }

    public function draft($id){

        if(!$this->can_manage_berita()){
            show_error('Anda tidak memiliki akses kelola berita.', 403);
        }

        $berita = $this->db
            ->where('id',$id)
            ->get('berita')
            ->row();

        if(!$berita){
            show_404();
        }

        $this->db->where('id',$id);
        $this->db->update('berita',[
            'status_berita' => 'Draft',
            'published_at' => null
        ]);

        $this->session->set_flashdata('success','Berita dikembalikan menjadi Draft');
        redirect('berita');
    }

    public function detail($id){

        if(is_numeric($id)){
            $this->db->where('id', $id);
        } elseif($this->db->field_exists('slug', 'berita')){
            $this->db->where('slug', $id);
        } else {
            show_404();
        }

        $data['berita'] = $this->db
            ->get('berita')
            ->row();

        if(!$data['berita']){
            show_404();
        }

        $data['berita'] = $this->increment_view_count($data['berita']);

        $berita_id = $data['berita']->id;
        $data['gambar_berita'] = $this->get_berita_gambar($berita_id);

        if($this->db->field_exists('status_berita', 'berita')){
            $this->db->where('status_berita', 'Published');
        }

        $data['berita_lainnya'] = $this->db
            ->where('id !=', $berita_id)
            ->order_by('published_at', 'DESC')
            ->order_by('created_at', 'DESC')
            ->limit(5)
            ->get('berita')
            ->result();

        $data['profil_website'] = null;

        if($this->db->table_exists('website_profil')){
            $data['profil_website'] = $this->db
                ->limit(1)
                ->get('website_profil')
                ->row();
        }

        $this->load->view('public/detail_berita', $data);
    }

    public function delete($id){

        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }

        $berita = $this->db
            ->where('id', $id)
            ->get('berita')
            ->row();

        if(!$berita){
            show_404();
        }

        $files_to_check = [];

        if(!empty($berita->gambar)){
            $files_to_check[] = $berita->gambar;
        }

        $gambar_tambahan = [];

        if($this->db->table_exists('berita_gambar')){
            $gambar_tambahan = $this->db
                ->where('berita_id', $id)
                ->get('berita_gambar')
                ->result();

            foreach($gambar_tambahan as $g){
                if(!empty($g->gambar)){
                    $files_to_check[] = $g->gambar;
                }
            }
        }

        $poster_file = !empty($berita->poster_gambar) ? $berita->poster_gambar : null;

        if($this->db->table_exists('berita_gambar')){
            $this->db->where('berita_id', $id);
            $this->db->delete('berita_gambar');
        }

        $this->db->where('id', $id);
        $this->db->delete('berita');

        foreach($files_to_check as $file){
            $this->delete_news_image_if_unused($file);
        }

        $this->delete_news_poster_file($poster_file);

        $this->session->set_flashdata('success', 'Berita dan file gambarnya berhasil dihapus.');
        redirect('berita');
    }

    public function edit($id){

        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }

        $data['berita'] = $this->db
            ->where('id', $id)
            ->get('berita')
            ->row();

        if(!$data['berita']){
            show_404();
        }

        $data['gambar_berita'] = $this->get_berita_gambar($id);
        $this->load->view('berita/edit', $data);
    }

    public function update($id){

        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }

        $berita = $this->db
            ->where('id', $id)
            ->get('berita')
            ->row();

        if(!$berita){
            show_404();
        }

        $judul = trim($this->input->post('judul'));
        $isi = trim($this->input->post('isi'));
        $status = $this->input->post('status_berita') ?: 'Draft';

        if(empty($judul) || empty($isi)){
            $this->session->set_flashdata('error', 'Judul dan isi berita wajib diisi.');
            redirect('berita/edit/'.$id);
        }

        $data = [
            'judul' => $judul,
            'isi'   => $isi
        ];

        if($this->db->field_exists('status_berita', 'berita')){
            $data['status_berita'] = $status;
        }

        if($this->db->field_exists('updated_at', 'berita')){
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        if($this->db->field_exists('published_at', 'berita')){
            if($status == 'Published' && empty($berita->published_at)){
                $data['published_at'] = date('Y-m-d H:i:s');
            }
        }

        $old_gambar = !empty($berita->gambar) ? $berita->gambar : null;

        if($this->input->post('hapus_gambar_utama') == '1'){
            $data['gambar'] = '';
        }

        if(!empty($_FILES['gambar']['name'])){
            $upload_path = './assets/news/';
            if(!is_dir($upload_path)){
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size']      = 8192;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if(!$this->upload->do_upload('gambar')){
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('berita/edit/'.$id);
            }

            $up = $this->upload->data();
            $data['gambar'] = $up['file_name'];
        }

        if($this->db->field_exists('poster_fit_mode', 'berita')){
            $data['poster_fit_mode'] = $this->input->post('poster_fit_mode') ?: 'cover';
        }

        if($this->db->field_exists('poster_focus', 'berita')){
            $data['poster_focus'] = $this->input->post('poster_focus') ?: 'center';
        }

        if($this->db->field_exists('poster_layout', 'berita')){
            $data['poster_layout'] = $this->input->post('poster_layout') ?: 'auto';
        }

        $this->db->where('id', $id);
        $this->db->update('berita', $data);

        if(array_key_exists('gambar', $data) && !empty($old_gambar) && $old_gambar != $data['gambar']){
            $this->delete_news_image_if_unused($old_gambar);
        }

        $this->upload_multi_berita($id);

        if(method_exists($this, 'generate_pamflet_file')){
            $this->generate_pamflet_file($id, true);
        }

        $this->session->set_flashdata('success', 'Berita berhasil diperbarui.');
        redirect('berita/edit/'.$id);
    }

    private function upload_multi_berita($berita_id){
        if(empty($_FILES['gambar_multi']['name'][0])){
            return;
        }

        $upload_path = './assets/news/';
        if(!is_dir($upload_path)){
            mkdir($upload_path, 0777, true);
        }

        $total = count($_FILES['gambar_multi']['name']);

        for($i = 0; $i < $total; $i++){
            if(empty($_FILES['gambar_multi']['name'][$i])){
                continue;
            }

            $_FILES['file_berita']['name']     = $_FILES['gambar_multi']['name'][$i];
            $_FILES['file_berita']['type']     = $_FILES['gambar_multi']['type'][$i];
            $_FILES['file_berita']['tmp_name'] = $_FILES['gambar_multi']['tmp_name'][$i];
            $_FILES['file_berita']['error']    = $_FILES['gambar_multi']['error'][$i];
            $_FILES['file_berita']['size']     = $_FILES['gambar_multi']['size'][$i];

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size']      = 8192;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if($this->upload->do_upload('file_berita')){
                $up = $this->upload->data();

                $last = $this->db
                    ->select_max('urutan')
                    ->where('berita_id', $berita_id)
                    ->get('berita_gambar')
                    ->row();

                $urutan = !empty($last->urutan) ? ((int)$last->urutan + 1) : 1;

                $this->db->insert('berita_gambar', [
                    'berita_id'  => $berita_id,
                    'gambar'     => $up['file_name'],
                    'urutan'     => $urutan,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    private function get_berita_gambar($berita_id){
        if(!$this->db->table_exists('berita_gambar')){
            return [];
        }

        return $this->db
            ->where('berita_id', $berita_id)
            ->order_by('urutan', 'ASC')
            ->order_by('id', 'ASC')
            ->get('berita_gambar')
            ->result();
    }

    private function berita_cover_and_gallery($berita){
        $images = [];

        if(!empty($berita->gambar)){
            $file = FCPATH.'assets/news/'.$berita->gambar;
            if(file_exists($file)){
                $images[] = $file;
            }
        }

        $galeri = $this->get_berita_gambar($berita->id);
        foreach($galeri as $g){
            $file = FCPATH.'assets/news/'.$g->gambar;
            if(file_exists($file) && !in_array($file, $images)){
                $images[] = $file;
            }
        }

        return $images;
    }

    private function poster_font($type = 'regular'){
        $fonts = [
            'regular' => [FCPATH.'assets/fonts/Poppins-Regular.ttf',FCPATH.'assets/fonts/Montserrat-Regular.ttf',FCPATH.'assets/fonts/Inter-Regular.ttf','C:/Windows/Fonts/arial.ttf','C:/Windows/Fonts/calibri.ttf'],
            'medium' => [FCPATH.'assets/fonts/Poppins-Medium.ttf',FCPATH.'assets/fonts/Poppins-SemiBold.ttf',FCPATH.'assets/fonts/Montserrat-Medium.ttf',FCPATH.'assets/fonts/Inter-Medium.ttf','C:/Windows/Fonts/arial.ttf','C:/Windows/Fonts/calibri.ttf'],
            'semibold' => [FCPATH.'assets/fonts/Poppins-SemiBold.ttf',FCPATH.'assets/fonts/Poppins-Bold.ttf',FCPATH.'assets/fonts/Montserrat-SemiBold.ttf',FCPATH.'assets/fonts/Inter-SemiBold.ttf','C:/Windows/Fonts/arialbd.ttf','C:/Windows/Fonts/calibrib.ttf'],
            'bold' => [FCPATH.'assets/fonts/Poppins-Bold.ttf',FCPATH.'assets/fonts/Montserrat-Bold.ttf',FCPATH.'assets/fonts/Inter-Bold.ttf','C:/Windows/Fonts/arialbd.ttf','C:/Windows/Fonts/calibrib.ttf'],
            'extrabold' => [FCPATH.'assets/fonts/Poppins-ExtraBold.ttf',FCPATH.'assets/fonts/Poppins-Bold.ttf',FCPATH.'assets/fonts/Montserrat-ExtraBold.ttf',FCPATH.'assets/fonts/Montserrat-Bold.ttf','C:/Windows/Fonts/arialbd.ttf','C:/Windows/Fonts/calibrib.ttf']
        ];

        foreach($fonts[$type] as $font){
            if(file_exists($font)){
                return $font;
            }
        }

        return null;
    }

    private function text_lines($text, $font, $size, $maxWidth, $maxLines){
        $words = preg_split('/\s+/', trim($text));
        $lines = [];
        $current = '';

        foreach($words as $word){
            $test = trim($current.' '.$word);
            $box = imagettfbbox($size, 0, $font, $test);
            $width = $box[2] - $box[0];

            if($width > $maxWidth && $current !== ''){
                $lines[] = $current;
                $current = $word;
            } else {
                $current = $test;
            }

            if(count($lines) >= $maxLines){
                break;
            }
        }

        if($current !== '' && count($lines) < $maxLines){
            $lines[] = $current;
        }

        if(count($lines) == $maxLines && count($words) > 0){
            $last = $lines[$maxLines - 1];
            while(strlen($last) > 0){
                $box = imagettfbbox($size, 0, $font, $last.'...');
                if(($box[2] - $box[0]) <= $maxWidth){
                    $lines[$maxLines - 1] = $last.'...';
                    break;
                }
                $last = substr($last, 0, -1);
            }
        }

        return $lines;
    }

    private function draw_text($canvas, $text, $size, $x, $y, $color, $font, $maxWidth = null, $maxLines = 1, $lineHeight = 1.3){
        if(!$font){
            imagestring($canvas, 5, $x, $y, $text, $color);
            return $y + 18;
        }

        if(!$maxWidth){
            imagettftext($canvas, $size, 0, $x, $y, $color, $font, $text);
            return $y + $size + 8;
        }

        $lines = $this->text_lines($text, $font, $size, $maxWidth, $maxLines);
        foreach($lines as $line){
            imagettftext($canvas, $size, 0, $x, $y, $color, $font, $line);
            $y += (int)($size * $lineHeight);
        }

        return $y;
    }

    private function generate_pamflet_file($id, $force = false){
        $berita = $this->db->where('id', $id)->get('berita')->row();
        if(!$berita){ return false; }

        $poster_dir = FCPATH.'assets/news/poster/';
        if(!is_dir($poster_dir)){ mkdir($poster_dir, 0777, true); }

        if(!$force && !empty($berita->poster_gambar) && file_exists($poster_dir.$berita->poster_gambar)){
            return $berita->poster_gambar;
        }

        if(!function_exists('imagecreatetruecolor')){ return false; }

        $w = 1080; $h = 1350;
        $canvas = imagecreatetruecolor($w, $h);
        $greenDark = imagecolorallocate($canvas, 6, 78, 59);
        $green = imagecolorallocate($canvas, 22, 163, 74);
        $greenSoft = imagecolorallocate($canvas, 236, 253, 245);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        $slate = imagecolorallocate($canvas, 15, 23, 42);
        $muted = imagecolorallocate($canvas, 71, 85, 105);
        $yellow = imagecolorallocate($canvas, 250, 204, 21);

        imagefilledrectangle($canvas, 0, 0, $w, $h, $white);
        imagefilledrectangle($canvas, 0, 0, $w, 260, $greenDark);
        imagefilledellipse($canvas, 920, 90, 360, 360, $green);
        imagefilledellipse($canvas, 180, 220, 260, 260, $green);
        imagefilledrectangle($canvas, 0, 260, $w, 290, $yellow);

        $fontBold = $this->poster_font('bold');
        $fontSemi = $this->poster_font('semibold');
        $fontReg = $this->poster_font('regular');

        $this->draw_text($canvas, 'MAN 3 BANJAR', 36, 70, 86, $white, $fontBold, 720, 1);
        $this->draw_text($canvas, 'BERITA MADRASAH', 28, 70, 140, $greenSoft, $fontSemi, 720, 1);
        $this->draw_text($canvas, date('d M Y', strtotime($berita->created_at)), 22, 70, 198, $greenSoft, $fontReg, 720, 1);

        $images = $this->berita_cover_and_gallery($berita);
        $photoX = 70; $photoY = 330; $photoW = 940; $photoH = 480;
        imagefilledrectangle($canvas, $photoX, $photoY, $photoX+$photoW, $photoY+$photoH, $greenSoft);
        if(!empty($images)){ $this->draw_cover_image($canvas, $images[0], $photoX, $photoY, $photoW, $photoH, $berita->poster_focus ?? 'center'); }

        $this->draw_text($canvas, strtoupper($berita->judul), 42, 70, 900, $slate, $fontBold, 940, 3, 1.25);
        $summary = strip_tags($berita->isi);
        $summary = preg_replace('/\s+/', ' ', $summary);
        $this->draw_text($canvas, $summary, 25, 70, 1070, $muted, $fontReg, 940, 4, 1.35);

        imagefilledrectangle($canvas, 70, 1250, 1010, 1260, $green);
        $this->draw_text($canvas, 'Portal Digital MAN 3 Banjar', 24, 70, 1310, $greenDark, $fontSemi, 940, 1);

        $filename = 'pamflet-berita-'.$id.'-'.time().'.jpg';
        imagejpeg($canvas, $poster_dir.$filename, 92);
        imagedestroy($canvas);

        if(!empty($berita->poster_gambar)){ $this->delete_news_poster_file($berita->poster_gambar); }

        $this->db->where('id', $id)->update('berita', ['poster_gambar' => $filename, 'poster_generated_at' => date('Y-m-d H:i:s')]);
        return $filename;
    }

    private function draw_cover_image($canvas, $file, $x, $y, $w, $h, $focus = 'center'){
        $info = getimagesize($file);
        if(!$info){ return false; }

        if($info['mime'] == 'image/png'){
            $src = imagecreatefrompng($file);
            imagealphablending($src, true);
            imagesavealpha($src, true);
        } elseif($info['mime'] == 'image/jpeg'){
            $src = imagecreatefromjpeg($file);
        } elseif($info['mime'] == 'image/webp' && function_exists('imagecreatefromwebp')){
            $src = imagecreatefromwebp($file);
        } else { return false; }

        if(!$src){ return false; }
        $sw = imagesx($src); $sh = imagesy($src);
        if($sw <= 0 || $sh <= 0){ imagedestroy($src); return false; }

        $ratio = max($w / $sw, $h / $sh);
        $nw = (int)($sw * $ratio); $nh = (int)($sh * $ratio);
        $sx = (int)(($nw - $w) / 2 / $ratio);
        $sy = (int)(($nh - $h) / 2 / $ratio);

        imagecopyresampled($canvas, $src, $x, $y, $sx, $sy, $w, $h, (int)($w/$ratio), (int)($h/$ratio));
        imagedestroy($src);
        return true;
    }

    public function regenerate_pamflet($id){
        if(!$this->session->userdata('logged_in')){ redirect('auth'); }
        $result = $this->generate_pamflet_file($id, true);
        $this->session->set_flashdata($result ? 'success' : 'error', $result ? 'Pamflet berita berhasil dibuat ulang.' : 'Pamflet gagal dibuat.');
        redirect('berita');
    }

    public function download_pamflet($id){
        if(!$this->session->userdata('logged_in')){ redirect('auth'); }
        $poster = $this->generate_pamflet_file($id, false);
        if(!$poster){ show_404(); }
        $file = FCPATH.'assets/news/poster/'.$poster;
        if(!file_exists($file)){ $poster = $this->generate_pamflet_file($id, true); $file = FCPATH.'assets/news/poster/'.$poster; }
        $this->load->helper('download');
        $berita = $this->db->where('id', $id)->get('berita')->row();
        $filename = 'pamflet_'.url_title($berita->judul, '-', true).'.jpg';
        force_download($filename, file_get_contents($file));
    }

    public function delete_gambar($id){
        if(!$this->session->userdata('logged_in')){ redirect('auth'); }
        $gambar = $this->db->where('id', $id)->get('berita_gambar')->row();
        if(!$gambar){ show_404(); }
        $berita_id = $gambar->berita_id;
        $filename = $gambar->gambar;
        $this->db->where('id', $id)->delete('berita_gambar');
        $this->delete_news_image_if_unused($filename);
        if(method_exists($this, 'generate_pamflet_file')){ $this->generate_pamflet_file($berita_id, true); }
        $this->session->set_flashdata('success', 'Foto kegiatan berhasil dihapus.');
        redirect('berita/edit/'.$berita_id);
    }

    private function delete_news_image_if_unused($filename){
        if(empty($filename)){ return; }
        $filename = basename($filename);
        $used_main = $this->db->where('gambar', $filename)->count_all_results('berita');
        $used_extra = 0;
        if($this->db->table_exists('berita_gambar')){ $used_extra = $this->db->where('gambar', $filename)->count_all_results('berita_gambar'); }
        if($used_main > 0 || $used_extra > 0){ return; }
        $file = FCPATH.'assets/news/'.$filename;
        if(file_exists($file) && is_file($file)){ unlink($file); }
    }

    private function delete_news_poster_file($filename){
        if(empty($filename)){ return; }
        $filename = basename($filename);
        $file = FCPATH.'assets/news/poster/'.$filename;
        if(file_exists($file) && is_file($file)){ unlink($file); }
    }
}
