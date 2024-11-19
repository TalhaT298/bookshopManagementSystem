<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel Bookstore Application

This project is a Laravel-based application for managing a bookstore. It includes functionality for creating, displaying, searching, and deleting book records.

## Prerequisites

- PHP 8.x
- Composer
- A database (e.g., MySQL)

---

## Installation

### Step 1: Clone and Set Up the Project

```bash
composer create-project laravel/laravel laravel-bookstore
cd laravel-bookstore
```
### Step 2: Install Dependencies
```bash
composer install
```
### Step 3: Generate Application Key
```bash
php artisan key:generate
```
### Step 4: Run Database Migrations
```bash
php artisan migrate
```
### Step 5: Seed the Database
```bash
php artisan db:seed DatabaseSeeder
```
### Step 6: Start the Development Server
```bash
php artisan serve
```
## Models

### Book Model

The `Book` model represents the structure of a book record. It includes attributes such as title, author, ISBN, price, and stock.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "author",
        "isbn",
        "price",
        "stock",
    ];
}
```
