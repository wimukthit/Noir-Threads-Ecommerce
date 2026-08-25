// =========================================================
// HOME PAGE — hero slider (sliding photos)
// Loaded only on index.php (in addition to base.js)
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