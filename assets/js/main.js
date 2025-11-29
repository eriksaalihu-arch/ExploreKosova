/* ===================== UTILITIES ===================== */
function isValidEmail(email) {
    return email.includes("@") && email.includes(".") && email.length > 5;
}

function setError(input, message) {
    const group = input.closest("div");
    const msgEl = group ? group.querySelector(".error-msg") : null;

    input.classList.add("input-error");
    if (msgEl) msgEl.textContent = message;
}

function clearError(input) {
    const group = input.closest("div");
    const msgEl = group ? group.querySelector(".error-msg") : null;

    input.classList.remove("input-error");
    if (msgEl) msgEl.textContent = "";
}

/* ===================== CONTACT FORM ===================== */
const contactForm = document.getElementById("contactForm");

if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
        const name = document.getElementById("contactName");
        const email = document.getElementById("contactEmail");
        const msg = document.getElementById("contactMessage");
        const successEl = document.getElementById("contactSuccess");

        let isValid = true;
        [name, email, msg].forEach(clearError);

        if (name.value.trim().length < 3) {
            setError(name, "Emri duhet të ketë të paktën 3 karaktere.");
            isValid = false;
        }
        if (!isValidEmail(email.value.trim())) {
            setError(email, "Ju lutem shkruani një email valid.");
            isValid = false;
        }
        if (msg.value.trim().length < 10) {
            setError(msg, "Mesazhi duhet të ketë të paktën 10 karaktere.");
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        } else if (successEl) {
            e.preventDefault();
            successEl.textContent = "Mesazhi u dërgua me sukses (simulim).";
            contactForm.reset();
        }
    });
}

/* ===================== LOGIN FORM ===================== */
const loginForm = document.getElementById("loginForm");

if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
        const email = document.getElementById("loginEmail");
        const pass = document.getElementById("loginPassword");

        let isValid = true;
        [email, pass].forEach(clearError);

        if (!isValidEmail(email.value.trim())) {
            setError(email, "Email nuk është valid.");
            isValid = false;
        }
        if (pass.value.trim().length < 6) {
            setError(pass, "Fjalëkalimi duhet të ketë të paktën 6 karaktere.");
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        } else {
            e.preventDefault();
            alert("Login simulues i suksesshëm!");
        }
    });
}

/* ===================== REGISTER FORM ===================== */
const registerForm = document.getElementById("registerForm");

if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
        const name = document.getElementById("regName");
        const email = document.getElementById("regEmail");
        const pass = document.getElementById("regPassword");
        const confirm = document.getElementById("regConfirm");

        let isValid = true;
        [name, email, pass, confirm].forEach(clearError);

        if (name.value.trim().length < 3) {
            setError(name, "Emri duhet të ketë të paktën 3 karaktere.");
            isValid = false;
        }
        if (!isValidEmail(email.value.trim())) {
            setError(email, "Ju lutem shkruani një email valid.");
            isValid = false;
        }
        if (pass.value.trim().length < 6) {
            setError(pass, "Fjalëkalimi duhet të ketë të paktën 6 karaktere.");
            isValid = false;
        }
        if (pass.value.trim() !== confirm.value.trim()) {
            setError(confirm, "Fjalëkalimet nuk përputhen.");
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        } else {
            e.preventDefault();
            alert("Regjistrimi u krye me sukses (simulim)!");
            registerForm.reset();
        }
    });
}