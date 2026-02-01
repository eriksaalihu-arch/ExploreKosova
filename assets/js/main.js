/* ===================== UTILITIES ===================== */
function isValidEmail(email) {
  return email.includes("@") && email.includes(".") && email.length > 5;
}

function setError(input, message) {
  if (!input) return;

  const group = input.closest("div");
  const msgEl = group ? group.querySelector(".error-msg") : null;

  input.classList.add("input-error");
  if (msgEl) msgEl.textContent = message;

  input.focus();
}

function clearError(input) {
  if (!input) return;

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

    let isValid = true;
    [name, email, msg].forEach(clearError);

    if (!name || name.value.trim().length < 3) {
      setError(name, "Emri duhet të ketë të paktën 3 karaktere.");
      isValid = false;
    }

    if (!email || !isValidEmail(email.value.trim())) {
      setError(email, "Ju lutem shkruani një email valid.");
      isValid = false;
    }

    if (!msg || msg.value.trim().length < 10) {
      setError(msg, "Mesazhi duhet të ketë të paktën 10 karaktere.");
      isValid = false;
    }

    if (!isValid) e.preventDefault(); // vetëm kur ka gabime
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

    if (!email || !isValidEmail(email.value.trim())) {
      setError(email, "Email nuk është valid.");
      isValid = false;
    }

    if (!pass || pass.value.trim().length < 6) {
      setError(pass, "Fjalëkalimi duhet të ketë të paktën 6 karaktere.");
      isValid = false;
    }

    if (!isValid) e.preventDefault();
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

    if (!name || name.value.trim().length < 3) {
      setError(name, "Emri duhet të ketë të paktën 3 karaktere.");
      isValid = false;
    }

    if (!email || !isValidEmail(email.value.trim())) {
      setError(email, "Ju lutem shkruani një email valid.");
      isValid = false;
    }

    if (!pass || pass.value.trim().length < 6) {
      setError(pass, "Fjalëkalimi duhet të ketë të paktën 6 karaktere.");
      isValid = false;
    }

    if (!confirm || pass.value.trim() !== confirm.value.trim()) {
      setError(confirm, "Fjalëkalimet nuk përputhen.");
      isValid = false;
    }

    if (!isValid) e.preventDefault();
  });
}

/* ===================== ERROR DISAPPEAR AFTER 4 SEC ===================== */
const alertBox = document.querySelector(".auth-alert");
if (alertBox) {
  setTimeout(() => {
    alertBox.style.opacity = "0";
    setTimeout(() => alertBox.remove(), 400);
  }, 4000);
}

/* ===================== SLIDERI ===================== */

(function () {
  const root = document.getElementById("ek-slider");
  if (!root) return;

  const track = root.querySelector(".slider-track");
  const slides = Array.from(root.querySelectorAll(".slide"));
  const prevBtn = root.querySelector(".slider-btn.prev");
  const nextBtn = root.querySelector(".slider-btn.next");
  const dotsWrap = root.querySelector(".slider-dots");

  let index = 0;
  let timer = null;
  const AUTOPLAY_MS = 4000;

  slides.forEach((_, i) => {
    const b = document.createElement("button");
    b.type = "button";
    b.className = "slider-dot" + (i === 0 ? " active" : "");
    b.setAttribute("aria-label", "Shko te slide " + (i + 1));
    b.addEventListener("click", () => goTo(i, true));
    dotsWrap.appendChild(b);
  });

  const dots = Array.from(dotsWrap.querySelectorAll(".slider-dot"));

  function update() {
    track.style.transform = `translateX(-${index * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle("active", i === index));
  }

  function goTo(i, userAction = false) {
    index = (i + slides.length) % slides.length;
    update();
    if (userAction) restartAutoplay();
  }

  function next() { goTo(index + 1, true); }
  function prev() { goTo(index - 1, true); }

  function startAutoplay() {
    stopAutoplay();
    timer = setInterval(() => goTo(index + 1, false), AUTOPLAY_MS);
  }

  function stopAutoplay() {
    if (timer) clearInterval(timer);
    timer = null;
  }

  function restartAutoplay() { startAutoplay(); }

  nextBtn && nextBtn.addEventListener("click", next);
  prevBtn && prevBtn.addEventListener("click", prev);

  root.addEventListener("mouseenter", stopAutoplay);
  root.addEventListener("mouseleave", startAutoplay);

  let startX = 0;
  root.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
  }, { passive: true });

  root.addEventListener("touchend", (e) => {
    const endX = e.changedTouches[0].clientX;
    const dx = endX - startX;
    if (Math.abs(dx) > 45) dx < 0 ? next() : prev();
  });

  update();
  startAutoplay();
})();