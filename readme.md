# 📘 REST API - Manajemen Pengguna (CodeIgniter 3 + JWT)

REST API ini dibuat menggunakan CodeIgniter 3 untuk manajemen pengguna, dilengkapi autentikasi menggunakan JWT.

---

## 📁 Struktur Utama

- `application/controllers/Auth.php` → login dan generate token JWT
- `application/controllers/User.php` → endpoint CRUD user (dilindungi token)
- `application/models/User_model.php` → query ke database users

---

## 🚀 Cara Menjalankan di XAMPP

### 1. Clone / Extract Project ke `htdocs`

```
C:/xampp/htdocs/ci3-jwt-api/
```

### 2. Buat Database

- Nama database: `ci3_jwt`
- Import file SQL jika disediakan, atau buat manual:

```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);
```

### 3. Konfigurasi Database

Edit file: `application/config/database.php`

```php
$db['default'] = array(
  'hostname' => 'localhost',
  'username' => 'root',
  'password' => '',
  'database' => 'ci3_jwt',
  'dbdriver' => 'mysqli',
  ...
);
```

### 4. Konfigurasi base_url

Edit file: `application/config/config.php`

```php
$config['base_url'] = 'http://localhost/ci3-jwt-api/';
```

### 5. Install Composer (untuk JWT)

Pastikan kamu sudah install Composer:

```bash
cd ci3-jwt-api
composer require firebase/php-jwt
```

> Jika tidak ingin pakai Composer, bisa salin file JWT manual ke folder library dan load secara manual (tidak disarankan).

---

## 🔐 Autentikasi

Gunakan endpoint berikut untuk login dan mendapatkan token:

### 🔸 Login

**POST** `/auth/login`

#### Request Body (JSON):

```json
{
  "email": "alaufa@example.com",
  "password": "123456"
}
```

#### Response:

```json
{
  "token": "<jwt_token>"
}
```

Simpan token ini untuk digunakan pada semua endpoint berikutnya.

---

## 📦 Endpoint CRUD Users (dilindungi JWT)

Semua endpoint di bawah harus menambahkan Header:

```
Authorization: Bearer <token-dari-login>
```

### 1. Get All Users

```
GET /user
```

### 2. Get User by ID

```
GET /user/show/{id}
```

### 3. Create User

```
POST /user/store
```

**Body:**

```json
{
  "name": "User Baru",
  "email": "baru@example.com",
  "password": "123456"
}
```

### 4. Update User

```
PUT /user/update/{id}
```

**Body:**

```json
{
  "name": "Nama Baru",
  "email": "emailbaru@example.com"
}
```

### 5. Delete User

```
DELETE /user/delete/{id}
```

---

## 📬 Testing dengan Postman

Gunakan `postman_collection.json` yang disediakan untuk import langsung semua endpoint.

Jangan lupa isi:

- `Authorization` header: `Bearer <token>`
- Pilih metode sesuai (GET, POST, PUT, DELETE)

---

## 📌 Catatan Tambahan

- Password user di-hash otomatis saat `store()`
- Semua data dikembalikan dalam format JSON
- Tidak ada session, semua bergantung pada JWT
