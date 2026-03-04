<div align="center">

# ✨ Clarity

一款注重阅读体验的三栏博客主题，用清晰的设计让阅读回归本真。

[![Typecho](https://img.shields.io/badge/Typecho-1.2.1+-blue?style=flat-square)](https://typecho.org/)
[![License](https://img.shields.io/badge/License-GPL--3.0-green?style=flat-square)](./LICENSE)
[![Version](https://img.shields.io/badge/Version-1.1.5-orange?style=flat-square)](./readme.md)

[🌐 预览站点](https://www.laosun.de)

</div>

## 🧭 使用
- 将本主题目录放到 `usr/themes/clarity`
- Typecho 后台 → 外观 → 启用 `clarity`
- 外观 → 设置外观 → `clarity` 配置
- 建议创建以下独立页面并选择对应模板：
  - 友链：`page-links.php`
  - 归档：`page-archives.php`
  - 分类：`page-categories.php`
  - 标签云：`page-tags.php`
  - 图库：`page-photos.php`
  - 瞬间：`page-moments.php`
  - 追番：`page-bangumis.php`

## ⚙️ 配置说明
### 🔗 友链
- `友链页面标题`：显示在友链页与移动端标题
- `我的博客信息（JSON）`
  - 示例：`{"title":"我的博客","url":"https://example.com","logo":"","description":"一句话","rss":"/feed"}`
- `Enhancement` 插件已启用时，友链数据自动读取插件表（按分类分组）
- `Enhancement` 插件中 `sort` 字段作为分组名，未设置则归入“友链”
- `友链数据（JSON）`（当 `Enhancement` 插件未启用时作为备用）
  - 示例：
    ```json
    [
      {
        "title": "友链",
        "description": "一些朋友",
        "links": [
          { "name": "站点", "url": "https://example.com", "logo": "", "desc": "描述" }
        ]
      }
    ]
    ```

### 🖼️ 图库
- `图库页面标题` / `图库页面描述`
- 图库默认读取当前页面的附件（媒体库上传后关联到该页面）
- 附件仅取图片类型，标题与描述用于卡片信息
- `图库数据（JSON）`（当页面附件为空时作为备用）
  - 示例：
    ```json
    [
      {
        "name": "travel",
        "displayName": "旅行",
        "photos": [
          { "url": "", "cover": "", "displayName": "标题", "description": "说明" }
        ]
      }
    ]
    ```

### ⚡ 瞬间
- `瞬间页面标题`
- `Enhancement` 插件已启用时，瞬间数据自动读取插件表
- `微语数据（JSON）`（侧边栏与瞬间页共用,未启用`Enhancement`时使用此配置）
- 支持定位显示，可使用 `location`（推荐）或 `location_address` + `latitude` + `longitude`
  - 示例：
    ```json
    [
      {
        "id": "m1",
        "content": "<p>今天很棒</p>",
        "time": "2025-01-01 12:00",
        "tags": ["生活"],
        "media": [{"type":"PHOTO","url":""}],
        "status": "public",
        "location": "上海市浦东新区世纪大道",
        "location_address": "上海市浦东新区世纪大道",
        "latitude": "31.2397",
        "longitude": "121.4998",
        "source": "web",
        "likes": 0,
        "comments": 0
      }
    ]
    ```

### 📺 追番
- `追番页面标题`
- `B 站 UID`（填写后自动拉取追番数据）

## 🧩 自定义字段
- `cover`：文章/页面封面图
- `toc`：文章/页面目录开关

## 📌 说明

- 天气组件需填写心知天气 API Key。

## 🧪 数据库兼容回归清单

> 目标：确认主题在 Typecho 支持的数据库适配器下行为一致  
> 范围：`Mysqli/Pdo_Mysql`、`Pgsql/Pdo_Pgsql`、`SQLite/Pdo_SQLite`

### 1. 通用检查（每个数据库都执行）

- 全新启用主题后进入 `外观 -> 设置外观`，修改任意字段并保存，刷新后值保持不变。
- 配置中输入包含中文与 emoji（如：`📄,🦌,🙌`），保存后刷新确认不丢失。
- 打开“归档”页面（`page-archives.php`），确认有文章时不显示“暂无归档”。
- 打开一篇文章详情页，确认上下篇导航可正常显示。
- 评论区存在回复关系时，确认“回复给某某”显示正常（父评论作者可读）。
- 若启用 `Enhancement` 插件：
  - 友链页能读取分组数据；
  - 瞬间页能读取插件 moments 数据。
- 打开任意文章详情页两次，确认浏览量逻辑正常（首次进入 +1，同会话内重复打开不重复累加）。

### 2. 分库专项检查

#### MySQL / MariaDB

- 确认数据库字符集为 `utf8mb4`（库、表、连接都建议一致）。
- 主题设置可保存 emoji，不出现“保存后被截断/清空”。

#### PostgreSQL

- 首次打开文章页时，若自动创建 `views` 列，页面不报错。
- 执行过一次浏览量写入后，再次访问文章页无 SQL 异常。

#### SQLite

- 首次打开文章页时，`views` 列自动补齐后不报错。
- 友链/瞬间插件数据读取正常（若插件已启用且有数据）。

### 3. 失败时的排查入口

- 进入 `外观 -> 设置外观`，查看 `Clarity 设置诊断` 区块。
- 若提示写入失败，优先检查：
  - 数据库账号是否有 `UPDATE/INSERT/ALTER` 权限；
  - 数据库字符集是否支持 emoji（MySQL 建议 `utf8mb4`）；
  - 表结构是否被第三方插件或历史迁移改动。

## 🙏 致谢
- Halo 主题 [Clarity](https://github.com/acanyo/theme-clarity)
