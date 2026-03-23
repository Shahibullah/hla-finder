# 🧬 HLA Finder

HLA Finder is a web-based system  designed to match donors and receivers based on Human Leukocyte Antigen (HLA) compatibility.  
The platform supports role-based access for Admin, Donor, Receiver, and Lab users.

---
Contributors
-Ahmmed Shahibullah Shahib
-Md. Rashakh Rahman Shompod
-Dabobbroto Chakroborty
-Talha Bin Zakir


## 🚀 Features

### 🔐 Authentication & Authorization
- Secure user registration and login
- Role-based access control (Admin, Donor, Receiver, Lab)
- Password reset functionality

### 👤 User Roles
- *Admin*
  - Manage users
  - Activate/Deactivate labs
  - View system statistics
- *Donor*
  - Register and manage profile
- *Receiver*
  - Search donors and manage profile
- *Lab*
  - Register (requires admin approval)
  - Update HLA information

### 🧪 Lab Management
- Lab registration with *inactive status by default*
- Admin approval system
- Activate / Deactivate labs

### 📊 Dashboard
- Role-based dashboards
- Admin statistics:
  - Total donors
  - Total receivers
  - Total labs
  - Active labs
  - Pending labs

### 🏠 Homepage
- Live active donor counter

### 🎨 UI Features
- Light/Dark theme toggle
- Responsive design

---

## 🛠️ Tech Stack

### 🔹 Backend
- *Laravel (PHP Framework)*
- PHP 8.x

### 🔹 Frontend
- Blade Templating Engine
- HTML5
- CSS3
- JavaScript (Vanilla)

### 🔹 Database
- MySQL

### 🔹 Tools & Environment
- XAMPP (Apache + MySQL)
- Composer
- Git & GitHub

---

## ⚙️ Installation & Setup

### 1. Clone Repository
```bash
git clone https://github.com/Shahibullah/hla-finder.git
cd hla-finder
