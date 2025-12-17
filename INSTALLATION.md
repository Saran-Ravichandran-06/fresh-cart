# ⚙️ Installation Guide – Online Fruits & Veggies Store

Follow the steps below to run this project locally.

---

## 🖥 System Requirements
- Windows
- XAMPP 
- PHP 
- MySQL 
- Web Browser

---

## 🔧 Step 1: Clone the Repository

git clone https://github.com//online-fruits-veggies-store.git

Move the project into:
    htdocs/   (for XAMPP)
    www/      (for WAMP)

## 🗄 Step 2: Database Setup

Open phpMyAdmin
Create a new database:
    CREATE DATABASE fruit_store;
Import the file:
    database.sql

## 🔐 Step 3: Configure Database Connection

includes/db.php

    <?php
    $conn = new mysqli("localhost", "root", "", "fruit_store");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>

## ▶ Step 4: Start Server

Open XAMPP/WAMP
Start Apache and MySQL

## 🌐 Step 5: Run the Application

Open browser and go to:

    http://localhost/fruit_veggie_store/