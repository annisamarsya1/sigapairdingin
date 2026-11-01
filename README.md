# 🌊 SIGAP AIR DINGIN

> **Sistem Informasi Pelaporan dan Pemetaan Permasalahan Air, Lingkungan, dan Pelayanan Publik**  
> Kelurahan Air Dingin, Kota Sawahlunto, Sumatera Barat

---

## 📌 Tentang Aplikasi

**SIGAP Air Dingin** adalah sistem informasi berbasis web yang dirancang untuk memudahkan warga Kelurahan Air Dingin dalam melaporkan permasalahan lingkungan, infrastruktur, bencana alam, serta mengajukan aspirasi dan layanan administrasi publik secara digital.

Sistem ini memungkinkan petugas kelurahan untuk memonitor, menindaklanjuti, dan memvisualisasikan laporan warga melalui peta risiko interaktif.

---

## ✨ Fitur Utama

| Fitur | Keterangan |
|-------|------------|
| 📋 **Pelaporan Terintegrasi** | Pengaduan, aspirasi, dan administrasi dalam satu sistem |
| 🗺️ **Peta Risiko Interaktif** | Visualisasi laporan bencana alam pada peta Leaflet.js |
| 📊 **Dashboard Admin** | Statistik laporan, grafik tren, dan peta sebaran |
| 🔄 **Manajemen Status** | Update status laporan: Baru → Ditindak → Ditolak |
| 📁 **Upload Bukti** | Warga dapat melampirkan foto/dokumen bukti |
| 🌐 **Bilingual** | Dukungan bahasa Indonesia dan Inggris |
| 📱 **Responsive** | Tampilan optimal di desktop maupun mobile |

---

## 🖼️ Screenshot

### Landing Page
![Landing Page](sigap-air-dingin/assets/img/sawahlunto1.png)

---

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP 8.x (Native / Vanilla PHP)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, Bootstrap 5.3
- **Peta:** Leaflet.js + OpenStreetMap + ESRI Satellite
- **Grafik:** Chart.js
- **Icons:** Font Awesome 6
- **Server:** Apache (Laragon / XAMPP)

---

## 📂 Struktur Folder

```
sigap-air-dingin/
├── admin/                  # Halaman khusus admin
│   ├── dashboard.php       # Dashboard statistik & grafik
│   ├── laporan.php         # Manajemen data laporan
│   ├── peta_risiko.php     # Peta risiko admin
│   ├── detail_laporan.php  # Detail laporan
│   ├── update_status.php   # Update status laporan
│   └── logout.php
├── auth/                   # Autentikasi
│   ├── login.php           # Login warga
│   ├── login_admin.php     # Login admin
│   ├── register.php        # Registrasi warga
│   └── register_admin.php  # Registrasi admin
├── assets/
│   ├── css/                # Stylesheet
│   └── img/                # Gambar & logo
├── config/
│   ├── db.php              # Konfigurasi database
│   └── app.php             # Konfigurasi aplikasi (BASE_URL)
├── partials/
│   ├── navbar.php          # Navigasi publik
│   ├── header.php          # Header template
│   └── footer.php          # Footer template
├── peta/
│   ├── peta.php            # Halaman peta
│   └── risiko.geojson      # Data GeoJSON risiko
├── uploads/                # File upload dari warga
├── index.php               # Dashboard warga (setelah login)
├── landing.php             # Halaman publik / beranda
├── pengaduan.php           # Form pengaduan
├── aspirasi.php            # Form aspirasi
├── administrasi.php        # Form administrasi publik
├── riwayat.php             # Riwayat laporan warga
├── peta_risiko.php         # Peta risiko warga
├── logout.php              # Logout
└── sigap_db.sql            # File SQL database
```

---

## ⚙️ Cara Instalasi

### Prasyarat
- [Laragon](https://laragon.org/) atau XAMPP
- PHP >= 8.0
- MySQL / MariaDB

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/username/sigapairdingin.git
```
Atau download ZIP dan ekstrak ke folder `C:\laragon\www\sigapairdingin\`

**2. Import database**
- Buka `http://localhost/phpmyadmin`
- Buat database baru bernama `sigab_db`
- Import file: `sigap-air-dingin/sigap_db.sql`

**3. Konfigurasi**

Edit file `sigap-air-dingin/config/app.php`:
```php
define('BASE_URL', 'http://localhost/sigapairdingin/sigap-air-dingin/');
```

Edit file `sigap-air-dingin/config/db.php`:
```php
$host = 'localhost';
$db   = 'sigab_db';
$user = 'root';
$pass = '';
```

**4. Jalankan aplikasi**

Buka browser dan akses:
```
http://localhost/sigapairdingin/sigap-air-dingin/landing.php
```

---

## 👤 Akun Default

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| Warga | Daftar sendiri via `/auth/register.php` | - |

> ⚠️ Segera ganti password default setelah pertama kali login.

---

## 📋 Jenis Layanan

### 📣 Pengaduan
Laporan permasalahan warga berdasarkan kategori:
- Bencana Alam
- Infrastruktur
- Lingkungan
- Keamanan
- Bantuan Hukum
- Lainnya

### 💡 Aspirasi
Usulan dan masukan warga untuk pembangunan kelurahan:
- Ekonomi
- Sosial
- Pembangunan Daerah
- Lainnya

### 📄 Administrasi Publik
Pengajuan surat keterangan secara online:
- Surat Keterangan Kelahiran
- Surat Pengantar Akta Kelahiran
- Surat Keterangan Kematian
- Surat Pengantar Akta Kematian
- Surat Keterangan Ahli Waris

---

## 🗺️ Lokasi

**Kelurahan Air Dingin**  
Kecamatan Lembah Segar, Kota Sawahlunto  
Sumatera Barat, Indonesia  
📍 Koordinat: `-0.6671, 100.7831`

---

## 🔄 Status Laporan

```
BARU  →  DITINDAK  →  selesai
  ↘
   DITOLAK
```

| Status | Keterangan |
|--------|------------|
| `baru` | Laporan baru masuk, belum diproses |
| `ditindak` | Laporan diterima dan sedang ditindaklanjuti |
| `ditolak` | Laporan tidak dapat diproses |

---

## 🤝 Kontribusi

Pull request sangat terbuka. Untuk perubahan besar, harap buka issue terlebih dahulu untuk mendiskusikan apa yang ingin diubah.

1. Fork repository ini
2. Buat branch fitur (`git checkout -b fitur/NamaFitur`)
3. Commit perubahan (`git commit -m 'Tambah fitur X'`)
4. Push ke branch (`git push origin fitur/NamaFitur`)
5. Buat Pull Request

---

## 📜 Lisensi

Didistribusikan di bawah lisensi MIT. Lihat `LICENSE` untuk informasi lebih lanjut.

---

## 📞 Kontak

**Kelurahan Air Dingin**  
Kota Sawahlunto, Sumatera Barat  

---

<div align="center">
  <p>Dibuat dengan ❤️ untuk warga Kelurahan Air Dingin</p>
  <p><strong>SIGAP Air Dingin</strong> — Laporkan Lebih Cepat, Tangani Lebih Tepat</p>
</div>
