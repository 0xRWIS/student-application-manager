# 📁 Project Setup Guide

A simple guide to run this project on your local machine.

---

## ⚡ Prerequisites

Before starting, make sure you have the following installed:

---

## 🐘 1. PHP + MySQL (Recommended: XAMPP)

The easiest way to run PHP + MySQL is using **XAMPP**.

👉 Download XAMPP:  
https://www.apachefriends.org/

### After installation:
- Start **Apache**
- Start **MySQL**

---

### Alternative (Manual install)

If you don’t want XAMPP:

- PHP: https://www.php.net/downloads  
- MySQL: https://dev.mysql.com/downloads/

---

##  2. Node.js + npm (Required for Tailwind CSS)

This project uses **Tailwind CSS**, so Node.js is required.

👉 Download Node.js (includes npm):  
https://nodejs.org/


### Check installation:

```bash
node -v
npm -v
```

---

## Install Project Dependencies

Inside your project folder, run:
```bash
npm install
```

---

### Tailwind CSS Setup

To generate and watch CSS output:
```bash
npm run dev
```
This will:

Watch input.css
Generate output.css
Output it inside the dist/ folder

---

## Database Configuration
Before running the project, you must set up your database connection.
### Step 1: Rename file
find:
```bash
database.EXAMPLE.php
```
Rename it to:
```bash
database.php
```

### Step 2: Edit database connection
Open database.php and update:
```php
<?php

$dsn = 'mysql:host=;dbname=;charset=utf8mb4';
$user = '';
$pass = '';

try {
    $dbconnect = new PDO($dsn, $user, $pass);
    $dbconnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo 'Failed: ' . $e->getMessage();
}
```

### Step 3: Database setup

- Open phpMyAdmin
- Create a new database (example: st-ap-ma-vi)
- Import the .sql file provided in this project
- Make sure $dbname matches your database name
---
## Run the Project
If using XAMPP:
1-Move project folder to
```bash
htdocs/
```
2-Open browser:
```bash
http://localhost/your-project-folder
```
---
