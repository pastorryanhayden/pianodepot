<?php
$current = $enspireProduct ?? '';
$items = [
    'cl' => ['label' => 'Enspire CL', 'href' => '/product/enspire-cl/'],
    'st' => ['label' => 'Enspire ST', 'href' => '/product/enspire-st/'],
    'pro' => ['label' => 'Enspire Pro', 'href' => '/product/enspire-pro/'],
    'dkc' => ['label' => 'DKC-900 Upgrade Kit', 'href' => '/product/dkc-900-upgrade-kit/'],
];
?>
<nav class="piano_series-links enspire-series-nav" aria-label="Disklavier models">
  <ul>
    <?php foreach ($items as $key => $item): ?>
      <li class="<?= $key === $current ? 'current-product-item' : '' ?>">
        <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES) ?>"><?= htmlspecialchars($item['label']) ?></a>
      </li>
    <?php endforeach; ?>
  </ul>
</nav>
