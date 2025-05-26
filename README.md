<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Dokumentasi Relasi Antar Model

## Model: Alternatif
- **Tabel**: `alternatif`
- **Primary Key**: `id_alternatif`
- **Relasi**:
  1. **Penilaian** (One-to-Many)
     - **Deskripsi**: Satu `Alternatif` dapat memiliki banyak `Penilaian`.
     - **Metode**: `penilaians()`
     - **Tipe Relasi**: `hasMany`
     - **Foreign Key**: `id_alternatif` di tabel `penilaian`.

  2. **Rangking** (One-to-One)
     - **Deskripsi**: Satu `Alternatif` memiliki satu `Rangking`.
     - **Metode**: `rangking()`
     - **Tipe Relasi**: `hasOne`
     - **Foreign Key**: `id_alternatif` di tabel `rangking`.

---

## Model: Penilaian
- **Tabel**: `penilaian`
- **Primary Key**: `id_penilaian`
- **Relasi**:
  1. **Alternatif** (Many-to-One)
     - **Deskripsi**: Banyak `Penilaian` dimiliki oleh satu `Alternatif`.
     - **Metode**: `alternatif()`
     - **Tipe Relasi**: `belongsTo`
     - **Foreign Key**: `id_alternatif` di tabel `penilaian`.

---

## Model: Rangking
- **Tabel**: `rangking`
- **Primary Key**: `id_rangking`
- **Relasi**:
  1. **Alternatif** (One-to-One)
     - **Deskripsi**: Satu `Rangking` dimiliki oleh satu `Alternatif`.
     - **Metode**: `alternatif()`
     - **Tipe Relasi**: `belongsTo`
     - **Foreign Key**: `id_alternatif` di tabel `rangking`.

---

## Model: Kriteria
- **Tabel**: `kriteria`
- **Primary Key**: `id_kriteria`
- **Relasi**:
  1. **SubKriteria** (One-to-Many)
     - **Deskripsi**: Satu `Kriteria` dapat memiliki banyak `SubKriteria`.
     - **Metode**: `subKriterias()`
     - **Tipe Relasi**: `hasMany`
     - **Foreign Key**: `id_kriteria` di tabel `sub_kriteria`.

  2. **Penilaian** (One-to-Many)
     - **Deskripsi**: Satu `Kriteria` dapat memiliki banyak `Penilaian`.
     - **Metode**: `penilaians()`
     - **Tipe Relasi**: `hasMany`
     - **Foreign Key**: `id_kriteria` di tabel `penilaian`.

---

## Model: SubKriteria
- **Tabel**: `sub_kriteria`
- **Primary Key**: `id_sub_kriteria`
- **Relasi**:
  1. **Kriteria** (Many-to-One)
     - **Deskripsi**: Banyak `SubKriteria` dimiliki oleh satu `Kriteria`.
     - **Metode**: `kriteria()`
     - **Tipe Relasi**: `belongsTo`
     - **Foreign Key**: `id_kriteria` di tabel `sub_kriteria`.

  2. **Penilaian** (One-to-Many)
     - **Deskripsi**: Satu `SubKriteria` dapat memiliki banyak `Penilaian`.
     - **Metode**: `penilaians()`
     - **Tipe Relasi**: `hasMany`
     - **Foreign Key**: `id_sub_kriteria` di tabel `penilaian`.

---

## Diagram Relasi Antar Model
```plaintext
Alternatif
  ├── penilaians (1:N)
  └── rangking (1:1)

Penilaian
  ├── alternatif (N:1)
  ├── kriteria (N:1)
  └── sub_kriteria (N:1)

Kriteria
  ├── sub_kriterias (1:N)
  └── penilaians (1:N)

SubKriteria
  ├── kriteria (N:1)
  └── penilaians (1:N)
```

---

## Penjelasan Relasi
1. **Alternatif ke Penilaian**: Relasi One-to-Many, artinya satu entitas `Alternatif` dapat memiliki banyak entitas `Penilaian`.
2. **Alternatif ke Rangking**: Relasi One-to-One, artinya satu entitas `Alternatif` hanya memiliki satu entitas `Rangking`.
3. **Penilaian ke Alternatif**: Relasi Many-to-One, artinya banyak entitas `Penilaian` dimiliki oleh satu entitas `Alternatif`.
4. **Rangking ke Alternatif**: Relasi One-to-One, artinya satu entitas `Rangking` dimiliki oleh satu entitas `Alternatif`.
5. **Kriteria ke SubKriteria**: Relasi One-to-Many, artinya satu entitas `Kriteria` dapat memiliki banyak entitas `SubKriteria`.
6. **Kriteria ke Penilaian**: Relasi One-to-Many, artinya satu entitas `Kriteria` dapat memiliki banyak entitas `Penilaian`.
7. **SubKriteria ke Kriteria**: Relasi Many-to-One, artinya banyak entitas `SubKriteria` dimiliki oleh satu entitas `Kriteria`.
8. **SubKriteria ke Penilaian**: Relasi One-to-Many, artinya satu entitas `SubKriteria` dapat memiliki banyak entitas `Penilaian`.

---

## Catatan
Pastikan setiap model memiliki relasi yang sesuai:
- Model `Penilaian` harus memiliki relasi `belongsTo` ke `Alternatif`.
- Model `Rangking` harus memiliki relasi `belongsTo` ke `Alternatif`.
- Model `Kriteria` harus memiliki relasi `hasMany` ke `SubKriteria` dan `Penilaian`.
- Model `SubKriteria` harus memiliki relasi `belongsTo` ke `Kriteria` dan `hasMany` ke `Penilaian`.
