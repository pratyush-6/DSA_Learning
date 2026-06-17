# DSA Learning Platform

A complete **Data Structures & Algorithms** learning module built with **plain PHP 8 +
MySQL (PDO)**. Progressive roadmap (Beginner → Intermediate → Advanced), multi-language
code (PHP / C++ / Java / Python), real-world examples, interview prep, coding practice
with reveal-solutions, quizzes, and per-user progress tracking.

## Quick start (XAMPP)

1. Make sure **Apache** and **MySQL** are running in the XAMPP Control Panel.
2. The MySQL PDO driver must be enabled in `C:\xampp\php\php.ini`:
   ```
   extension=pdo_mysql
   extension=mysqli
   ```
   (Restart Apache after changing `php.ini`.)
3. Visit **http://localhost/learn_dsa/setup.php** once. This creates the `learn_dsa`
   database, builds all tables, and seeds the 28-chapter curriculum. It is **idempotent**
   — safe to re-run; user progress is preserved.
4. Open **http://localhost/learn_dsa/** and sign up, or log in as the seeded admin.

### Default admin
- Email: `admin@dsa.test`
- Password: `admin123`  *(change it after first login via Profile)*

## Features

| Area | What it does |
|------|--------------|
| **Roadmap** | Chapters grouped by level with completion % and ✓ marks |
| **Lessons** | Theory, complexity tables, real-world examples, inline subtopics |
| **Multi-language** | Every code example has PHP / C++ / Java / Python tabs (default = your preferred language) |
| **Practice** | Problems by difficulty with "Reveal Solution" in all 4 languages |
| **Interview prep** | Conceptual + coding Q&A per chapter, plus company-wise collections |
| **Quizzes** | MCQs per chapter, auto-scored with explanations |
| **Progress** | Mark complete (AJAX ✓), chapter/level/overall %, dashboard, streaks, achievements |
| **Engagement** | Notes (auto-save), bookmarks, search |
| **Admin** | CRUD authoring tool for chapters, topics, code, interview Qs, problems, quizzes |

## Configuration

Edit `config/config.php` (app + DB credentials) and `config/database.php` (connection).
Defaults assume XAMPP MySQL at `127.0.0.1:3306`, user `root`, no password.

If the app is served from a different path, update `BASE_URL` in `config/config.php`.

## Project structure

```
config/      config + PDO connection
includes/    auth, helpers (markdown, progress, streaks), CSRF
lib/         Parsedown (Markdown -> HTML)
partials/    header, nav, footer, curriculum sidebar
assets/      app.css, app.js (progress/bookmark/notes/quiz/code-tabs)
api/         JSON endpoints: progress, bookmark, note, quiz_submit
admin/       authoring CRUD (admin role only)
database/    schema.sql, seed/ (runner + content/01..28 chapter files)
*.php        pages: index, roadmap, chapter, topic, practice, problem,
             quiz, interview, companies, dashboard, bookmarks, search,
             profile, register, login, logout
setup.php    one-time DB + schema + seed
```

## Adding or editing content

Two options:
1. **Admin UI** (`/admin`) — create/edit chapters, topics, code snippets, interview
   questions, problems (+ solutions), and quizzes through forms.
2. **Seed files** — edit/add `database/seed/content/NN-name.php` (each returns a chapter
   array: topics, code in 4 languages, interview Qs, problems, quiz), then re-run
   `setup.php`. See `03-arrays.php` for the reference format.

## Tech notes

- All DB access uses **PDO prepared statements**; output is escaped with `htmlspecialchars`
  (rendered Markdown content is trusted/admin-authored).
- **CSRF tokens** protect every POST and AJAX write.
- Passwords are hashed with `password_hash()` (bcrypt).
- Syntax highlighting via **Prism.js**; UI via **Bootstrap 5** (both CDN).
