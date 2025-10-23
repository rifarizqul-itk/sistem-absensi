-- Membuat database jika belum ada
CREATE DATABASE IF NOT EXISTS db_absensi_mahasiswa;
USE db_absensi_mahasiswa;

-- Menghapus tabel lama jika ada
DROP TABLE IF EXISTS absensi, users, mahasiswa, mata_kuliah, dosen, program_studi;

-- Membuat tabel-tabel
CREATE TABLE absensi (
  id_absensi int NOT NULL,
  id_mahasiswa int NOT NULL,
  id_mk int NOT NULL,
  tanggal_absensi date NOT NULL,
  status enum('Hadir','Izin','Sakit','Alpa') NOT NULL DEFAULT 'Alpa',
  keterangan text
);

CREATE TABLE dosen (
  id_dosen int NOT NULL,
  nip varchar(20) NOT NULL,
  nama_dosen varchar(100) NOT NULL
);

CREATE TABLE mahasiswa (
  id_mahasiswa int NOT NULL,
  nim varchar(20) NOT NULL,
  nama_lengkap varchar(100) NOT NULL,
  id_prodi int DEFAULT NULL
);

CREATE TABLE mata_kuliah (
  id_mk int NOT NULL,
  kode_mk varchar(20) NOT NULL,
  nama_mk varchar(100) NOT NULL,
  id_prodi int DEFAULT NULL,
  id_dosen_pengampu int DEFAULT NULL
);

CREATE TABLE program_studi (
  id_prodi int NOT NULL,
  nama_prodi varchar(100) NOT NULL
);

-- Tabel users dengan kolom password (bukan password_hash)
CREATE TABLE users (
  id_user INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL, -- Diubah dari password_hash
  role enum('dosen','mahasiswa') NOT NULL,
  id_dosen INT DEFAULT NULL,
  id_mahasiswa INT DEFAULT NULL
);

-- Memasukkan data awal
INSERT INTO absensi (id_absensi, id_mahasiswa, id_mk, tanggal_absensi, status, keterangan) VALUES
(1, 1, 1, '2025-10-22', 'Sakit', ''),
(3, 1, 6, '2025-10-22', 'Hadir', NULL),
(4, 5, 3, '2025-10-22', 'Hadir', NULL),
(5, 5, 1, '2025-10-23', 'Alpa', '');

INSERT INTO dosen (id_dosen, nip, nama_dosen) VALUES
(1, '198503152010121002', 'Dwi Nur Amalia'), -- Menggunakan NIP acak dari contoh sebelumnya
(2, '199211082015012001', 'Arif Wicaksono Septyanto'), -- Menggunakan NIP acak dari contoh sebelumnya
(3, '198907242014051003', 'Henokh Lugo Hariyanto'); -- Menggunakan NIP acak dari contoh sebelumnya

INSERT INTO mahasiswa (id_mahasiswa, nim, nama_lengkap, id_prodi) VALUES
(1, '10241001', 'Aan Mardiah', 1),
(2, '10241002', 'Ade Putri Amanda', 1),
(3, '10241003', 'Adelia Cyntia Renata', 1),
(4, '10241004', 'Adelia Isra Ekaputri', 1),
(5, '10241005', 'Agus Liberty Purba', 1),
(6, '10241007', 'Aliya Labibah', 1),
(7, '10241008', 'Alya Auralia', 1),
(8, '10241009', 'Anastasya Salsabila Khoirunnisa', 1),
(9, '10241010', 'Andika Putra Pratama', 1),
(10, '10241011', 'Annisa Dwi Lestari Sonny', 1);

INSERT INTO mata_kuliah (id_mk, kode_mk, nama_mk, id_prodi, id_dosen_pengampu) VALUES
(1, 'SI01', 'Basis Data', 1, 2),
(2, 'SI02', 'Matematika Diskrit', 1, 3),
(3, 'SI03', 'Pemrograman Lanjut', 1, 2),
(4, 'SI04', 'Desain Proses Bisnis', 1, 1),
(5, 'SI05', 'Interaksi Manusia dan Komputer', 1, 1),
(6, 'SI06', 'Statistika Sistem Informasi', 1, 3);

INSERT INTO program_studi (id_prodi, nama_prodi) VALUES
(2, 'Informatika'),
(1, 'Sistem Informasi'),
(3, 'Teknik Elektro');

-- Memasukkan data user dengan password teks biasa (nama_depan + 123)
INSERT INTO users (username, password, role, id_dosen, id_mahasiswa) VALUES
('henokh', 'henokh123', 'dosen', 3, NULL),
('arif', 'arif123', 'dosen', 2, NULL),
('amal', 'amal123', 'dosen', 1, NULL),
('aan', 'aan123', 'mahasiswa', NULL, 1),
('putri', 'putri123', 'mahasiswa', NULL, 2),
('renata', 'renata123', 'mahasiswa', NULL, 3),
('adel', 'adel123', 'mahasiswa', NULL, 4),
('agus', 'agus123', 'mahasiswa', NULL, 5),
('labibah', 'labibah123', 'mahasiswa', NULL, 6),
('alya', 'alya123', 'mahasiswa', NULL, 7),
('tasya', 'tasya123', 'mahasiswa', NULL, 8),
('andika', 'andika123', 'mahasiswa', NULL, 9),
('annisa', 'annisa123', 'mahasiswa', NULL, 10);

-- Menambahkan Primary Key, Auto Increment, Unique Key, Index, dan Foreign Key
ALTER TABLE absensi ADD PRIMARY KEY (id_absensi);
ALTER TABLE absensi ADD UNIQUE KEY unik_absen (id_mahasiswa,id_mk,tanggal_absensi);
ALTER TABLE absensi ADD KEY id_mk (id_mk);
ALTER TABLE absensi MODIFY id_absensi int NOT NULL AUTO_INCREMENT;

ALTER TABLE dosen ADD PRIMARY KEY (id_dosen);
ALTER TABLE dosen ADD UNIQUE KEY nip (nip);
ALTER TABLE dosen MODIFY id_dosen int NOT NULL AUTO_INCREMENT;

ALTER TABLE mahasiswa ADD PRIMARY KEY (id_mahasiswa);
ALTER TABLE mahasiswa ADD UNIQUE KEY nim (nim);
ALTER TABLE mahasiswa ADD KEY id_prodi (id_prodi);
ALTER TABLE mahasiswa MODIFY id_mahasiswa int NOT NULL AUTO_INCREMENT;

ALTER TABLE mata_kuliah ADD PRIMARY KEY (id_mk);
ALTER TABLE mata_kuliah ADD UNIQUE KEY kode_mk (kode_mk);
ALTER TABLE mata_kuliah ADD KEY id_prodi (id_prodi);
ALTER TABLE mata_kuliah ADD KEY id_dosen_pengampu (id_dosen_pengampu);
ALTER TABLE mata_kuliah MODIFY id_mk int NOT NULL AUTO_INCREMENT;

ALTER TABLE program_studi ADD PRIMARY KEY (id_prodi);
ALTER TABLE program_studi ADD UNIQUE KEY nama_prodi (nama_prodi);
ALTER TABLE program_studi MODIFY id_prodi int NOT NULL AUTO_INCREMENT;

ALTER TABLE users ADD UNIQUE KEY username (username);
ALTER TABLE users ADD UNIQUE KEY id_dosen (id_dosen);
ALTER TABLE users ADD UNIQUE KEY id_mahasiswa (id_mahasiswa);

ALTER TABLE absensi ADD CONSTRAINT absensi_ibfk_1 FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa (id_mahasiswa) ON DELETE CASCADE;
ALTER TABLE absensi ADD CONSTRAINT absensi_ibfk_2 FOREIGN KEY (id_mk) REFERENCES mata_kuliah (id_mk) ON DELETE CASCADE;

ALTER TABLE mahasiswa ADD CONSTRAINT mahasiswa_ibfk_1 FOREIGN KEY (id_prodi) REFERENCES program_studi (id_prodi) ON DELETE SET NULL;

ALTER TABLE mata_kuliah ADD CONSTRAINT mata_kuliah_ibfk_1 FOREIGN KEY (id_prodi) REFERENCES program_studi (id_prodi);
ALTER TABLE mata_kuliah ADD CONSTRAINT mata_kuliah_ibfk_2 FOREIGN KEY (id_dosen_pengampu) REFERENCES dosen (id_dosen) ON DELETE SET NULL;

ALTER TABLE users ADD CONSTRAINT users_ibfk_1 FOREIGN KEY (id_dosen) REFERENCES dosen (id_dosen) ON DELETE CASCADE;
ALTER TABLE users ADD CONSTRAINT users_ibfk_2 FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa (id_mahasiswa) ON DELETE CASCADE;