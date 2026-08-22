// =========================================================
// Hero slider (sliding photos on the home page)
// =========================================================
(function () {
  const slides = document.querySelectorAll('.slide');
  const dotsWrap = document.querySelector('.slider-dots');
  if (!slides.length) return;

  let current = 0;
  let timer;

  function goTo(index) {
    slides[current].classList.remove('active');
    dotsWrap.children[current].classList.remove('active');
    current = (index + slides.length) % slides.length;
    slides[current].classList.add('active');
    dotsWrap.children[current].classList.add('active');
  }

  function next() { goTo(current + 1); }
  function prev() { goTo(current - 1); }

  function startAuto() {
    timer = setInterval(next, 5000);
  }
  function stopAuto() {
    clearInterval(timer);
  }

  // Build dots
  slides.forEach((_, i) => {
    const dot = document.createElement('button');
    if (i === 0) dot.classList.add('active');
    dot.addEventListener('click', () => { goTo(i); stopAuto(); startAuto(); });
    dotsWrap.appendChild(dot);
  });

  document.querySelector('.slider-arrow.next')?.addEventListener('click', () => { next(); stopAuto(); startAuto(); });
  document.querySelector('.slider-arrow.prev')?.addEventListener('click', () => { prev(); stopAuto(); startAuto(); });

  startAuto();
})();

// =========================================================
// Quantity stepper (product detail page)
// =========================================================
function changeQty(delta) {
  const input = document.getElementById('qtyInput');
  if (!input) return;
  let val = parseInt(input.value || '1', 10) + delta;
  const max = parseInt(input.max || '99', 10);
  if (val < 1) val = 1;
  if (val > max) val = max;
  input.value = val;
}

// =========================================================
// Simple client-side validation feedback for checkout phone field
// =========================================================
document.addEventListener('DOMContentLoaded', function () {
  const phone = document.getElementById('phoneInput');
  if (phone) {
    phone.addEventListener('input', function () {
      this.value = this.value.replace(/[^0-9+ ]/g, '');
    });
  }
});

// =========================================================
// Toast notifications
// =========================================================
function showToast(message, type) {
  const container = document.getElementById('toast-container');
  if (!container) return;
  const toast = document.createElement('div');
  toast.className = 'toast' + (type === 'error' ? ' toast-error' : '');
  toast.textContent = message;
  container.appendChild(toast);
  setTimeout(() => {
    toast.classList.add('toast-out');
    setTimeout(() => toast.remove(), 250);
  }, 2800);
}

// =========================================================
// Add to cart via AJAX (no page reload) + toast
// =========================================================
document.addEventListener('DOMContentLoaded', function () {
  const addForm = document.getElementById('addToCartForm');
  if (addForm) {
    addForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(addForm);
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showToast(data.message || 'Added to cart!');
            const badge = document.querySelector('.cart-badge');
            if (badge && typeof data.cart_count !== 'undefined') {
              badge.textContent = data.cart_count;
            }
          } else {
            showToast(data.message || 'Could not add to cart.', 'error');
          }
        })
        .catch(() => showToast('Something went wrong.', 'error'));
    });
  }
});

// =========================================================
// Product gallery: thumbnails, prev/next arrows, touch swipe
// =========================================================
document.addEventListener('DOMContentLoaded', function () {
  const mainImage = document.getElementById('mainImage');
  const thumbs = document.querySelectorAll('.pd-thumb');
  if (!mainImage || thumbs.length === 0) return;

  const images = Array.from(thumbs).map(t => t.dataset.src);
  let current = 0;

  function showImage(index) {
    current = (index + images.length) % images.length;
    mainImage.src = images[current];
    thumbs.forEach((t, i) => t.classList.toggle('active', i === current));
  }

  thumbs.forEach((thumb, i) => {
    thumb.addEventListener('click', () => showImage(i));
  });

  document.getElementById('pdNext')?.addEventListener('click', () => showImage(current + 1));
  document.getElementById('pdPrev')?.addEventListener('click', () => showImage(current - 1));

  // Touch swipe support
  const wrap = document.getElementById('mainImageWrap');
  let touchStartX = 0;
  wrap.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
  wrap.addEventListener('touchend', e => {
    const delta = e.changedTouches[0].screenX - touchStartX;
    if (Math.abs(delta) > 40) {
      delta < 0 ? showImage(current + 1) : showImage(current - 1);
    }
  }, { passive: true });
});

// =========================================================
// Image zoom on hover (product detail page)
// =========================================================
document.addEventListener('DOMContentLoaded', function () {
  const wrap = document.querySelector('.zoom-wrap');
  if (!wrap) return;
  const img = wrap.querySelector('img');

  wrap.addEventListener('mousemove', function (e) {
    const rect = wrap.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    img.style.transformOrigin = x + '% ' + y + '%';
  });
  wrap.addEventListener('mouseenter', () => wrap.classList.add('zoomed'));
  wrap.addEventListener('mouseleave', () => wrap.classList.remove('zoomed'));
});
