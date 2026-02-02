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

// ===================== AUTO HIDE ALERTS =====================
(() => {
  const ALERT_SELECTORS = [
    ".auth-alert",
    ".alert",
    ".success-message",
    ".error-message"
  ];

  const alerts = document.querySelectorAll(ALERT_SELECTORS.join(","));

  if (!alerts.length) return;

  alerts.forEach(alert => {
    // sigurohemi që fade-out funksionon bukur
    alert.style.transition = "opacity .4s ease, transform .4s ease";

    setTimeout(() => {
      alert.style.opacity = "0";
      alert.style.transform = "translateY(-6px)";

      setTimeout(() => {
        alert.remove();
      }, 400);
    }, 4000);
  });
})();

// ========================= Hero Slider =========================
(function () {
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initHeroSlider);
  } else {
    initHeroSlider();
  }

  function initHeroSlider() {
    const root = document.getElementById("ek-hero-slider");
    if (!root) return;

    const slides = Array.from(root.querySelectorAll(".hero-slide"));
    const btnPrev = root.querySelector(".hero-btn.prev");
    const btnNext = root.querySelector(".hero-btn.next");
    const dotsWrap = root.querySelector(".hero-dots");

    const capWrap  = document.getElementById("ek-hero-caption");
    const capTitle = document.getElementById("ek-hero-cap-title");
    const capText  = document.getElementById("ek-hero-cap-text");

    if (slides.length <= 1) return;

    let index = slides.findIndex((s) => s.classList.contains("is-active"));
    if (index < 0) index = 0;

    let dots = [];
    if (dotsWrap) {
      dotsWrap.innerHTML = "";
      dots = slides.map((_, i) => {
        const d = document.createElement("button");
        d.type = "button";
        d.className = "hero-dot" + (i === index ? " is-active" : "");
        d.setAttribute("aria-label", `Shko te slide ${i + 1}`);
        d.addEventListener("click", () => goTo(i, true));
        dotsWrap.appendChild(d);
        return d;
      });
    }

    function updateCaption(i) {
      if (!capWrap || !capTitle || !capText) return;

      const t = (slides[i].dataset.title || "").trim();
      const p = (slides[i].dataset.text || "").trim();

      if (t === "" && p === "") {
        capWrap.style.display = "none";
        capTitle.textContent = "";
        capText.textContent = "";
        return;
      }

      capWrap.style.display = "";
      capTitle.textContent = t;
      capText.textContent = p;
    }

    function render() {
      slides.forEach((s, i) => s.classList.toggle("is-active", i === index));
      dots.forEach((d, i) => d.classList.toggle("is-active", i === index));
      updateCaption(index);
    }

    function goTo(i, userAction = false) {
      index = (i + slides.length) % slides.length;
      render();
      if (userAction) restartAutoplay();
    }

    function next(userAction = false) {
      goTo(index + 1, userAction);
    }

    function prev(userAction = false) {
      goTo(index - 1, userAction);
    }

    btnNext && btnNext.addEventListener("click", () => next(true));
    btnPrev && btnPrev.addEventListener("click", () => prev(true));

    const autoplay = root.dataset.autoplay === "1";
    const interval = Number(root.dataset.interval || 5000);
    let timer = null;

    function startAutoplay() {
      if (!autoplay) return;
      stopAutoplay();
      timer = setInterval(() => next(false), interval);
    }

    function stopAutoplay() {
      if (timer) clearInterval(timer);
      timer = null;
    }

    function restartAutoplay() {
      if (!autoplay) return;
      startAutoplay();
    }

    root.addEventListener("mouseenter", stopAutoplay);
    root.addEventListener("mouseleave", startAutoplay);

    document.addEventListener("visibilitychange", () => {
      if (document.hidden) stopAutoplay();
      else startAutoplay();
    });

    let startX = 0;
    root.addEventListener(
      "touchstart",
      (e) => {
        startX = e.touches[0].clientX;
      },
      { passive: true }
    );

    root.addEventListener(
      "touchend",
      (e) => {
        const endX = e.changedTouches[0].clientX;
        const diff = endX - startX;
        if (Math.abs(diff) > 40) {
          diff < 0 ? next(true) : prev(true);
        }
      },
      { passive: true }
    );
    render();
    startAutoplay();
  }
})();

// Fix: kur kthehesh me Back (bfcache), bëj reload që të kontrollohet session
window.addEventListener("pageshow", function (event) {
  if (event.persisted) {
    window.location.reload();
  }
});