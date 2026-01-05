# 🌍 Tourism Guide Web Application

Tourism Guide adalah aplikasi web sederhana berbasis **PHP & MySQL** yang menyediakan informasi destinasi wisata. Aplikasi ini dibuat sebagai project pembelajaran dan demonstrasi implementasi **PHP Connectivity** serta **CRUD (Create, Read, Update, Delete)** dengan database relasional.

🔗 **Live Demo**: https://tourism.qzz.io/

---

## 📌 Fitur Utama

- 📍 Menampilkan daftar destinasi wisata
- 📝 Menampilkan detail informasi wisata
- 🗂️ Manajemen data wisata (CRUD)
- 🔗 Relasi database menggunakan **Primary Key & Foreign Key**
- 💾 Penyimpanan data menggunakan **MySQL**
- 🌐 Akses melalui web browser

---

## 🛠️ Teknologi yang Digunakan

- **PHP** (Native)
- **MySQL**
- **HTML5**
- **CSS3**
- **Bootstrap** (untuk tampilan)
- **Apache Web Server**

---

## 🗄️ Struktur Database (Gambaran Umum)

Contoh struktur tabel:

- `categories`
  - id_category (PK)
  - category_name

- `tourism`
  - id_tourism (PK)
  - name
  - description
  - location
  - image
  - id_category (FK)

Relasi:
- Satu kategori dapat memiliki banyak data wisata (One-to-Many)

---

## 📂 Struktur Folder

