# 📦 Database Setup

This project uses **MySQL** for data storage. Follow the steps below to set up the database using **XAMPP** and **phpMyAdmin**.

## ⚙️ Requirements
- [XAMPP](https://www.apachefriends.org/)
- `schema.sql` – file containing table definitions
- `seed.sql` – file containing sample data (optional)

## 🛠️ Steps

1. **Open XAMPP** and start the **Apache** and **MySQL** modules.
2. Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin) in your browser.
3. Click **New** and create a new database (e.g., `mapaayosDB`).
4. Click on the newly created database.
5. Click **Import** in the top menu.
6. Upload and import the `schema.sql` file to create the necessary tables.
7. *(Optional)* Upload and import the `seed.sql` file to populate the database with sample data.

---

✅ Once completed, your database will be ready for use with the application.