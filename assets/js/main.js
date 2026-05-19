/* ===== SECTION SWITCHING ===== */
function showSection(id) {
  document.querySelectorAll('.page-section').forEach(s => {
    s.classList.remove('active');
  });
  const target = document.getElementById(id);
  if (target) {
    target.classList.add('active');
    // Re-trigger reveal for newly shown section
    setTimeout(() => {
      observeReveals();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }, 20);
  }

  // Update active nav link
  document.querySelectorAll('.nav-link').forEach(link => {
    link.classList.remove('active');
    if (link.dataset.section === id) link.classList.add('active');
  });
}

function closeMobile() {
  document.getElementById('mobileMenu').classList.remove('open');
  document.getElementById('hamburger').classList.remove('open');
}

/* ===== HAMBURGER ===== */
document.getElementById('hamburger').addEventListener('click', function() {
  this.classList.toggle('open');
  document.getElementById('mobileMenu').classList.toggle('open');
});

/* ===== NAVBAR SCROLL SHADOW ===== */
window.addEventListener('scroll', () => {
  const nav = document.getElementById('navbar');
  if (window.scrollY > 10) {
    nav.classList.add('scrolled');
  } else {
    nav.classList.remove('scrolled');
  }
});

/* ===== REVEAL ON SCROLL ===== */
let revealObserver;

function observeReveals() {
  const reveals = document.querySelectorAll('.reveal:not(.visible)');
  if (revealObserver) revealObserver.disconnect();

  revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });

  reveals.forEach(el => revealObserver.observe(el));
}

// Initial observe
observeReveals();

/* ===== RIPPLE EFFECT ===== */
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.ripple-btn');
  if (!btn) return;

  const circle = document.createElement('span');
  circle.classList.add('ripple');
  const rect = btn.getBoundingClientRect();
  const size = Math.max(rect.width, rect.height);
  circle.style.cssText = `
    width: ${size}px; height: ${size}px;
    left: ${e.clientX - rect.left - size / 2}px;
    top: ${e.clientY - rect.top - size / 2}px;
  `;
  btn.appendChild(circle);
  circle.addEventListener('animationend', () => circle.remove());
});

/* ===== PARALLAX HERO IMAGE (subtle) ===== */
window.addEventListener('scroll', () => {
  const heroImg = document.querySelector('.hero-img-wrap img');
  if (heroImg) {
    const scrollY = window.scrollY;
    heroImg.style.transform = `translateY(${scrollY * 0.08}px)`;
  }
});

/* ===== PRODUCT CARD TILT ===== */
document.querySelectorAll('.prod-card, .featured-card').forEach(card => {
  card.addEventListener('mousemove', function(e) {
    const rect = this.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;
    this.style.transform = `translateY(-6px) rotateY(${x * 6}deg) rotateX(${-y * 6}deg)`;
  });
  card.addEventListener('mouseleave', function() {
    this.style.transform = '';
    this.style.transition = 'transform 0.5s ease, box-shadow 0.35s';
    setTimeout(() => { this.style.transition = ''; }, 500);
  });
});

/* ===== ACTIVE NAV ON SECTION SHOW ===== */
// Initial state
document.querySelector('[data-section="home"]')?.classList.add('active');


// footer
document.getElementById("year").innerHTML = new Date().getFullYear();
