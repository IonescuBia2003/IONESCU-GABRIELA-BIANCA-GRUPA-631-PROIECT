
# **Vet Clinic Management System**

## 📖 **Descriere Generală**
"Vet Clinic Management System" este o aplicație web dezvoltată pentru gestionarea programărilor și a informațiilor legate de clienții unui cabinet veterinar. Aplicația permite autentificarea utilizatorilor, crearea de conturi noi, gestionarea programărilor și vizualizarea acestora sub formă de calendar. Administratorul poate vizualiza toate programările efectuate de către utilizatori într-un format asemănător cu cel al Google Calendar, ceea ce facilitează organizarea și planificarea activităților zilnice.

Aplicația utilizează tehnologia PHP pentru partea de backend, împreună cu o bază de date MySQL gestionată prin PDO.

---

## ⚙️ **Funcționalități Principale Implementate**

### 🔐 **Autentificare și Înregistrare Utilizatori**
- Utilizatorii pot crea conturi noi și se pot autentifica în aplicație.
- Utilizatorii autentificați pot adăuga, edita și șterge programări.
- Datele utilizatorilor sunt gestionate securizat în baza de date.

### 📅 **Dashboard pentru Utilizatori**
- După autentificare, utilizatorii pot accesa un dashboard personal în care își pot vedea toate programările.
- Programările sunt afișate într-un tabel organizat cronologic.

### ➕ **Funcționalitatea de Adăugare Programare**
- Utilizatorii pot adăuga programări pentru animalele lor, specificând:
  - Nume animal
  - Nume proprietar
  - Data și ora programării
  - Note suplimentare
- Sistemul verifică suprapunerile pentru a evita conflictele de programare.

### ✏️ **Funcționalitatea de Editare Programare**
- Utilizatorii pot edita programările existente dacă au nevoie să modifice detalii.

### 🗑️ **Funcționalitatea de Ștergere Programare**
- Utilizatorii pot șterge programările care nu mai sunt necesare.

### 📆 **Calendar Vizual pentru Administrator**
- Administratorul are acces la un calendar care afișează toate programările efectuate de toți utilizatorii.
- Calendarul este structurat pe zile și ore, cu programările afișate pe intervale orare exacte.
- Calendarul permite selecția lunii și anului pentru a vizualiza programările din perioade diferite.

---

## 🛠️ **Tehnologii Utilizate**
- **PHP**: Limbaj de programare pentru backend.
- **MySQL**: Baza de date pentru stocarea informațiilor despre utilizatori și programări.
- **HTML/CSS**: Pentru interfața utilizatorului.
- **PDO (PHP Data Objects)**: Pentru gestionarea conexiunii cu baza de date într-un mod securizat și eficient.

---

## 🗂️ **Structura Proiectului**
```
vet_clinic/
├── app.php            # Fișier principal pentru gestionarea autentificării și programărilor
├── calendar.php       # Fișier separat pentru vizualizarea programărilor sub formă de calendar
├── README.md          # Documentația proiectului
└── database.sql       # Script SQL pentru crearea bazei de date și tabelelor necesare
```

---

## 📂 **Cum să Rulezi Proiectul Local**

### 1️⃣ **Pasul 1: Instalarea XAMPP**
- Descarcă și instalează [XAMPP](https://www.apachefriends.org/download.html).
- Pornește serverul Apache și MySQL din XAMPP Control Panel.

### 2️⃣ **Pasul 2: Configurarea Bazei de Date**
- Accesează **phpMyAdmin** la adresa:
  ```
  http://localhost/phpmyadmin
  ```
- Creează o nouă bază de date numită `vet_clinic`.
- Rulează următorul script SQL pentru a crea tabelele necesare:

```sql
CREATE DATABASE vet_clinic;
USE vet_clinic;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_name VARCHAR(100) NOT NULL,
    owner_name VARCHAR(100) NOT NULL,
    date_time DATETIME NOT NULL,
    notes TEXT,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 3️⃣ **Pasul 3: Configurarea Proiectului**
- Plasează fișierele proiectului în folderul `htdocs` din XAMPP.
  ```
  C:/xampp/htdocs/vet_clinic
  ```

### 4️⃣ **Pasul 4: Accesarea Aplicației**
- Deschide browserul și accesează:
  ```
  http://localhost/vet_clinic/app.php
  ```

---

## 🔒 **Gestionarea Autentificării**
- **Autentificare:** Utilizatorii trebuie să introducă numele de utilizator și parola pentru a accesa aplicația.
- **Înregistrare:** Utilizatorii noi își pot crea conturi introducând un nume de utilizator și o parolă.
- **Deconectare:** Utilizatorii se pot deconecta din aplicație printr-un buton dedicat.

---

## 🚀 **Funcționalități viitoare (opționale)**
- Notificări prin email pentru programări.
- Gestionarea multiplelor cabinete veterinare.
- Afișarea statisticilor despre programări.

---

## 📝 **Autor**
Proiect dezvoltat de [Numele Tău].
