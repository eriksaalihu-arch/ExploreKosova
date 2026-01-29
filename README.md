# 🌍 ExploreKosova

**ExploreKosova** është një web aplikacion i zhvilluar në **PHP**, i dedikuar promovimit të tureve turistike në Kosovë. Platforma ofron funksionalitete për vizitorët, përdoruesit e regjistruar dhe administratorët, duke përfshirë menaxhimin e tureve, kontaktin me vizitorët dhe një dashboard administrativ me statistika.

Projekti është ndërtuar duke kombinuar **Object-Oriented Programming (OOP)** dhe **qasje procedurale**, me një strukturë **MVC-like**, duke respektuar praktikat e mira të programimit dhe sigurisë.

---

## 📌 Përshkrim i Projektit

ExploreKosova u mundëson vizitorëve:
- të shohin ture turistike në Kosovë,
- të lexojnë detaje për secilin tur,
- të shikojnë ose shkarkojnë dokumente PDF,
- të kontaktojnë administratorët përmes contact form.

Administratorët kanë qasje në një **Dashboard**, ku mund të:
- menaxhojnë turet (CRUD),
- shohin dhe fshijnë mesazhet nga contact form,
- menaxhojnë përdoruesit,
- shohin statistika të përgjithshme të sistemit.

---

## 🛠️ Teknologjitë e Përdorura

- **PHP**
- **MySQL**
- **PDO (PHP Data Objects)**
- **HTML5**
- **CSS3**
- **JavaScript**
- **XAMPP**
- **phpMyAdmin**

---

## 🧱 Arkitektura e Projektit

Projekti përdor një arkitekturë **MVC-like**, të kombinuar me **OOP dhe kod procedural**:

- **Models** – logjika e databazës
- **Controllers / Handlers** – trajtimi i kërkesave (POST/GET)
- **Views** – faqet PHP/HTML që shfaqen te përdoruesi

🔹 Kjo arkitekturë ofron:
- ndarje të qartë të përgjegjësive,
- kod më të organizuar dhe të lexueshëm,
- mirëmbajtje dhe zgjerim më të lehtë.

---

## ⚙️ Funksionalitetet Kryesore

### 🔐 Autentifikimi
- Login & Register
- Hash i fjalëkalimeve
- Validim i të dhënave

### 👥 Role
- **Admin**
- **User**
- Qasje e kontrolluar sipas rolit

### 🧭 Menaxhimi i Tureve (CRUD)
- Shtim i tureve
- Editim
- Fshirje
- Shfaqje në faqe publike

### 🖼️ Upload Media
- Upload **foto** për ture
- Upload **PDF**
- Ruajtje e path-eve në databazë

### 📩 Contact Form
- Ruajtje e mesazheve në databazë
- Menaxhim nga admin dashboard

### 📊 Dashboard Administrativ
- Numri total i përdoruesve
- Numri total i mesazheve
- Numri total i tureve
- Lista e aktiviteteve të fundit

---

## 📁 Struktura e Projektit (Tree)

ExploreKosova/
│
├── app/
│   ├── config/
│   │   ├── config.php
│   │   └── Database.php
│   ├── helpers/
│   │   └── auth.php
│
├── includes/
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
│
├── uploads/
│   ├── images/
│   └── pdfs/
│
├── admin_tour_form.php
├── admin_tour_save.php
├── admin_tour_delete.php
├── admin_message_delete.php
├── admin_user_delete.php
│
├── dashboard.php
├── services.php
├── service-details.php
├── contact.php
├── contact_submit.php
├── login.php
├── register.php
├── logout.php
│
├── assets/
│   └── style.css
│
└── README.md


---

## ⚙️ Instalimi Lokal (XAMPP)

### 1️⃣ Vendosja e projektit
- Kopjo folderin `ExploreKosova` në:
/Applications/XAMPP/xamppfiles/htdocs/

### 2️⃣ Start shërbimet
- Hap **XAMPP**
- Start:
  - Apache
  - MySQL

### 3️⃣ Krijimi i databazës
- Hap `http://localhost/phpmyadmin`
- Krijo databazë:
explore_kosova

- Importo SQL file (tabelat: users, tours, contact_messages)

### 4️⃣ Konfigurimi i databazës
Edito file:
Shembull:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'explore_kosova');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', 'http://localhost/ExploreKosova');

---

### 👑 Si të bëhet një user Admin
Në phpMyAdmin:
UPDATE users
SET role = 'admin'
WHERE email = 'emaili@userit.com';

---

### 🔐 Siguria
 -	PDO Prepared Statements (mbrojtje ndaj SQL Injection)
 -	CSRF Tokens për veprime kritike
 -	Sanitizim i inputeve (htmlspecialchars)
 - Role-based access control (Admin / User)

---

/screenshots/ballina.png
/screenshots/contact.png
/screenshots/about.png
/screenshots/service.png
/screenshots/login.png
/screenshots/dashboard1.png
/screenshots/dashboard2.png
/screenshots/dashboard3.png
/screenshots/dashboard4.png
/screenshots/addtour.png

---

### 👨‍💻 Autorët
	- Erik Salihu
	-	Jasin Krasniqi
	-	Meris Misini