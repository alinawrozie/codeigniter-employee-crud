# 🚀 CodeIgniter 3 - Employee CRUD Portal

[![PHP Version](https://img.shields.io/badge/php-%5E7.4-blue.svg)](https://www.php.net/)
[![Framework](https://img.shields.io/badge/framework-CodeIgniter%203-red.svg)](https://codeigniter.com/)
[![Database](https://img.shields.io/badge/database-MySQL%20%2F%20MariaDB-orange.svg)](https://www.mysql.com/)

**Employee Management CRUD System** built with **CodeIgniter 3 (MVC Architecture)**, MySQL, Bootstrap 4, and jQuery. This application provides standard and bulk management of employee profiles, complete with validation, image uploading, search, and pagination.

---

## 🌟 Core Features

- 👤 **Complete CRUD**: Create, read, update, and delete employee profiles.
- 🖼️ **Profile Image Handling**: File upload functionality with automatic image cleanup upon editing or deletion.
- ⚡ **Asynchronous Validation & Interaction**:
  - Live client-side form validations using `jQuery Validate`.
  - Date picking constraints through `jQuery UI Datepicker`.
  - Real-time profile image preview before submission.
- 🗂️ **Advanced Bulk Actions**: Select multiple records to **Activate**, **In-Active**, or **Delete** in a single click (with JS-based safety confirmations).
- 🔍 **Dynamic Filtering**: Server-side name search functionality combined with full pagination support.
- 📂 **Relational Database Design**: Automated handling of creation/join timestamps and status flags.

---

## 🛠️ Tech Stack & Libraries

- **Backend**: PHP 7.4+ & CodeIgniter 3.x (MVC structure)
- **Database**: MySQL / MariaDB
- **Frontend CSS**: Bootstrap 4.1.3 & Normalize.css
- **Frontend JavaScript**: jQuery 3.6.0, jQuery UI Datepicker, jQuery Validation Plugin

---

## 📂 Custom MVC Structure

This project contains custom MVC components designed to fit into a CodeIgniter 3 framework root directory:

```text
codeigniter-employee-crud/
├── controller/
│   └── Employees.php            # Main Controller handling business logic & validation rules
├── model/
│   └── Employee_model.php       # Data Access Object querying the employees database table
├── database/
│   └── company.sql              # MySQL Database Schema script
└── view/
    ├── add_employee.php         # Form view to add a new employee record (with client-side validation)
    ├── edit_emp.php             # Form view to update employee details and image
    └── manage_employees.php     # Main dashboard displaying records list, pagination, and bulk actions
```

---

## 🛢️ Database Schema (`employees_tbl`)

The table setup is stored in `database/company.sql`. The database design is:

```sql
CREATE TABLE `employees_tbl` (
  `emp_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `dob` varchar(15) NOT NULL,
  `email` varchar(60) NOT NULL,
  `skills` varchar(60) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `gender` int(1) NOT NULL,          -- 1: Male, 0: Female
  `address` varchar(60) NOT NULL,
  `status` int(1) NOT NULL,          -- 1: Active, 0: In-Active
  `image` varchar(60) NOT NULL,       -- Path to the stored profile image
  `date_of_join` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 🚀 Setup & Installation Instructions

Follow these steps to integrate this MVC module into your local CodeIgniter 3 setup:

### 1. Prerequisites
- Install a local server environment such as **XAMPP**, **WampServer**, or **Laragon** (PHP 7.4+ is recommended).
- Ensure Git is installed.

### 2. Configure Database
1. Open **phpMyAdmin** (or any database GUI tool).
2. Create a new database named `company`.
3. Import the database schema from `database/company.sql` file.

### 3. Integrate with CodeIgniter 3
1. Download a clean instance of **CodeIgniter 3**.
2. Drop the files from this repository into their corresponding CodeIgniter directory folders:
   - Copy `controller/Employees.php` to your `application/controllers/` folder.
   - Copy `model/Employee_model.php` to your `application/models/` folder.
   - Copy the views in `view/` to your `application/views/` folder.
3. Configure the database connection settings in `application/config/database.php` to match your MySQL credentials.
4. Set the default controller to `employees` or configure routes in `application/config/routes.php`:
   ```php
   $route['default_controller'] = 'employees/manage';
   ```

### 4. Run the Project
Start your Apache and MySQL servers, then navigate to your local localhost path:
`http://localhost/codeigniter-employee-crud/` (or your customized URL path).

---

## 🔒 Code Safety & Reliability Improvements

To ensure clean, production-grade logic, the code has been reinforced with safeguards:
- **Directory Safety**: Checks `is_dir('images')` before trying to create it dynamically during upload.
- **Robust File Management**: Uses `file_exists()` and `is_file()` checks prior to unlinking/deleting old profile images, avoiding PHP warning crashes during database record cleanup.