// =========================================================
// NOIR THREADS — BASE SCRIPT
// Loaded on every page. Currently just the shared toast
// notification helper used by several page-specific scripts
// (e.g. product.js after Add to Cart).
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