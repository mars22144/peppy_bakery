# Planning Implementasi Fitur Tambahan Peppy Bakery (Issue #2)

Dokumen ini berisi panduan teknis dan tahapan implementasi fitur baru untuk dikerjakan oleh junior programmer atau AI model.

## 1. Fitur "Add to Cart" Cepat dengan Icon dan Alert (products.php)

**Tujuan:** Memberikan kemudahan bagi pengguna untuk langsung memasukkan produk ke keranjang dari daftar menu (`products.php`) melalui klik icon keranjang tanpa harus masuk ke halaman detail, lalu menampilkan alert javascript konfirmasi.

**Tahapan Implementasi:**
*   **Buat/Modifikasi Endpoint Backend (AJAX):**
    *   Buat file backend baru, misalnya `add_to_cart_ajax.php`.
    *   Endpoint ini menerima request berisi `id_produk` dan `qty` (default 1).
    *   Logika: Cek session, jika valid, tambahkan produk ke session keranjang (`$_SESSION['cart'][$id_produk]`).
    *   Kembalikan response sukses.
*   **Modifikasi UI `products.php`:**
    *   Tambahkan tombol icon keranjang (misalnya `<i class="fas fa-cart-plus"></i>`) pada setiap card produk.
    *   Tambahkan atribut khusus seperti `data-id="<?= $pid ?>"`.
*   **Tambahkan Javascript (Client-side):**
    *   Di bagian bawah file `products.php`, tambahkan event listener untuk tombol keranjang tersebut.
    *   Saat diklik, panggil `event.stopPropagation();` agar event klik tidak mengarahkan parent card ke halaman detail produk.
    *   Gunakan AJAX (`fetch`) untuk mengirim request tambah barang ke `add_to_cart_ajax.php`.
    *   Setelah response berhasil diterima, tampilkan pop-up alert: `alert('Berhasil ditambahkan ke keranjang');`.

## 2. Implementasi Pagination pada Halaman Admin

**Tujuan:** Mencegah tampilan memanjang dan mempercepat loading pada halaman pengelolaan Admin (Produk, Pesanan, Pelanggan) dengan membagi daftar data ke dalam beberapa halaman.

**Target File:** `admin/products_admin.php`, `admin/orders_admin.php`, dan `admin/users_admin.php` (untuk pelanggan).

**Tahapan Implementasi:**
*   **Logika Backend Paging (PHP):**
    *   Tentukan jumlah baris per halaman (misal: `$limit = 10;`).
    *   Tangkap nomor halaman dari URL: `$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;`.
    *   Hitung titik awal (offset): `$offset = ($page - 1) * $limit;`.
*   **Modifikasi Query Database:**
    *   Query Hitung Total: Buat query `SELECT COUNT(*) FROM nama_tabel` untuk mendapatkan total keseluruhan baris.
    *   Hitung total keseluruhan halaman: `$total_pages = ceil($total_data / $limit);`.
    *   Query Data Utama: Ubah query pemanggilan data dengan menambahkan pembatas: `LIMIT $limit OFFSET $offset`.
*   **Membuat UI Navigasi Pagination (HTML/CSS):**
    *   Tambahkan kontainer (misal: `<div class="pagination">`) di bagian bawah tabel data admin.
    *   Gunakan perulangan (loop) PHP untuk merender angka `1` sampai `$total_pages`.
    *   Tautkan setiap angka dengan param URL `?page=X`.
    *   Berikan highlight (class active) pada angka yang cocok dengan halaman saat ini agar admin tahu sedang berada di halaman ke berapa.

---
**Instruksi Penting Tambahan:**
- Pada saat implementasi Javascript add-to-cart, ingat untuk mengecek juga batasan stok di server.
- Uji cobakan pagination dengan memasukkan data dummy (tiruan) yang cukup banyak agar bisa terlihat perpindahan antar halamannya berfungsi sempurna.
