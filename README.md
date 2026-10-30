# 春树 Chun Shu · 个人博客

一个由 WordPress 迁移而来的 Hugo 静态博客。站点模板按照原站（Premium Pixels 主题）的版式复刻：深色顶栏、浅色纹理背景、左右双栏、赭红色链接以及 wp-pagenavi 风格的分页，同时补充了移动端响应式适配。

- 纯静态输出，不依赖 JavaScript 框架
- 内容通过 Pages CMS 或直接编辑 Markdown 维护
- 推送到 `main` 分支后由 GitHub Actions 自动构建并发布到 GitHub Pages

## 技术栈

| 项目 | 说明 |
| --- | --- |
| 静态站点生成器 | Hugo Extended（本仓库使用 0.165.0） |
| 主题 | 自带 `layouts/` 模板完整覆盖页面渲染；`themes/PaperMod` 仅保留以支持 `figure` 等 shortcode |
| 内容格式 | Markdown + YAML frontmatter |
| 内容管理 | Pages CMS（`.pages.yml`） |
| 部署 | GitHub Actions + GitHub Pages |

## 目录结构

```
.
├── content/
│   ├── posts/          # 文章（163 篇，文件名沿用迁移时编号，如 605.html.md）
│   ├── pages/          # 独立页面，每页一个目录，内含 index.md
│   └── categories/     # 分类定义，每类一个目录，内含 _index.md
├── data/
│   ├── comments.yaml   # 迁移过来的评论数据
│   └── library.yaml    # 书单数据
├── layouts/            # 站点模板（覆盖主题模板）
│   ├── baseof.html     # 整体骨架：顶栏 + 双栏布局
│   ├── index.html      # 首页文章列表
│   ├── single.html     # 文章页
│   ├── list.html       # 分类列表页
│   ├── taxonomy.html   # 分类总览
│   ├── archives.html   # 按年归档
│   ├── search.html     # 站内搜索
│   ├── 404.html
│   ├── partials/       # header / sidebar / footer / pagination 等片段
│   └── shortcodes/     # 视频、音频、图集等 shortcode
├── static/
│   ├── css/            # style.css（原主题样式）、hugo.css（适配与响应式补充）
│   └── wp-content/     # 迁移过来的图片等上传资源
├── .github/workflows/  # hugo.yaml，自动构建并部署
├── .pages.yml          # Pages CMS 配置
├── hugo.yaml           # 站点配置
└── nginx.conf          # 可选的本地 Nginx 预览配置
```

## 本地运行

1. 安装 [Hugo Extended](https://gohugo.io/installation/)（版本需与线上一致或更高）
2. 启动预览服务：

```bash
hugo server -D
```

浏览器访问 `http://localhost:1313/`，修改文件会自动刷新。

3. 生成发布文件：

```bash
hugo --gc --minify
```

产物输出到 `public/`，该目录为构建结果，无需提交到仓库。

## 内容管理

### 方式一：Pages CMS

1. 打开 <https://app.pagescms.org>，使用 GitHub 账号登录并授权本仓库
2. 左侧可选择「文章」「页面」「分类」进行编辑
3. 保存后会自动提交到 `main` 分支，GitHub Actions 随即重新构建并发布

配置说明见 `.pages.yml`：

- 上传的图片保存到 `static/wp-content/uploads`，正文中写回 `/wp-content/uploads/...`
- 「页面」和「分类」禁用了新建、改名、删除，避免破坏已有 URL
- 日期字段按 `yyyy-MM-dd'T'HH:mm:ssxxx` 读写，与现有数据一致

### 方式二：直接编辑 Markdown

文章的 frontmatter 字段：

| 字段 | 说明 |
| --- | --- |
| `title` | 标题 |
| `date` | 发布时间，格式 `2024-10-29T12:20:24+00:00` |
| `categories` | 分类英文 slug，可多个 |
| `url` | 固定链接，旧文章为 `/archives/N.html`，新文章可留空 |
| `post_id` | 迁移编号，用于评论关联，新文章可留空 |
| 正文 | frontmatter 之后的 Markdown 内容 |

`guid`、`views`、`duoshuo_thread_id` 等为迁移遗留字段，可保留不动。

> 部分文章正文使用 `{{< figure src="..." >}}` 短代码。这类内容在富文本编辑器中可能被改写，建议直接用文本编辑。

新增分类时需同步三处：`content/categories/<slug>/_index.md`（显示名与地址）、`.pages.yml` 中的分类选项、以及文章的 `categories` 字段。

## 分类与地址

| 显示名称 | 分类地址 | 目录 |
| --- | --- | --- |
| 小说 | `/category/novel/` | `content/categories/novel/` |
| 诗歌 | `/category/poem/` | `content/categories/poem/` |
| 随笔 | `/category/article/` | `content/categories/article/` |
| 感悟 | `/category/feeling/` | `content/categories/feeling/` |
| 书评、诗评、人评 | `/category/review/` | `content/categories/review/` |
| 买书信息 | `/category/book/` | `content/categories/book/` |
| 摄影 | `/category/photo/` | `content/categories/photo/` |
| 爱谁谁 | `/category/lovewhowho/` | `content/categories/lovewhowho/` |
| 其他 | `/category/other/` | `content/categories/other/` |

URL 结构由 `hugo.yaml` 中的 `permalinks` 与各分类 `_index.md` 的 `url` 共同决定，修改时请保持两者一致。

## 部署

- 工作流：`.github/workflows/hugo.yaml`，在每次推送到 `main` 时构建并部署到 GitHub Pages，也可在 Actions 页面手动触发
- 自定义域名在仓库 **Settings → Pages** 中配置；域名解析需指向 GitHub Pages（`www` 记录使用 CNAME 指向 `<用户名>.github.io`）
- DNS 切换完成后，建议在 Pages 设置中启用 **Enforce HTTPS**

## 说明

- 站点不依赖数据库，历史评论与浏览量数据以静态文件形式保留
- 文章内容版权归作者所有，转载请注明出处
