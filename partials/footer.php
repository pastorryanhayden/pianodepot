<?php
$cfg = pd_config();
?>
<footer id="footer" class="site-footer" role="contentinfo">
    <p><?= htmlspecialchars($cfg['site_name'], ENT_QUOTES) ?></p>
    <p><?= htmlspecialchars($cfg['address'], ENT_QUOTES) ?></p>
    <p><a href="tel:<?= htmlspecialchars($cfg['phone_tel'], ENT_QUOTES) ?>"><?= htmlspecialchars($cfg['phone'], ENT_QUOTES) ?></a></p>
</footer>
</body>
</html>
