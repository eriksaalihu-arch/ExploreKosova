/* ===================== UTILITIES ===================== */

function isValidEmail(email) {
    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
}

function setError(input, message) {
    const msgEl = input.nextElementSibling;
    msgEl.textContent = message;
    input.classList.add("input-error");
}

function clearError(input) {
    const msgEl = input.nextElementSibling;
    msgEl.textContent = "";
    input.classList.remove("input-error");
}

/* ===================== CONTACT FORM ===================== */

const contactForm = document.getElementById("contactForm");

if (contactForm) {
    contactForm.addEventListener("submit", function (e) {

        let valid = true;

        const name = contactName;
        const email = contactEmail;
        const msg = contactMessage;

        clearError(name);
        clearError(email);
        clearError(msg);

        if (name.value.trim().length < 3) {
            setError(name, "Emri duhet të ketë të paktën 3 karaktere.");
            valid = false;
        }

        if (!isValidEmail(email.value.trim())) {
            setError(email, "Ju lutem vendosni një email valid.");
            valid = false;
        }

        if (msg.value.trim().length < 10) {
            setError(msg, "Mesazhi duhet të ketë të paktën 10 karaktere.");
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        } else {
            e.preventDefault(); 
            contactForm.innerHTML = `<p class="success-msg">Mesazhi u dërgua me sukses! ✔</p>`;
        }
    });
}

/* ===================== LOGIN FORM ===================== */

const loginForm = document.getElementById("loginForm");

if (loginForm) {
    loginForm.addEventListener("submit", function (e) {

        let valid = true;

        const email = loginEmail;
        const pass = loginPassword;

        clearError(email);
        clearError(pass);

        if (!isValidEmail(email.value.trim())) {
            setError(email, "Email nuk është valid.");
            valid = false;
        }

        if (pass.value.trim().length < 6) {
            setError(pass, "Fjalëkalimi duhet të ketë të paktën 6 karaktere.");
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

/* ===================== REGISTER FORM ===================== */

const registerForm = document.getElementById("registerForm");

if (registerForm) {
    registerForm.addEventListener("submit", function (e) {

        let valid = true;

        const name = regName;
        const email = regEmail;
        const pass = regPassword;
        const confirm = regConfirm;

        clearError(name);
        clearError(email);
        clearError(pass);
        clearError(confirm);

        if (name.value.trim().length < 3) {
            setError(name, "Emri duhet të ketë të paktën 3 karaktere.");
            valid = false;
        }

        if (!isValidEmail(email.value.trim())) {
            setError(email, "Ju lutem shkruani një email valid.");
            valid = false;
        }

        if (pass.value.trim().length < 6) {
            setError(pass, "Fjalëkalimi duhet të ketë të paktën 6 karaktere.");
            valid = false;
        }

        if (pass.value.trim() !== confirm.value.trim()) {
            setError(confirm, "Fjalëkalimet nuk përputhen.");
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        } else {
            e.preventDefault();
            registerForm.innerHTML = `<p class="success-msg">Regjistrimi u krye me sukses! ✔</p>`;
        }
    });
}