-- ==============================================================================
-- SQL Patch Sinkronisasi Data Siswa Kelas XII (Berdasarkan Emis 4.0 - TKA)
-- MAN 3 BANJAR - Tahun Ajaran 2026/2027
-- Generated: 2026-09-15
-- ==============================================================================

-- 1. Tambah Siswa Baru: Noor Mila Safitri ke Kelas XII A
INSERT INTO siswa (
    id, nama_lengkap, nisn, nik, tempat_lahir, tanggal_lahir, jk, alamat, no_hp, 
    nama_ayah, nama_ibu, status_siswa, created_at
) VALUES (
    1145, 'Noor Mila Safitri', '0097828190', '6372025203090001', 'Basarang', '2009-03-12', 'P',
    'JL. GOLF PERUM WELLA MANDIRI SYAMSUDIN NOOR, LANDASAN ULIN, KOTA BANJARBARU, KALIMANTAN SELATAN, 70724, 70724',
    '', 'MASRANI', 'Misdawati', 'Aktif', NOW()
) ON DUPLICATE KEY UPDATE 
    nama_lengkap = VALUES(nama_lengkap),
    nik = VALUES(nik),
    tempat_lahir = VALUES(tempat_lahir),
    tanggal_lahir = VALUES(tanggal_lahir),
    nama_ayah = VALUES(nama_ayah),
    nama_ibu = VALUES(nama_ibu),
    status_siswa = 'Aktif';

-- Daftarkan ke rombel XII A (ID 56) tahun 2026/2027
INSERT INTO siswa_kelas (siswa_id, kelas_id, tahun_ajaran, status, created_at)
SELECT 1145, 56, '2026/2027', 'Aktif', NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM siswa_kelas WHERE siswa_id = 1145 AND kelas_id = 56 AND tahun_ajaran = '2026/2027'
);

-- 2. Update Siswa Tidak Ada di EMIS: M. ANDRE MAULANA (NISN: 0081178808)
DELETE FROM siswa_kelas WHERE siswa_id = 898 AND kelas_id = 58 AND tahun_ajaran = '2026/2027';
UPDATE siswa SET status_siswa = 'Pindah', updated_at = NOW() WHERE id = 898;

-- 3. Pindah Rombel Siswa: WAFA MUFIDA (NISN: 0098850707) dari XII E (60) ke XII B (57)
UPDATE siswa_kelas 
SET kelas_id = 57 
WHERE siswa_id = (SELECT id FROM siswa WHERE nisn = '0098850707') 
  AND tahun_ajaran = '2026/2027';

-- 4. Pembaruan Atribut Siswa Berdasarkan EMIS 4.0
UPDATE siswa SET nik = '6371036609090004', updated_at = NOW() WHERE nisn = '0098058432'; -- RITA ZAHRO
UPDATE siswa SET nama_ayah = 'AKHMAD FAHRANI', updated_at = NOW() WHERE nisn = '0091783443'; -- RAINA NOOR AFNI
UPDATE siswa SET nik = '6303035807080001', updated_at = NOW() WHERE nisn = '3084113402'; -- AFIFA SYAHIRA
UPDATE siswa SET nik = '6303036101090001', updated_at = NOW() WHERE nisn = '3090982984'; -- KHALIFATURRAHMAH
UPDATE siswa SET nama_ayah = 'MURHAN', updated_at = NOW() WHERE nisn = '0083428734'; -- MUHAMMAD FAHRIAN
UPDATE siswa SET nama_ayah = 'YAMANI', updated_at = NOW() WHERE nisn = '0094139743'; -- AGISNI NURIL KHAIRA
UPDATE siswa SET nik = '6372040408090003', tempat_lahir = 'JAKARTA', updated_at = NOW() WHERE nisn = '0095768449'; -- MAULANA YUSUF
UPDATE siswa SET nama_ayah = 'AKHMAD FAUZI', updated_at = NOW() WHERE nisn = '0074805216'; -- AKHMAD FAISAL
UPDATE siswa SET nama_ayah = 'FARIED AKBAR', updated_at = NOW() WHERE nisn = '0085428729'; -- RASHA ISLAMI PASHA
UPDATE siswa SET nama_ayah = 'FAUZIAN NOR', updated_at = NOW() WHERE nisn = '3095972159'; -- RIDHO MAULANA
UPDATE siswa SET no_hp = '628771654212635', updated_at = NOW() WHERE nisn = '0094295616'; -- AISYAH PUTERI NURAINI
UPDATE siswa SET nama_ayah = 'AHMAD FUADI', updated_at = NOW() WHERE nisn = '3098515494'; -- SHERA AZZAHRA
UPDATE siswa SET nama_ayah = 'MUSTI', updated_at = NOW() WHERE nisn = '0086889959'; -- NAUFA NOR AZHORA
UPDATE siswa SET nama_ayah = 'M. YUSUF', updated_at = NOW() WHERE nisn = '0088366151'; -- M. FAUZAN RAHMAN
UPDATE siswa SET tempat_lahir = 'BANJAR', nama_ayah = 'HAIRIL ANWAR', updated_at = NOW() WHERE nisn = '3093752652'; -- ISNAWATI
UPDATE siswa SET nama_ayah = 'ZAINAL KIFLI', updated_at = NOW() WHERE nisn = '0072680381'; -- M. ALFIANOR
