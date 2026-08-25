<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-col">
      <div class="logo footer-logo">NOIR<span>THREADS</span></div>
      <p>Everyday tees, made to last. Designed for people who wear their shirts, not the other way around.</p>
    </div>
    <div class="footer-col">
      <h4>Shop</h4>
      <a href="/tshirt-store/products.php?category=1">Men</a>
      <a href="/tshirt-store/products.php?category=2">Women</a>
      <a href="/tshirt-store/products.php?category=3">Unisex</a>
      <a href="/tshirt-store/products.php?category=4">Kids</a>
    </div>
    <div class="footer-col">
      <h4>Help</h4>
      <a href="/tshirt-store/delivery.php">Delivery Info</a>
      <a href="/tshirt-store/returns.php">Returns</a>
      <a href="/tshirt-store/contact.php">Contact Us</a>
    </div>
    <div class="footer-col">
      <h4>Get in touch</h4>
      <p>hello@noirthreads.lk</p>
      <p>+94 77 123 4567</p>
    </div>
  </div>
  <div class="footer-bottom">
    &copy; <?php echo date("Y"); ?> NOIR THREADS. University E-Commerce Project.
  </div>
</footer>
<script src="/tshirt-store/js/base.js"></script>
<?php if (!empty($page_js)): foreach ($page_js as $js): ?>
<script src="/tshirt-store/js/<?php echo htmlspecialchars($js); ?>"></script>
<?php endforeach; endif; ?>
</body>
</html>