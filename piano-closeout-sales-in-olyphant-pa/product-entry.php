<?php
$hasImage = !empty($closeoutProduct['image']);
[$lead, $body] = array_pad(explode('|', $closeoutProduct['description'], 2), 2, null);
?>
<?php if (!empty($closeoutProduct['section_heading'])): ?>
<h2 class="closeout-products__heading"><?= htmlspecialchars($closeoutProduct['section_heading']) ?></h2>
<?php endif; ?>
<article class="closeout-product<?= $hasImage ? ' closeout-product--with-image' : ' closeout-product--without-image' ?>" data-closeout-product>
    <?php if ($hasImage): ?>
    <figure class="closeout-product__media">
        <img src="<?= htmlspecialchars($closeoutProduct['image']) ?>" alt="<?= htmlspecialchars($closeoutProduct['image_alt'] ?? $closeoutProduct['title']) ?>" loading="lazy">
    </figure>
    <?php endif; ?>
    <div class="closeout-product__copy">
        <h3><?= htmlspecialchars($closeoutProduct['title']) ?><?php if (!empty($closeoutProduct['price_condition'])): ?> – <?= htmlspecialchars($closeoutProduct['price_condition']) ?><?php endif; ?></h3>
        <p><?php if ($body !== null): ?><strong><?= htmlspecialchars($lead) ?></strong><br><?= htmlspecialchars($body) ?><?php else: ?><?= htmlspecialchars($lead) ?><?php endif; ?></p>
        <?php if (!empty($closeoutProduct['details_url'])): ?>
        <p><a class="closeout-product__details" href="<?= htmlspecialchars($closeoutProduct['details_url']) ?>"<?= str_starts_with($closeoutProduct['details_url'], 'http') ? ' target="_blank" rel="noopener noreferrer"' : '' ?>><?= htmlspecialchars($closeoutProduct['details_label'] ?? 'Learn More') ?></a></p>
        <?php endif; ?>
        <p class="closeout-product__availability"><?= htmlspecialchars($closeoutProduct['availability']) ?> <a href="tel:<?= htmlspecialchars($closeoutProduct['phone']) ?>"><?= htmlspecialchars($closeoutProduct['phone_display']) ?></a></p>
    </div>
</article>
