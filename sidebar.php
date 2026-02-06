<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$logoFallback = \Typecho\Common::url('assets/images/logo.svg', $this->options->themeUrl);
$logo = clarity_site_logo($logoFallback);
$showTitle = clarity_bool(clarity_opt('show_title', '1'));
$subtitle = trim((string) clarity_opt('subtitle', $this->options->description));
$emojiTail = trim((string) clarity_opt('emoji_tail', ''));
$menuItems = clarity_menu_items();
$menuIconInvert = clarity_bool(clarity_opt('menu_icon_invert', '0'));
$socialItems = clarity_json_option('social_json', []);
$userAuth = clarity_bool(clarity_opt('user_auth', '0'));
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
<aside id="z-sidebar" class="<?php echo $menuIconInvert ? 'menu-icon-invert' : ''; ?>">
  <div class="clarity-header">
    <?php if ($emojiTail !== ''): ?>
      <div class="emoji-tail">
        <?php
        $emojis = array_filter(array_map('trim', explode(',', $emojiTail)));
        foreach ($emojis as $index => $emoji):
        ?>
          <span class="split-char" style="--delay: <?php echo ($index * 0.6 - 3); ?>s"><?php echo $emoji; ?></span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <a href="<?php echo $this->options->siteUrl; ?>">
      <?php if ($logo): ?>
        <img src="<?php echo htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" class="clarity-logo<?php echo $showTitle ? ' circle' : ''; ?>" alt="<?php echo htmlspecialchars($this->options->title, ENT_QUOTES, 'UTF-8'); ?>" />
      <?php endif; ?>
    </a>

    <?php if ($showTitle): ?>
      <div class="clarity-text">
        <div class="header-title">
          <?php
          $chars = preg_split('//u', $this->options->title, -1, PREG_SPLIT_NO_EMPTY);
          foreach ($chars as $idx => $char):
          ?>
            <span class="split-char" style="--delay: <?php echo (($idx + 1) * 0.1); ?>s"><?php echo htmlspecialchars($char, ENT_QUOTES, 'UTF-8'); ?></span>
          <?php endforeach; ?>
        </div>
        <div class="header-subtitle"><?php echo htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8'); ?></div>
      </div>
    <?php endif; ?>
  </div>

  <nav class="sidebar-nav scrollcheck-y">
    <div class="search-btn sidebar-nav-item gradient-card" onclick="SearchWidget.open()">
      <span class="icon-[ph--magnifying-glass-bold]"></span>
      <span class="nav-text">搜索</span>
      <kbd class="search-shortcut">Ctrl+K</kbd>
    </div>

    <?php if (!empty($menuItems)): ?>
      <menu>
        <?php foreach ($menuItems as $item): ?>
          <?php
          $children = isset($item['children']) && is_array($item['children']) ? $item['children'] : [];
          $hasChildren = !empty($children);
          $url = $item['url'] ?? '#';
          $text = $item['text'] ?? '';
          $icon = $item['icon'] ?? '';
          $target = $item['target'] ?? '';
          ?>
          <li class="<?php echo $hasChildren ? 'has-submenu' : ''; ?>">
            <?php if ($hasChildren): ?>
              <a class="sidebar-nav-item has-dropdown" href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $target ? ' target="' . htmlspecialchars($target, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
                <?php if ($icon): ?>
                  <?php echo $renderIcon($icon); ?>
                <?php endif; ?>
                <span class="nav-text"><?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="icon-[ph--caret-down-bold] dropdown-arrow"></span>
              </a>
              <div class="dropdown-menu">
                <?php foreach ($children as $child): ?>
                  <?php
                  $childUrl = $child['url'] ?? '#';
                  $childText = $child['text'] ?? '';
                  $childIcon = $child['icon'] ?? '';
                  $childTarget = $child['target'] ?? '';
                  ?>
                  <a class="dropdown-item" href="<?php echo htmlspecialchars($childUrl, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $childTarget ? ' target="' . htmlspecialchars($childTarget, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
                    <?php if ($childIcon): ?>
                      <?php echo $renderIcon($childIcon); ?>
                    <?php endif; ?>
                    <span class="nav-text"><?php echo htmlspecialchars($childText, ENT_QUOTES, 'UTF-8'); ?></span>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <a class="sidebar-nav-item" href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $target ? ' target="' . htmlspecialchars($target, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
                <?php if ($icon): ?>
                  <?php echo $renderIcon($icon); ?>
                <?php endif; ?>
                <span class="nav-text"><?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if ($target === '_blank'): ?>
                  <span class="icon-[ph--arrow-up-right] external-tip"></span>
                <?php endif; ?>
              </a>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </menu>
    <?php endif; ?>
  </nav>

  <footer class="sidebar-footer">
    <?php if ($userAuth): ?>
      <?php if ($this->user->hasLogin()): ?>
        <?php
        $userName = '';
        if (isset($this->user->screenName)) {
            $userName = (string) $this->user->screenName;
        }
        if ($userName === '' && isset($this->user->name)) {
            $userName = (string) $this->user->name;
        }
        if ($userName === '' && isset($this->user->mail)) {
            $userName = (string) $this->user->mail;
        }
        if ($userName === '') {
            $userName = '用户';
        }
        $userMail = isset($this->user->mail) ? (string) $this->user->mail : '';
        $avatar = '';
        if ($userMail !== '') {
            $avatar = \Typecho\Common::gravatarUrl($userMail, 64, 'X', 'mp', isset($_SERVER['HTTPS']));
        }
        $roleMap = [
            'administrator' => '管理员',
            'editor' => '编辑',
            'contributor' => '贡献者',
            'subscriber' => '订阅者',
        ];
        $role = isset($this->user->group) ? (string) $this->user->group : '';
        $roleLabel = $roleMap[$role] ?? '已登录';
        ?>
        <a class="user-entry" href="<?php echo $this->options->adminUrl; ?>">
          <div class="avatar-wrapper">
            <?php if ($avatar !== ''): ?>
              <img class="user-avatar" src="<?php echo htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" />
            <?php else: ?>
              <span class="icon-[ph--user-circle-bold] default-avatar-icon"></span>
            <?php endif; ?>
          </div>
          <div class="user-info">
            <span class="user-name"><?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="user-desc"><?php echo htmlspecialchars($roleLabel, ENT_QUOTES, 'UTF-8'); ?></span>
          </div>
        </a>
      <?php else: ?>
        <a class="user-entry" href="<?php echo $this->options->adminUrl; ?>" data-auth-trigger="sidebar-login">
          <div class="avatar-wrapper">
            <span class="icon-[ph--user-circle-bold] default-avatar-icon"></span>
          </div>
          <div class="user-info">
            <span class="user-name">登录</span>
            <span class="user-desc">点击登录账号</span>
          </div>
        </a>
      <?php endif; ?>
    <?php endif; ?>

    <div class="theme-toggle" x-data="themeToggle()">
      <button :class="{ active: theme === 'light' }" @click="setTheme('light', $event)" title="浅色模式">
        <span class="icon-[ph--sun-bold]"></span>
      </button>
      <button :class="{ active: theme === 'system' }" @click="setTheme('system', $event)" title="跟随系统">
        <span class="icon-[ph--monitor-bold]"></span>
      </button>
      <button :class="{ active: theme === 'dark' }" @click="setTheme('dark', $event)" title="深色模式">
        <span class="icon-[ph--moon-bold]"></span>
      </button>
    </div>

    <?php if (!empty($socialItems)): ?>
      <div class="social-icons">
        <?php foreach ($socialItems as $item): ?>
          <?php
          $url = $item['url'] ?? '#';
          $text = $item['text'] ?? '';
          $icon = $item['icon'] ?? '';
          ?>
          <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
            <?php if ($icon): ?>
              <?php echo $renderIcon($icon); ?>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </footer>
</aside>
