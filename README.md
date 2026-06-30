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
| **Built-in compiler** | Write/run/test code in PHP, C++, Java, Python on coding-exercise pages; submit against predefined test cases — completed only when all pass |
| **Group Study** | Create/join a group via a unique code (one group per user), leave to switch |
| **Comparison dashboard** | Chart.js graphs comparing members: modules completed, questions solved, completion history with dates, and cumulative progress trends |

## Built-in code compiler

Coding exercises (the **Coding Exercises** chapter) include an in-browser editor
(CodeMirror) where users write a program, **Run** it against custom input, and
**Submit** it against predefined test cases. An exercise is marked **completed
only when every test case passes**.

Execution backends (see `config/config.php`):
- **PHP & Python** run **locally** on the server (offline, private) using the
  configured interpreters (`PHP_CLI`, `PYTHON_BIN`).
- **C++ & Java** run locally if `g++` / `javac` are installed; otherwise they are
  sent to the **Piston** public API (`EXEC_ENABLE_REMOTE`, needs internet). Code
  for those two languages leaves the machine only when run/submitted.

Each run is time-limited (`EXEC_TIME_LIMIT_MS`) and output-capped; on Windows the
process tree is force-killed on timeout.

> **Security:** this feature executes user-submitted code on the server and is
> intended for a **local, single-user** install. Do **not** expose it on the
> public internet without proper sandboxing (containers, resource limits). To
> disable remote execution entirely, set `EXEC_ENABLE_REMOTE` to `false`.

## Running the tests

A comprehensive suite covers Group Study, Module Completion, and Analytics:

```
php tests/run_tests.php
```

It creates isolated, prefixed test users/groups and cleans them up afterward,
printing PASS/FAIL per scenario and exiting non-zero on any failure.

The same suite runs automatically on every push/PR via **GitHub Actions**
(`.github/workflows/ci.yml`, spins up MySQL, runs `setup.php` then the tests).

## Configuration

Defaults assume XAMPP MySQL at `127.0.0.1:3306`, user `root`, no password, and
the app served at `/learn_dsa`.

For machine-specific settings or **production secrets**, copy
`config/config.local.example.php` → `config/config.local.php` (git-ignored) and
override any constant (DB credentials, `BASE_URL`, `APP_DEBUG`,
`EXEC_ENABLE_REMOTE`). Values there win over the defaults in `config/config.php`.

Login attempts and code run/submit calls are rate-limited per IP/user.

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
