# 🚀 PANDUAN DEPLOY WEBSITE & PMB ONLINE KE CPANEL DOMAINESIA

Paket ini adalah aplikasi mandiri (*standalone*) berisi **Website Profil Madrasah + Portal PMB / PPDB Online 24/7** yang siap di-upload ke Shared Hosting cPanel DomaiNesia.

---

## 📦 1. Persiapan File ZIP
1. Kompres seluruh isi folder `dist_web_madrasah` menjadi satu file ZIP (misal: `web_man3banjar_cloud.zip`).
2. *Catatan*: Pastikan `index.php`, `.htaccess`, folder `application`, `system`, `assets`, dan `uploads` berada di akar file ZIP.

---

## 🗄️ 2. Langkah di cPanel DomaiNesia

### A. Buat Database MySQL
1. Buka cPanel DomaiNesia $\rightarrow$ menu **MySQL Database Wizard**.
2. Buat database baru (misal: `u12345_webman3`).
3. Buat user database baru dan catat passwordnya.
4. Berikan hak akses **ALL PRIVILEGES** ke user tersebut.
5. Buka menu **phpMyAdmin**, pilih database yang baru dibuat, lalu klik **Import**.
6. Pilih file `database/db_web_madrasah_cloud.sql` dan klik **Go / Kirim**.

---

### B. Upload File Website
1. Buka cPanel DomaiNesia $\rightarrow$ menu **File Manager**.
2. Buka folder `public_html` (atau folder subdomain jika menggunakan subdomain seperti `ppdb.man3banjar.sch.id`).
3. Klik tombol **Upload**, lalu pilih file `web_man3banjar_cloud.zip`.
4. Setelah selesai upload, klik kanan file ZIP tersebut lalu pilih **Extract**.
5. Pastikan hak akses folder `uploads/` bernilai `755` atau `777` agar upload berkas pendaftar dan foto berita lancar.

---

### C. Sesuaikan Konfigurasi Database
1. Di File Manager, buka file `application/config/database.php`.
2. Sesuaikan kredensial database:
```php
'username' => 'u12345_userweb',
'password' => 'PasswordDatabaseAnda',
'database' => 'u12345_webman3',
```
3. Buka file `application/config/config.php`:
   - `base_url` akan otomatis mendeteksi nama domain Anda.
   - Pastikan `ppdb_api_key` sama dengan yang ada di LabSys lokal:
```php
$config['ppdb_api_key'] = 'LABSYS_SYNC_SECRET_KEY_MAN3BANJAR_2026';
```

---

## 🔑 3. Akses Login Admin & Fitur

* **Website Publik**: `https://man3banjar.sch.id/`
* **Portal PPDB Online 24/7**: `https://man3banjar.sch.id/ppdb`
* **Login Admin Web & PMB Cloud**: `https://man3banjar.sch.id/login`
  * **Username default**: `admin`
  * **Password default**: `admin123` *(Segera ganti setelah login)*

---

## 🔄 4. Sinkronisasi Data ke Server Lokal LabSys

1. Buka LabSys di server lokal sekolah $\rightarrow$ menu **PPDB**.
2. Klik tombol biru **`"📥 Tarik Pendaftar dari DomaiNesia"`**.
3. Masukkan URL endpoint: `https://man3banjar.sch.id/api/ppdb/sync` (sudah terisi otomatis).
4. Klik **Mulai Sinkronisasi**.
5. Seluruh biodata calon siswa baru beserta file berkas (KK, Akta, Ijazah, Foto) akan otomatis diunduh dan tersimpan rapi di LabSys server lokal!
