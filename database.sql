-- Active: 1759828269793@@127.0.0.1@3306@db_absensi_mahasiswa
-- Buat database baru (jalankan di phpMyAdmin/Laragon)
CREATE DATABASE db_absensi_mahasiswa;
USE db_absensi_mahasiswa;

-- 1. Tabel Program Studi (Normalisasi)
CREATE TABLE program_studi (
    id_prodi INT AUTO_INCREMENT PRIMARY KEY,
    nama_prodi VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 2. Tabel Dosen (Biodata)
CREATE TABLE dosen (
    id_dosen INT AUTO_INCREMENT PRIMARY KEY,
    nip VARCHAR(20) NOT NULL UNIQUE,
    nama_dosen VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- 3. Tabel Mahasiswa (Biodata - diinput Dosen)
CREATE TABLE mahasiswa (
    id_mahasiswa INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    id_prodi INT,
    FOREIGN KEY (id_prodi) REFERENCES program_studi(id_prodi) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. Tabel Users (Akun Login)
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(32) NOT NULL, -- Diubah ke VARCHAR(32) untuk MD5
    role ENUM('dosen', 'mahasiswa') NOT NULL,
    id_dosen INT NULL UNIQUE,
    id_mahasiswa INT NULL UNIQUE,
    FOREIGN KEY (id_dosen) REFERENCES dosen(id_dosen) ON DELETE CASCADE,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Tabel Mata Kuliah (Dikelola Dosen)
CREATE TABLE mata_kuliah (
    id_mk INT AUTO_INCREMENT PRIMARY KEY,
    kode_mk VARCHAR(20) NOT NULL UNIQUE,
    nama_mk VARCHAR(100) NOT NULL,
    id_prodi INT,
    id_dosen_pengampu INT,
    FOREIGN KEY (id_prodi) REFERENCES program_studi(id_prodi),
    FOREIGN KEY (id_dosen_pengampu) REFERENCES dosen(id_dosen) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 6. Tabel Absensi (Core)
CREATE TABLE absensi (
    id_absensi INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_mk INT NOT NULL,
    tanggal_absensi DATE NOT NULL,
    status ENUM('Hadir', 'Izin', 'Sakit', 'Alpa') NOT NULL DEFAULT 'Alpa',
    keterangan TEXT NULL,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa) ON DELETE CASCADE,
    FOREIGN KEY (id_mk) REFERENCES mata_kuliah(id_mk) ON DELETE CASCADE,
    -- Mencegah mahasiswa absen 2x di matkul yang sama di hari yang sama
    UNIQUE KEY unik_absen (id_mahasiswa, id_mk, tanggal_absensi)
) ENGINE=InnoDB;

-- --- DATA AWAL (PENTING UNTUK LOGIN) ---

-- 1. Isi data Program Studi
INSERT INTO program_studi (nama_prodi) VALUES 
('Sistem Informasi'), ('Informatika'), ('Teknik Elektro');

-- 2. Buat 1 biodata Dosen
INSERT INTO dosen (nip, nama_dosen) VALUES 
('199001012020011001', 'Prof. Dr. Budiman');

-- 3. Buat 1 AKUN LOGIN untuk Dosen tsb
-- Passwordnya adalah: 'dosen123' (menggunakan MD5)
INSERT INTO users (username, password_hash, role, id_dosen) VALUES
('dosen_budiman', 'e111b918b141e975d0452382e7b1660a', 'dosen', 1);

-- 4. Buat 1 CONTOH BIODATA MAHASISWA (agar bisa dites registernya)
INSERT INTO mahasiswa (nim, nama_lengkap, id_prodi) VALUES
('10241001', 'Budi Santoso', 1),
('10241002', 'Ani Yudhoyono', 2);

INSERT INTO users (username, password, role) VALUES
('admin2', '123', 'admin');

INSERT INTO dosen (nip, nama_dosen) 
VALUES ('12345', 'Prof. Susi');

INSERT INTO users (username, password_hash, role, id_dosen) 
VALUES ('susi_admin', MD5('susi123'), 'dosen', 2);
