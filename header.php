<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$siteTitle = $this->options->title;
$siteDesc = $this->options->description;
$sep = clarity_opt('title_type', 'vertical') === 'across' ? ' - ' : ' | ';
$pageTitle = clarity_get('pageTitle');

if (!empty($pageTitle)) {
    $documentTitle = $pageTitle . $sep . $siteTitle;
} elseif ($this->is('post') || $this->is('page')) {
    $documentTitle = $this->title . $sep . $siteTitle;
} elseif ($this->is('author')) {
    $documentTitle = $this->author->screenName . ' 的文章' . $sep . $siteTitle;
} else {
    $documentTitle = $siteDesc ? ($siteTitle . $sep . $siteDesc) : $siteTitle;
}

$documentTitle = html_entity_decode((string) $documentTitle, ENT_QUOTES, 'UTF-8');

$metaDescription = $siteDesc;
if ($this->is('post') || $this->is('page')) {
    $metaDescription = clarity_get_excerpt($this, 120) ?: $siteDesc;
}

$canonical = null;
if ($this->is('post') || $this->is('page')) {
    $canonical = $this->permalink;
} elseif (method_exists($this, 'getArchiveUrl')) {
    $canonical = $this->getArchiveUrl();
}

$logoFallback = \Typecho\Common::url('assets/images/logo.svg', $this->options->themeUrl);
$logo = clarity_site_logo($logoFallback);
$primaryColor = clarity_opt('primary_color', '#3b82f6');
$accentColor = clarity_opt('accent_color', '#60a5fa');
$themeMode = clarity_opt('theme_mode', 'light');
$imgAlt = clarity_bool(clarity_opt('img_alt', '1')) ? 'true' : 'false';
$enableFancy = clarity_bool(clarity_opt('enable_fancybox', '0')) ? 'true' : 'false';
$navActive = clarity_bool(clarity_opt('nav_active_indicator', '1')) ? 'true' : 'false';
$pageTransition = clarity_opt('page_transition', 'fade-scale');
$preconnect = clarity_parse_lines((string) clarity_opt('preconnect_urls', ''));
$customHeadHtml = trim((string) clarity_opt('headhtml', ''));
?>
<!doctype html>
<html lang="zh-CN" data-page-transition="<?php echo htmlspecialchars($pageTransition, ENT_QUOTES, 'UTF-8'); ?>" data-nav-active="<?php echo $navActive; ?>">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($documentTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <?php if (!empty($metaDescription)): ?>
      <meta name="description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php endif; ?>
    <?php if (!empty($canonical)): ?>
      <link rel="canonical" href="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php endif; ?>
    <link rel="icon" href="<?php echo htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" />
    <link rel="apple-touch-icon" href="<?php echo htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" />
    <link rel="alternate" type="application/rss+xml" href="<?php $this->options->feedUrl(); ?>" title="<?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?> - RSS" />
    <meta property="og:site_name" content="<?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:locale" content="zh_CN" />
    <meta property="og:title" content="<?php echo htmlspecialchars($documentTitle, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php if (!empty($metaDescription)): ?>
      <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>" />
      <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php endif; ?>
    <?php if (!empty($canonical)): ?>
      <meta property="og:url" content="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php endif; ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo htmlspecialchars($documentTitle, ENT_QUOTES, 'UTF-8'); ?>" />

    <?php foreach ($preconnect as $url): ?>
      <link rel="preconnect" href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" crossorigin />
      <link rel="dns-prefetch" href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php endforeach; ?>

    <link rel="preload" href="<?php $this->options->themeUrl('assets/fonts/JetBrainsMono-Regular/6096f8ea8ec1ddec37a08da471025894.woff2'); ?>" as="font" type="font/woff2" crossorigin />
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/fonts/JetBrainsMono-Regular/jetBrainsMonoRegular.css'); ?>?v=<?php echo CLARITY_VERSION; ?>" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/fonts/JetBrainsMono-Italic/jetBrainsMonoItalic.css'); ?>?v=<?php echo CLARITY_VERSION; ?>" media="print" onload="this.media='all'" />

    <?php $logoFontCss = trim((string) clarity_opt('logo_font_css', '')); ?>
    <?php if ($logoFontCss !== ''): ?>
      <style><?php echo $logoFontCss; ?></style>
    <?php endif; ?>

    <?php $linksTitleFontCss = trim((string) clarity_opt('links_title_font_css', '')); ?>
    <?php if ($linksTitleFontCss !== ''): ?>
      <style><?php echo $linksTitleFontCss; ?></style>
    <?php endif; ?>

    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/dist/vendor.css'); ?>?v=<?php echo CLARITY_VERSION; ?>" />
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/dist/main.css'); ?>?v=<?php echo CLARITY_VERSION; ?>" />
    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/dist/custom.css'); ?>?v=<?php echo CLARITY_VERSION; ?>" />
    <script>
      (function () {
        function legacyCopy(text) {
          try {
            var textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'fixed';
            textarea.style.top = '-1000px';
            textarea.style.opacity = '0';
            var container = document.body || document.documentElement;
            container.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, 99999);
            var ok = false;
            try {
              ok = document.execCommand('copy');
            } catch (err) {
              ok = false;
            }
            container.removeChild(textarea);
            return ok;
          } catch (err) {
            return false;
          }
        }

        function ensureClipboard() {
          if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
            return;
          }
          var fallback = {
            writeText: function (text) {
              return new Promise(function (resolve, reject) {
                legacyCopy(text) ? resolve() : reject(new Error('copy failed'));
              });
            }
          };
          try {
            if (!navigator.clipboard) {
              navigator.clipboard = fallback;
            } else if (typeof navigator.clipboard.writeText !== 'function') {
              navigator.clipboard.writeText = fallback.writeText;
            }
          } catch (err) {
            try {
              Object.defineProperty(navigator, 'clipboard', { value: fallback });
            } catch (err2) {}
          }
        }

        window.clarityCopyText = function (text) {
          ensureClipboard();
          if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
            return navigator.clipboard.writeText(text);
          }
          return new Promise(function (resolve, reject) {
            legacyCopy(text) ? resolve() : reject(new Error('copy failed'));
          });
        };
      })();
    </script>
    <script type="module" src="<?php $this->options->themeUrl('assets/dist/main.js'); ?>?v=<?php echo CLARITY_VERSION; ?>"></script>

    <style>
      :root,
      :root.light,
      :root.dark {
        --c-primary: <?php echo htmlspecialchars($primaryColor, ENT_QUOTES, 'UTF-8'); ?> !important;
        --c-primary-soft: color-mix(in srgb, <?php echo htmlspecialchars($primaryColor, ENT_QUOTES, 'UTF-8'); ?>, transparent 85%) !important;
        --c-accent: <?php echo htmlspecialchars($accentColor, ENT_QUOTES, 'UTF-8'); ?> !important;
      }
    </style>

    <script>
      window.themeConfig = window.themeConfig || {};
      window.themeConfig.custom = window.themeConfig.custom || {};
      window.themeConfig.custom.img_alt = <?php echo $imgAlt; ?>;
      window.themeConfig.custom.enable_fancybox = <?php echo $enableFancy; ?>;
      window.themeConfig.style = window.themeConfig.style || {};
      window.themeConfig.style.theme_mode = "<?php echo htmlspecialchars($themeMode, ENT_QUOTES, 'UTF-8'); ?>";
    </script>
    <script>
      (function () {
        const theme = localStorage.getItem('theme') || window.themeConfig?.style?.theme_mode || 'system';
        const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (isDark) {
          document.documentElement.classList.add('dark');
        }
      })();
    </script>
    <?php if ($customHeadHtml !== ''): ?>
      <?php echo $customHeadHtml; ?>
    <?php endif; ?>
    <?php $this->header(); ?>
  </head>
  <body id="clarity-root">
    <div id="z-sidebar-bgmask" class="hidden" onclick="document.getElementById('z-sidebar').classList.remove('show');this.classList.add('hidden');document.getElementById('toggle-sidebar')?.classList.remove('active');"></div>

    <div id="z-aside-bgmask" class="hidden" onclick="document.getElementById('z-aside').classList.remove('show');this.classList.add('hidden');document.getElementById('toggle-aside')?.classList.remove('active');"></div>

    <?php $this->need('sidebar.php'); ?>

    <div id="content">
      <main id="main-content">
