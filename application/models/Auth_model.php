<?php
class Auth_model extends CI_Model {

    public function login($username, $password, $raw_password = ''){
        $user = $this->db
            ->where('username', $username)
            ->group_start()
                ->where('password', $password)
                ->or_where('password', $raw_password)
            ->group_end()
            ->get('users')
            ->row();

        if($user){
            return $user;
        }

        $user_by_uname = $this->db->where('username', $username)->get('users')->row();
        if($user_by_uname && !empty($user_by_uname->password)){
            if(password_verify($raw_password, $user_by_uname->password)){
                return $user_by_uname;
            }
        }

        // Fallback: Jika tidak ketemu di users, cek tabel ptk (untuk login guru murni tanpa akun users)
        if($this->db->table_exists('ptk')){
            $ptk = $this->db
                ->where('username', $username)
                ->where('password', $password)
                ->where('status_aktif', 'Aktif')
                ->get('ptk')
                ->row();

            if($ptk){
                // Kembalikan objek tiruan (dummy user object) agar kompatibel dengan sistem sesi auth.php
                $u = new stdClass();
                $u->id = null; // Guru tidak lagi punya record di tabel users
                $u->username = $ptk->username;
                $u->role = 'guru';
                $u->ptk_id = $ptk->id;
                return $u;
            }
        }

        if($this->db->table_exists('siswa')){
            // Pencarian UTAMA: Keduanya adalah NISN (Username = NISN, Password = NISN)
            $siswa = $this->db
                ->where('nisn', $username)
                ->where('nisn', $raw_password)
                ->group_start()
                    ->where('status_siswa', 'Aktif')
                    ->or_where('status_siswa', 'Baru')
                ->group_end()
                ->get('siswa')
                ->row();

            // Fallback: Jika menggunakan NIS untuk Username & Password
            if(!$siswa){
                $siswa = $this->db
                    ->where('nis', $username)
                    ->where('nis', $raw_password)
                    ->group_start()
                        ->where('status_siswa', 'Aktif')
                        ->or_where('status_siswa', 'Baru')
                    ->group_end()
                    ->get('siswa')
                    ->row();
            }

            if($siswa){
                $u = new stdClass();
                $u->id = null; 
                $u->username = $siswa->nisn ?: $siswa->nis;
                $u->role = 'siswa';
                $u->ptk_id = null;
                $u->siswa_id = $siswa->id;
                return $u;
            }
        }

        return null;
    }
}