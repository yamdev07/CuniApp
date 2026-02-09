# 🐇 CuniApp — Rabbit Breeding Management System

> A web application for managing rabbit breeding activities (cuniculture) including reproduction tracking, births monitoring, and animal management.

![Laravel](https://img.shields.io/badge/Laravel-10.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.x-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange?logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green)

---

## 📌 About the Project

**CuniApp** is a farm management web application designed to help breeders efficiently monitor and organize rabbit reproduction cycles.

Managing a rabbit farm manually is difficult:
- forgetting mating dates
- not knowing expected birth dates
- poor tracking of newborn rabbits
- confusion between males and females

This system digitizes the entire breeding workflow and provides a clear dashboard to track all activities.

---

## ✨ Main Features

### 🐰 Rabbit Management
- Register male rabbits
- Register female rabbits
- Update health/status of animals
- Track active/inactive animals

### ❤️ Reproduction Tracking
- Record mating (Saillie)
- Automatic calculation of **expected birth date**
- Reproductive history per female

### 🍼 Birth & Newborns
- Record birth events (Mise bas)
- Manage newborn rabbits (Lapereaux)
- Track number of living/dead newborns
- Birth history per female

### 📊 Dashboard
- Overview of breeding activity
- Upcoming births
- Recent matings
- Population monitoring

### 🗂️ Records & History
- Complete reproduction logs
- Traceability of all operations
- Editable and deletable records

---

## 🧠 System Workflow
````
Female Rabbit
↓
Mating (Saillie)
↓
Expected Birth Date (Auto Calculated)
↓
Birth (Mise bas)
↓
Newborn Rabbits (Lapereaux)
↓
Population Monitoring
````

---

## 🛠️ Built With

- **Laravel** — Backend framework
- **Blade** — Templating engine
- **MySQL** — Database
- **Vite** — Asset bundler
- **Bootstrap** — UI styling

---

## ⚙️ Installation

### 1️⃣ Clone the project
```bash
git clone https://github.com/yamdev07/CuniApp.git
cd CuniApp
````
2️⃣ Install dependencies
````
composer install
npm install
````
3️⃣ Configure environment
````
Copy .env file:

cp .env.example .env
````

Generate the application key:
````
php artisan key:generate
````
4️⃣ Configure database
````
Edit .env:

DB_DATABASE=cuniapp
DB_USERNAME=root
DB_PASSWORD=

````
Create the database in MySQL, then run:
````
php artisan migrate
php artisan db:seed
````
5️⃣ Run the project
````
npm run dev
php artisan serve
````

Open in browser:
````
http://127.0.0.1:8000
````
## 🧪 Default Test Data

- After seeding, the system contains:

- sample male rabbits

- sample female rabbits

- reproduction history

- birth records

📁 Project Structure
````
app/
database/
 ├── migrations
 ├── seeders
resources/views/
 ├── femelles
 ├── males
 ├── saillies
 ├── mises_bas
 └── naissances
routes/web.php
````
## 🔐 Future Improvements

- Notifications for upcoming births

- Mobile responsive dashboard

- Veterinary records

- Vaccination tracking

- Statistics and charts

- Multi-user accounts

## 👨‍💻 Author

Yoann ADIGBONON
Full-Stack Developer — Laravel | Flutter | Networks | Systems

GitHub: https://github.com/yamdev07
