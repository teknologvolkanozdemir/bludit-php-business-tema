<?php

function businessThemeEscape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$contact = require __DIR__ . '/contact.php';
$contactEmail = filter_var($contact['email'] ?? '', FILTER_VALIDATE_EMAIL);
$contactPhone = preg_replace('/[^0-9+().\-\s]/', '', (string) ($contact['phone'] ?? ''));
$contactAddress = trim((string) ($contact['address'] ?? ''));
$staticPages = isset($staticContent) && is_array($staticContent) ? $staticContent : [];
$contentPages = isset($content) && is_array($content) ? $content : [];
$siteTitle = $site->title();
$siteDescription = $site->description();
$isHome = ($WHERE_AM_I ?? 'home') === 'home';

?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo businessThemeEscape($siteDescription); ?>">
    <title><?php echo Theme::title(); ?></title>
    <?php Theme::plugins('siteHead'); ?>
    <?php echo Theme::css('css/style.css'); ?>
</head>
<body>
    <a class="skip-link" href="#main-content">İçeriğe geç</a>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="<?php echo DOMAIN; ?>" aria-label="<?php echo businessThemeEscape($siteTitle); ?> ana sayfa">
                <?php echo businessThemeEscape($siteTitle); ?>
            </a>
            <nav class="site-nav" aria-label="Ana menü">
                <a href="<?php echo DOMAIN; ?>">Ana sayfa</a>
                <?php foreach ($staticPages as $navPage): ?>
                    <a href="<?php echo businessThemeEscape($navPage->permalink()); ?>">
                        <?php echo businessThemeEscape($navPage->title()); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>

    <main id="main-content">
        <?php if ($isHome): ?>
            <section class="hero">
                <div class="container hero-content">
                    <p class="eyebrow">İşinizi geleceğe taşıyoruz</p>
                    <h1><?php echo businessThemeEscape($siteTitle); ?></h1>
                    <p class="hero-description"><?php echo businessThemeEscape($siteDescription); ?></p>
                    <?php if ($contactEmail): ?>
                        <a class="button" href="mailto:<?php echo businessThemeEscape($contactEmail); ?>">Bizimle iletişime geçin</a>
                    <?php endif; ?>
                </div>
            </section>

            <section class="portfolio-section container" aria-labelledby="portfolio-title">
                <div class="section-heading">
                    <p class="eyebrow">Neler yapıyoruz?</p>
                    <h2 id="portfolio-title">Ürünlerimiz ve çalışmalarımız</h2>
                    <p>İşletmenizi ve sunduğunuz çözümleri yakından tanıyın.</p>
                </div>
                <?php if ($contentPages): ?>
                    <div class="portfolio-grid">
                        <?php foreach ($contentPages as $portfolioPage): ?>
                            <article class="portfolio-card">
                                <h3>
                                    <a href="<?php echo businessThemeEscape($portfolioPage->permalink()); ?>">
                                        <?php echo businessThemeEscape($portfolioPage->title()); ?>
                                    </a>
                                </h3>
                                <div class="card-excerpt"><?php echo $portfolioPage->contentBreak(); ?></div>
                                <a class="text-link" href="<?php echo businessThemeEscape($portfolioPage->permalink()); ?>">
                                    Detayları görüntüle <span aria-hidden="true">→</span>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="empty-state">Ürün ve çalışmalarınızı tanıtmak için Bludit yönetim panelinden içerik ekleyin.</p>
                <?php endif; ?>
            </section>
        <?php elseif (($WHERE_AM_I ?? '') === 'page' && isset($page)): ?>
            <article class="container page-content">
                <p class="eyebrow"><?php echo businessThemeEscape($siteTitle); ?></p>
                <h1><?php echo businessThemeEscape($page->title()); ?></h1>
                <div class="rich-content"><?php echo $page->content(); ?></div>
            </article>
        <?php else: ?>
            <section class="container portfolio-section">
                <div class="section-heading">
                    <p class="eyebrow"><?php echo businessThemeEscape($siteTitle); ?></p>
                    <h1><?php echo Theme::title(); ?></h1>
                </div>
                <?php if ($contentPages): ?>
                    <div class="portfolio-grid">
                        <?php foreach ($contentPages as $listPage): ?>
                            <article class="portfolio-card">
                                <h2><a href="<?php echo businessThemeEscape($listPage->permalink()); ?>"><?php echo businessThemeEscape($listPage->title()); ?></a></h2>
                                <div class="card-excerpt"><?php echo $listPage->contentBreak(); ?></div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="empty-state">Gösterilecek içerik bulunamadı.</p>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-about">
                <a class="brand footer-brand" href="<?php echo DOMAIN; ?>"><?php echo businessThemeEscape($siteTitle); ?></a>
                <p><?php echo businessThemeEscape($siteDescription); ?></p>
            </div>
            <?php if ($contactEmail || $contactPhone || $contactAddress !== ''): ?>
                <section class="footer-contact" aria-labelledby="contact-title">
                    <h2 id="contact-title">İletişim</h2>
                    <?php if ($contactEmail): ?>
                        <p><a href="mailto:<?php echo businessThemeEscape($contactEmail); ?>"><?php echo businessThemeEscape($contactEmail); ?></a></p>
                    <?php endif; ?>
                    <?php if ($contactPhone !== ''): ?>
                        <p><a href="tel:<?php echo businessThemeEscape(preg_replace('/\s+/', '', $contactPhone)); ?>"><?php echo businessThemeEscape($contactPhone); ?></a></p>
                    <?php endif; ?>
                    <?php if ($contactAddress !== ''): ?>
                        <p><?php echo nl2br(businessThemeEscape($contactAddress)); ?></p>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
            <p class="copyright">&copy; <?php echo date('Y'); ?> <?php echo businessThemeEscape($siteTitle); ?></p>
        </div>
    </footer>
    <?php Theme::plugins('siteBody'); ?>
</body>
</html>
