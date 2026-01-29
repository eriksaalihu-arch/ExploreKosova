🌍 ExploreKosova

ExploreKosova është një web aplikacion i zhvilluar në PHP për promovimin e tureve turistike në Kosovë.
Platforma ofron informacione për ture, shërbime turistike, kontakt me vizitorët dhe një dashboard administrativ për menaxhim të plotë të përmbajtjes.

Ky projekt është realizuar si projekt akademik me fokus në arkitekturë të mirë, siguri dhe dizajn modern.

⸻

📌 Përshkrim i Projektit

ExploreKosova synon të promovojë turizmin në Kosovë duke ofruar një platformë moderne ku vizitorët mund të:
	•	Shikojnë ture turistike
	•	Lexojnë për shërbimet
	•	Kontaktojnë ekipin përmes contact form

Administratorët kanë qasje në një Admin Dashboard për menaxhimin e përdoruesve, tureve dhe mesazheve.

⸻

🛠️ Teknologjitë e Përdorura
	•	PHP (PDO)
	•	MySQL
	•	HTML5
	•	CSS3
	•	JavaScript
	•	XAMPP
	•	phpMyAdmin

⸻

🧱 Arkitektura e Projektit
	•	Strukturë MVC-like
	•	Kombinim OOP + Procedural PHP
	•	PDO për komunikim të sigurt me databazën
	•	Ndarje e logjikës nga prezantimi
	•	Praktika bazë sigurie

⸻

⚙️ Funksionalitetet Kryesore

Autentifikimi
	•	Register
	•	Login
	•	Logout
	•	Role: Admin / User

Menaxhimi i Tureve (Admin)
	•	Shtim tur
	•	Editim tur
	•	Fshirje tur
	•	Upload foto & PDF

Contact Form
	•	Dërgim mesazhesh nga vizitorët
	•	Menaxhim i mesazheve nga admin dashboard

Admin Dashboard
	•	Numri total i përdoruesve
	•	Numri total i mesazheve
	•	Numri total i tureve
	•	Lista e fundit e përdoruesve
	•	Lista e fundit e mesazheve
	•	Lista e fundit e tureve

⸻

📁 Struktura e Projektit

ExploreKosova/
├── app/
│   ├── config/
│   │   ├── config.php
│   │   └── Database.php
│   └── helpers/
│       └── auth.php
├── includes/
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
├── uploads/
│   ├── images/
│   └── pdfs/
├── assets/
│   └── style.css
├── admin_user_delete.php
├── admin_message_delete.php
├── admin_tour_form.php
├── admin_tour_save.php
├── admin_tour_delete.php
├── dashboard.php
├── contact.php
├── contact_submit.php
├── services.php
├── service-details.php
├── login.php
├── register.php
├── logout.php
├── index.php
└── README.md

⸻

⚙️ Instalimi Lokal (XAMPP)

1. Vendosja e Projektit

Kopjo folderin ExploreKosova në:

macOS:
/Applications/XAMPP/xamppfiles/htdocs/ExploreKosova

Windows:
C:\xampp\htdocs\ExploreKosova

⸻

2. Startimi i Shërbimeve
	•	Hap XAMPP Control Panel
	•	Start Apache
	•	Start MySQL

⸻

3. Krijimi i Databazës
	•	Hap shfletuesin dhe shko te:
http://localhost/phpmyadmin
	•	Krijo databazë me emrin:
explore_kosova
	•	Krijo ose importo tabelat:
	•	users
	•	tours
	•	contact_messages

⸻

4. Konfigurimi i Databazës

Hap file-in:
app/config/config.php

Vendos:

DB_HOST = localhost
DB_NAME = explore_kosova
DB_USER = root
DB_PASS =
BASE_URL = http://localhost/ExploreKosova

⸻

5. Si të bëhet një User Admin

Në phpMyAdmin ekzekuto:

UPDATE users
SET role = ‘admin’
WHERE email = ‘emaili_userit@example.com’;

⸻

🔐 Siguria
	•	PDO Prepared Statements (mbrojtje nga SQL Injection)
	•	CSRF Tokens për veprime kritike
	•	Validim inputesh
	•	Sanitizim i output-it (XSS protection)

⸻

🖼️ Screenshots

Screenshots të aplikacionit:
	•	Ballina
	•	Rreth Nesh
	•	Shërbimet
	•	Kontakt
	•	Login / Register
	•	Admin Dashboard
	•	Menaxhimi i Tureve

⸻

👨‍💻 Autorët e Projektit
	•	Erik Salihu – ID: 242574387
	•	Jasin Krasniqi
	•	Meris Misini

⸻

© 2025 ExploreKosova – Të gjitha të drejtat e rezervuara