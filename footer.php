<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$exploreLinks = clarity_json_option('footer_explore_json', []);
$footerLinks = clarity_json_option('footer_links_json', []);
$showRss = clarity_bool(clarity_opt('footer_show_rss', '1'));
$showTravellings = clarity_bool(clarity_opt('footer_show_travellings', '1'));
$siteBase = rtrim((string) $this->options->siteUrl, '/');
$rssUrl = $siteBase . '/feed';
$beian = trim((string) clarity_opt('footer_beian', ''));
$gongan = trim((string) clarity_opt('footer_gongan', ''));
$enableUptime = clarity_bool(clarity_opt('footer_uptime_kuma', '0'));
$uptimeBadgeUrl = trim((string) clarity_opt('footer_uptime_kuma_badge', ''));
$uptimeStatusUrl = trim((string) clarity_opt('footer_uptime_kuma_url', ''));
$isLinksPage = clarity_get('isLinksPage', false);
$linksRandom = clarity_bool(clarity_opt('links_random', '1'));
$authError = '';
if (isset($_COOKIE['__typecho_notice_type']) && $_COOKIE['__typecho_notice_type'] === 'error' && isset($_COOKIE['__typecho_notice'])) {
  $noticeRaw = json_decode((string) $_COOKIE['__typecho_notice'], true);
  if (is_array($noticeRaw) && isset($noticeRaw[0])) {
    $authError = (string) $noticeRaw[0];
  } elseif (is_string($noticeRaw)) {
    $authError = $noticeRaw;
  }
}
$authForgotUrl = rtrim((string) $this->options->adminUrl, '/') . '/forgot-password.php';
$renderIcon = function ($icon) {
  $icon = trim((string) $icon);
  if ($icon === '') {
    return '';
  }
  if (preg_match('/icon-\[([a-z0-9]+)--([^\]]+)\]/i', $icon, $match)) {
    $prefix = strtolower($match[1]);
    $name = $match[2];
    $safeName = preg_replace('/[^a-z0-9\-:_]/i', '', $name);
    $iconName = $prefix . ':' . $safeName;
    $iconUrl = 'https://api.iconify.design/' . rawurlencode($prefix) . '/' . rawurlencode($safeName) . '.svg';
    return '<span class="iconify-mask ' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" data-icon="' . htmlspecialchars($iconName, ENT_QUOTES, 'UTF-8') . '" style="--icon-url:url(\'' . htmlspecialchars($iconUrl, ENT_QUOTES, 'UTF-8') . '\')"></span>';
  }
  if (preg_match('/\\bph\\s+ph-([a-z0-9-]+)\\b/i', $icon, $match)) {
    $name = strtolower($match[1]);
    $iconName = 'ph:' . $name;
    $iconUrl = 'https://api.iconify.design/ph/' . rawurlencode($name) . '.svg';
    return '<span class="iconify-mask ' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" data-icon="' . htmlspecialchars($iconName, ENT_QUOTES, 'UTF-8') . '" style="--icon-url:url(\'' . htmlspecialchars($iconUrl, ENT_QUOTES, 'UTF-8') . '\')"></span>';
  }
  if (preg_match('/^(https?:)?\\//i', $icon) || preg_match('/^\\.\\//', $icon) || preg_match('/^\\.\\.\\//', $icon)) {
    return '<img src="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" alt="" />';
  }
  return '<span class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '"></span>';
};
?>
      <footer class="z-footer">
        <nav class="footer-nav">
          <?php if ($showRss || $showTravellings || !empty($exploreLinks)): ?>
            <div class="footer-nav-group">
              <h3><?php echo htmlspecialchars('探索', ENT_QUOTES, 'UTF-8'); ?></h3>
              <menu>
                <?php if ($showRss): ?>
                  <li>
                    <a href="<?php echo htmlspecialchars($rssUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                      <span class="icon-[ph--rss-simple-bold]"></span>
                      <span class="nav-text">RSS 订阅</span>
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($showTravellings): ?>
                  <li>
                    <a href="https://www.travellings.cn/go.html" target="_blank">
                      <span class="icon-[ph--train-regional-bold]"></span>
                      <span class="nav-text">开往</span>
                    </a>
                  </li>
                <?php endif; ?>
                <?php foreach ($exploreLinks as $link): ?>
                  <?php
                  $url = $link['url'] ?? '#';
                  $text = $link['text'] ?? '';
                  $icon = $link['icon'] ?? '';
                  ?>
                  <li>
                    <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                      <?php if (!empty($icon)): ?>
                        <?php echo $renderIcon($icon); ?>
                      <?php endif; ?>
                      <span class="nav-text"><?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                  </li>
                <?php endforeach; ?>
              </menu>
            </div>
          <?php endif; ?>

          <?php if (!empty($footerLinks)): ?>
            <div class="footer-nav-group">
              <h3><?php echo htmlspecialchars('链接', ENT_QUOTES, 'UTF-8'); ?></h3>
              <menu>
                <?php foreach ($footerLinks as $link): ?>
                  <?php
                  $url = $link['url'] ?? '#';
                  $text = $link['text'] ?? '';
                  $icon = $link['icon'] ?? '';
                  ?>
                  <li>
                    <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                      <?php if (!empty($icon)): ?>
                        <?php echo $renderIcon($icon); ?>
                      <?php endif; ?>
                      <span class="nav-text"><?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                  </li>
                <?php endforeach; ?>
              </menu>
            </div>
          <?php endif; ?>

          <div class="footer-nav-group">
            <h3>信息</h3>
            <menu>
              <li>
                <a href="https://typecho.org" target="_blank">
                  <span class="icon-[ph--cube-bold]"></span>
                  <span class="nav-text">由 Typecho 驱动</span>
                </a>
              </li>
              <li>
                <a href="https://github.com/jkjoy/theme-clarity" target="_blank">
                  <span class="icon-[ph--mountains-bold]"></span>
                  <span class="nav-text">主题 Clarity <?php echo CLARITY_VERSION; ?></span>
                </a>
              </li>
              <?php if ($beian !== ''): ?>
                <li>
                  <a href="https://beian.miit.gov.cn/" target="_blank">
                    <img src="<?php $this->options->themeUrl('assets/images/icp.webp'); ?>" alt="ICP" class="beian-icon" />
                    <span class="nav-text"><?php echo htmlspecialchars($beian, ENT_QUOTES, 'UTF-8'); ?></span>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($gongan !== ''): ?>
                <li>
                  <a id="gongan-beian-link" href="https://beian.mps.gov.cn/#/query/webSearch" target="_blank">
                    <img src="<?php $this->options->themeUrl('assets/images/gonganbeian.png'); ?>" alt="公安备案" class="beian-icon" />
                    <span id="gongan-beian-text" class="nav-text"><?php echo htmlspecialchars($gongan, ENT_QUOTES, 'UTF-8'); ?></span>
                  </a>
                  <script>
                    (function () {
                      const text = document.getElementById('gongan-beian-text')?.innerText || '';
                      const code = text.replace(/[^0-9]/g, '');
                      if (code) {
                        document.getElementById('gongan-beian-link').href = 'https://beian.mps.gov.cn/#/query/webSearch?code=' + code;
                      }
                    })();
                  </script>
                </li>
              <?php endif; ?>
              <?php if ($enableUptime): ?>
                <?php if ($uptimeBadgeUrl !== ''): ?>
                  <?php $uptimeLink = $uptimeStatusUrl !== '' ? $uptimeStatusUrl : $uptimeBadgeUrl; ?>
                  <li class="xhhaocom-dataStatistics-v2-uptime-kuma uptime-kuma-badge">
                    <a href="<?php echo htmlspecialchars($uptimeLink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                      <img src="<?php echo htmlspecialchars($uptimeBadgeUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="Uptime Kuma" loading="lazy" />
                    </a>
                  </li>
                <?php else: ?>
                  <li class="xhhaocom-dataStatistics-v2-uptime-kuma"></li>
                <?php endif; ?>
              <?php endif; ?>
            </menu>
          </div>
        </nav>

        <div class="footer-inject">
          <?php $this->footer(); ?>
        </div>

        <p>© <span><?php echo date('Y'); ?></span> <span><?php echo htmlspecialchars($this->options->title, ENT_QUOTES, 'UTF-8'); ?></span></p>
      </footer>
    </main>

    <?php $this->need('aside.php'); ?>
  </div>

  <div id="z-panel">
    <button id="toggle-sidebar" aria-label="切换菜单" onclick="(function (btn) { var sb = document.getElementById('z-sidebar'), mk = document.getElementById('z-sidebar-bgmask'); sb.classList.toggle('show'); mk.classList.toggle('hidden'); btn.classList.toggle('active'); })(this)">
      <span class="icon-[ph--sidebar-duotone] rtl-flip" style="display:block"></span>
    </button>
    <?php if (clarity_get('showAside', true) && clarity_bool(clarity_opt('aside_enable', '1'))): ?>
      <button id="toggle-aside" aria-label="切换侧边栏" onclick="(function (btn) { var as = document.getElementById('z-aside'), mk = document.getElementById('z-aside-bgmask'); as.classList.toggle('show'); mk.classList.toggle('hidden'); btn.classList.toggle('active'); })(this)">
        <span class="icon-[ph--align-right-duotone] rtl-flip" style="display:block"></span>
      </button>
    <?php endif; ?>
    <?php if ($isLinksPage && $linksRandom): ?>
      <button id="mobile-random-link-btn" aria-label="随机穿梭" onclick="randomVisitLink()">
        <span class="icon-[ph--shuffle-bold]" style="display:block"></span>
      </button>
    <?php endif; ?>
    <button id="back-to-top" aria-label="返回顶部" style="display:none">
      <span class="icon-[ph--arrow-up-bold]" style="display:block"></span>
    </button>
  </div>

  <button id="pc-back-to-top" class="pc-back-to-top" onclick="window.scrollTo({ top: 0, behavior: 'smooth' })">
    <svg class="svgIcon" viewBox="0 0 384 512">
      <path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"></path>
    </svg>
  </button>

  <div id="search-modal" class="search-modal" style="display:none">
    <div class="search-modal-overlay" onclick="SearchWidget.close()"></div>
    <div class="search-modal-content">
      <form class="search-form" method="get" action="<?php echo $this->options->siteUrl; ?>">
        <input type="text" name="s" class="search-input" placeholder="输入关键词" autocomplete="off" />
        <button class="search-submit" type="submit"><span class="icon-[ph--magnifying-glass-bold]"></span></button>
      </form>
    </div>
  </div>

  <div id="auth-modal" class="auth-modal" style="display:none" aria-hidden="true">
    <div class="auth-modal-overlay" data-auth-close></div>
    <div class="auth-modal-content" role="dialog" aria-modal="true" aria-labelledby="auth-modal-title">
      <button type="button" class="auth-modal-close" aria-label="关闭登录窗口" data-auth-close>
        <span class="icon-[ph--x-bold]"></span>
      </button>
      <h3 id="auth-modal-title" class="auth-modal-title">登录</h3>
      <form class="auth-form" method="post" action="<?php $this->options->loginAction(); ?>">
        <?php if ($authError !== ''): ?>
          <div class="auth-alert" role="alert"><?php echo htmlspecialchars($authError, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <label class="auth-form-label" for="auth-name">用户名或邮箱</label>
        <input id="auth-name" class="auth-input" type="text" name="name" autocomplete="username" required />

        <label class="auth-form-label" for="auth-password">密码</label>
        <input id="auth-password" class="auth-input" type="password" name="password" autocomplete="current-password" required />

        <label class="auth-form-check">
          <input type="checkbox" name="remember" value="1" />
          <span>下次自动登录</span>
        </label>

        <input id="auth-referer" type="hidden" name="referer" value="<?php echo htmlspecialchars($this->options->siteUrl, ENT_QUOTES, 'UTF-8'); ?>" />

        <button class="auth-submit" type="submit">登录</button>
      </form>
      <div class="auth-extra-links">
        <a href="<?php echo htmlspecialchars($authForgotUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">忘记密码</a>
        <?php if ($this->options->allowRegister): ?>
          <a href="<?php $this->options->registerUrl(); ?>">注册账号</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    window.updateModalBodyLock = window.updateModalBodyLock || function () {
      const hasOpenModal = Array.from(document.querySelectorAll('.search-modal, .auth-modal')).some((modal) => modal && modal.style.display === 'flex');
      document.body.style.overflow = hasOpenModal ? 'hidden' : '';
    };

    window.SearchWidget = window.SearchWidget || {
      open: function () {
        const modal = document.getElementById('search-modal');
        if (!modal) return;
        modal.style.display = 'flex';
        window.updateModalBodyLock();
        const input = modal.querySelector('.search-input');
        if (input) input.focus();
      },
      close: function () {
        const modal = document.getElementById('search-modal');
        if (!modal) return;
        modal.style.display = 'none';
        window.updateModalBodyLock();
      }
    };

    window.AuthModal = window.AuthModal || {
      open: function () {
        const modal = document.getElementById('auth-modal');
        if (!modal) return;
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        const refererInput = document.getElementById('auth-referer');
        if (refererInput) {
          refererInput.value = window.location.href;
        }
        window.updateModalBodyLock();
        const input = modal.querySelector('#auth-name');
        if (input) input.focus();
      },
      close: function () {
        const modal = document.getElementById('auth-modal');
        if (!modal) return;
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        window.updateModalBodyLock();
      }
    };

    const authShouldOpen = <?php echo ($authError !== '') ? 'true' : 'false'; ?>;
    if (authShouldOpen) {
      window.AuthModal.open();
    }

    document.querySelectorAll('[data-auth-trigger]').forEach((trigger) => {
      trigger.addEventListener('click', function (event) {
        event.preventDefault();
        window.AuthModal.open();
      });
    });

    document.querySelectorAll('[data-auth-close]').forEach((trigger) => {
      trigger.addEventListener('click', function () {
        window.AuthModal.close();
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') return;
      const authModal = document.getElementById('auth-modal');
      if (authModal && authModal.style.display === 'flex') {
        window.AuthModal.close();
        return;
      }
      const searchModal = document.getElementById('search-modal');
      if (searchModal && searchModal.style.display === 'flex') {
        window.SearchWidget.close();
      }
    });

    window.randomVisitLink = window.randomVisitLink || function () {
      const list = window.clarityLinks || [];
      if (!list.length) return;
      const item = list[Math.floor(Math.random() * list.length)];
      if (window.openShuttle) {
        window.openShuttle({
          url: item.url,
          name: item.name || item.text || item.title || '友链',
          logo: item.logo || item.icon || '',
          desc: item.desc || item.description || ''
        });
      } else if (item.url) {
        window.open(item.url, '_blank', 'noopener,noreferrer');
      }
    };

    window.switchTab = window.switchTab || function (btn) {
      const container = document.getElementById('link-tabs');
      if (!container) return;
      const target = btn.dataset.tab;
      container.querySelectorAll('.custom-tab-item').forEach((el) => el.classList.remove('active'));
      container.querySelectorAll('.custom-tab-pane').forEach((el) => el.classList.remove('active'));
      btn.classList.add('active');
      const pane = document.getElementById(target);
      if (pane) pane.classList.add('active');
    };

    document.querySelectorAll('.copy-btn').forEach((btn) => {
      btn.addEventListener('click', async () => {
        const text = btn.dataset.copy || '';
        if (!text) return;
        try {
          if (window.clarityCopyText) {
            await window.clarityCopyText(text);
          } else if (navigator.clipboard && navigator.clipboard.writeText) {
            await navigator.clipboard.writeText(text);
          }
          btn.classList.add('copied');
          setTimeout(() => btn.classList.remove('copied'), 2000);
        } catch (e) {}
      });
    });
  </script>
</body>
</html>
