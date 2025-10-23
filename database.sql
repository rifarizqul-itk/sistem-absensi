-- Active: 1759828269793@@127.0.0.1@3306@db_absensi_mahasiswa
-- Membuat database jika belum ada
CREATE DATABASE IF NOT EXISTS db_absensi_mahasiswa;
USE db_absensi_mahasiswa;

-- Menghapus tabel lama jika ada
DROP TABLE IF EXISTS absensi, users, mahasiswa, mata_kuliah, dosen, program_studi;

-- Membuat tabel-tabel
CREATE TABLE absensi (
  id_absensi int AUTO_INCREMENT PRIMARY KEY,
  id_mahasiswa int NOT NULL,
  id_mk int NOT NULL,
  tanggal_absensi date NOT NULL,
  status enum('Hadir','Izin','Sakit','Alpa') NOT NULL DEFAULT 'Alpa',
  keterangan TEXT NULL,
  FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa) ON DELETE CASCADE,
  FOREIGN KEY (id_mk) REFERENCES mata_kuliah(id_mk) ON DELETE CASCADE,
  UNIQUE KEY unik_absen (id_mahasiswa, id_mk, tanggal_absensi)
);

CREATE TABLE dosen (
  id_dosen int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  nip varchar(20) NOT NULL,
  nama_dosen varchar(100) NOT NULL
);

CREATE TABLE mahasiswa (
  id_mahasiswa int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  nim varchar(20) NOT NULL UNIQUE,
  nama_lengkap varchar(100) NOT NULL
);

CREATE TABLE mata_kuliah (
  id_mk int AUTO_INCREMENT PRIMARY KEY,
  kode_mk varchar(20) NOT NULL UNIQUE,
  nama_mk varchar(100) NOT NULL,
  id_dosen_pengampu int DEFAULT NULL,
  FOREIGN KEY (id_dosen_pengampu) REFERENCES dosen(id_dosen) ON DELETE SET NULL
);

CREATE TABLE users (
  id_user INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  login_id VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role enum('superadmin','dosen','mahasiswa') NOT NULL,
  id_dosen INT DEFAULT NULL,
  id_mahasiswa INT DEFAULT NULL
);

CREATE TABLE peserta_mk (
  id_peserta INT PRIMARY KEY AUTO_INCREMENT,
  id_mahasiswa INT NOT NULL,
  id_mk INT NOT NULL,
  FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa) ON DELETE CASCADE,
  FOREIGN KEY (id_mk) REFERENCES mata_kuliah(id_mk) ON DELETE CASCADE,
  UNIQUE KEY unik_peserta (id_mahasiswa, id_mk)
)

-- Memasukkan data awal
INSERT INTO absensi (id_absensi, id_mahasiswa, id_mk, tanggal_absensi, status, keterangan) VALUES
(1, 1, 1, '2025-10-22', 'Sakit', ''),
(3, 1, 6, '2025-10-22', 'Hadir', NULL),
(4, 5, 3, '2025-10-22', 'Hadir', NULL),
(5, 5, 1, '2025-10-23', 'Alpa', '');

INSERT INTO dosen (nip, nama_dosen) VALUES
('198503152010121002', 'Dwi Nur Amalia'), -- Menggunakan NIP acak dari contoh sebelumnya
('199211082015012001', 'Arif Wicaksono Septyanto'), -- Menggunakan NIP acak dari contoh sebelumnya
('198907242014051003', 'Henokh Lugo Hariyanto'); -- Menggunakan NIP acak dari contoh sebelumnya

INSERT INTO mahasiswa (nim, nama_lengkap) VALUES
('10241001', 'Aan Mardiah'),
('10241002', 'Ade Putri Amanda'),
('10241003', 'Adelia Cyntia Renata'),
('10241004', 'Adelia Isra Ekaputri'),
('10241005', 'Agus Liberty Purba'),
('10241007', 'Aliya Labibah'),
('10241008', 'Alya Auralia'),
('10241009', 'Anastasya Salsabila Khoirunnisa'),
('10241010', 'Andika Putra Pratama'),
('10241011', 'Annisa Dwi Lestari Sonny');

INSERT INTO mata_kuliah (kode_mk, nama_mk, id_dosen_pengampu) VALUES
('SI01', 'Basis Data', 2),
('SI02', 'Matematika Diskrit', 3),
('SI03', 'Pemrograman Lanjut', 2),
('SI04', 'Desain Proses Bisnis', 1),
('SI05', 'Interaksi Manusia dan Komputer', 1),
('SI06', 'Statistika Sistem Informasi', 3);

INSERT INTO program_studi (id_prodi, nama_prodi) VALUES
(2, 'Informatika'),
(1, 'Sistem Informasi'),
(3, 'Teknik Elektro');

-- Memasukkan data user dengan password teks biasa (nama_depan + 123)
INSERT INTO users (login_id, password_hash, role, id_dosen, id_mahasiswa) VALUES
('superadmin', MD5('superadmin123'), 'superadmin', NULL, NULL),
('henokh.lugo@lecturer.itk.ac.id', MD5('henokh123'), 'dosen', 3, NULL),
('arif.septyanto@lecturer.itk.ac.id', MD5('arif123'), 'dosen', 2, NULL),
('amalia@lecturer.itk.ac.id', MD5('amal123'), 'dosen', 1, NULL),
('10241001@student.itk.ac.id', MD5('aan123'), 'mahasiswa', NULL, 1),
('10241002@student.itk.ac.id', MD5('putri123'), 'mahasiswa', NULL, 2),
('10241003@student.itk.ac.id', MD5('renata123'), 'mahasiswa', NULL, 3),
('10241004@student.itk.ac.id', MD5('adel123'), 'mahasiswa', NULL, 4),
('10241005@student.itk.ac.id', MD5('agus123'), 'mahasiswa', NULL, 5),
('10241007@student.itk.ac.id', MD5('labibah123'), 'mahasiswa', NULL, 6),
('10241008@student.itk.ac.id', MD5('alya123'), 'mahasiswa', NULL, 7),
('10241009@student.itk.ac.id', MD5('tasya123'), 'mahasiswa', NULL, 8),
('10241010@student.itk.ac.id', MD5('andika123'), 'mahasiswa', NULL, 9),
('10241011@student.itk.ac.id', MD5('annisa123'), 'mahasiswa', NULL, 10);