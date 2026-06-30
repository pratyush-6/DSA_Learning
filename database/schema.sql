-- DSA Learning Platform — database schema
-- Engine: MySQL 8 / MariaDB (InnoDB, utf8mb4)
-- This file is idempotent: it can be re-run safely.

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------------
-- Auth & users
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name               VARCHAR(120) NOT NULL,
    email              VARCHAR(190) NOT NULL,
    password_hash      VARCHAR(255) NOT NULL,
    role               ENUM('user','admin') NOT NULL DEFAULT 'user',
    preferred_language ENUM('php','cpp','java','python') NOT NULL DEFAULT 'php',
    created_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_activity (
    user_id       INT UNSIGNED NOT NULL,
    activity_date DATE NOT NULL,
    PRIMARY KEY (user_id, activity_date),
    CONSTRAINT fk_activity_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Curriculum: Chapter -> Topic -> Subtopic
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS chapters (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title       VARCHAR(160) NOT NULL,
    slug        VARCHAR(180) NOT NULL,
    level       ENUM('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
    description TEXT NULL,
    icon        VARCHAR(60) NULL,
    sort_order  INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY uq_chapters_slug (slug),
    KEY idx_chapters_level (level, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS topics (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    chapter_id    INT UNSIGNED NOT NULL,
    title         VARCHAR(180) NOT NULL,
    slug          VARCHAR(200) NOT NULL,
    summary       TEXT NULL,
    theory_md     MEDIUMTEXT NULL,
    real_world_md MEDIUMTEXT NULL,
    complexity_md MEDIUMTEXT NULL,
    sort_order    INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY uq_topics_slug (slug),
    KEY idx_topics_chapter (chapter_id, sort_order),
    CONSTRAINT fk_topics_chapter FOREIGN KEY (chapter_id) REFERENCES chapters (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS subtopics (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    topic_id   INT UNSIGNED NOT NULL,
    title      VARCHAR(200) NOT NULL,
    slug       VARCHAR(220) NOT NULL,
    body_md    MEDIUMTEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_subtopics_topic (topic_id, sort_order),
    CONSTRAINT fk_subtopics_topic FOREIGN KEY (topic_id) REFERENCES topics (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS code_snippets (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    topic_id       INT UNSIGNED NOT NULL,
    language       ENUM('php','cpp','java','python') NOT NULL,
    label          VARCHAR(160) NOT NULL DEFAULT 'Example',
    code           MEDIUMTEXT NOT NULL,
    explanation_md MEDIUMTEXT NULL,
    sort_order     INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_code_topic (topic_id, sort_order),
    CONSTRAINT fk_code_topic FOREIGN KEY (topic_id) REFERENCES topics (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Interview preparation
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS companies (
    id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(140) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_companies_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS interview_questions (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    chapter_id INT UNSIGNED NOT NULL,
    type       ENUM('conceptual','coding') NOT NULL DEFAULT 'conceptual',
    difficulty ENUM('easy','medium','hard') NOT NULL DEFAULT 'easy',
    question   TEXT NOT NULL,
    answer_md  MEDIUMTEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_iq_chapter (chapter_id, sort_order),
    CONSTRAINT fk_iq_chapter FOREIGN KEY (chapter_id) REFERENCES chapters (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS interview_question_company (
    question_id INT UNSIGNED NOT NULL,
    company_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (question_id, company_id),
    CONSTRAINT fk_iqc_question FOREIGN KEY (question_id) REFERENCES interview_questions (id) ON DELETE CASCADE,
    CONSTRAINT fk_iqc_company FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Practice & assessment
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS practice_problems (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    chapter_id   INT UNSIGNED NOT NULL,
    title        VARCHAR(200) NOT NULL,
    slug         VARCHAR(220) NOT NULL,
    difficulty   ENUM('easy','medium','hard') NOT NULL DEFAULT 'easy',
    statement_md MEDIUMTEXT NOT NULL,
    constraints_md MEDIUMTEXT NULL,
    examples_md  MEDIUMTEXT NULL,
    sort_order   INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY uq_problems_slug (slug),
    KEY idx_problems_chapter (chapter_id, sort_order),
    KEY idx_problems_difficulty (difficulty),
    CONSTRAINT fk_problems_chapter FOREIGN KEY (chapter_id) REFERENCES chapters (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS practice_solutions (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    problem_id     INT UNSIGNED NOT NULL,
    language       ENUM('php','cpp','java','python') NOT NULL,
    code           MEDIUMTEXT NOT NULL,
    explanation_md MEDIUMTEXT NULL,
    PRIMARY KEY (id),
    KEY idx_solutions_problem (problem_id),
    CONSTRAINT fk_solutions_problem FOREIGN KEY (problem_id) REFERENCES practice_problems (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS quizzes (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    chapter_id INT UNSIGNED NOT NULL,
    title      VARCHAR(200) NOT NULL,
    PRIMARY KEY (id),
    KEY idx_quizzes_chapter (chapter_id),
    CONSTRAINT fk_quizzes_chapter FOREIGN KEY (chapter_id) REFERENCES chapters (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS quiz_questions (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    quiz_id        INT UNSIGNED NOT NULL,
    question       TEXT NOT NULL,
    explanation_md MEDIUMTEXT NULL,
    sort_order     INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_qq_quiz (quiz_id, sort_order),
    CONSTRAINT fk_qq_quiz FOREIGN KEY (quiz_id) REFERENCES quizzes (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS quiz_options (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    question_id INT UNSIGNED NOT NULL,
    option_text VARCHAR(500) NOT NULL,
    is_correct  TINYINT(1) NOT NULL DEFAULT 0,
    sort_order  INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_qo_question (question_id, sort_order),
    CONSTRAINT fk_qo_question FOREIGN KEY (question_id) REFERENCES quiz_questions (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Progress & engagement
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS user_progress (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id      INT UNSIGNED NOT NULL,
    topic_id     INT UNSIGNED NOT NULL,
    status       ENUM('not_started','completed') NOT NULL DEFAULT 'completed',
    completed_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_progress (user_id, topic_id),
    KEY idx_progress_completed (completed_at),
    CONSTRAINT fk_progress_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_progress_topic FOREIGN KEY (topic_id) REFERENCES topics (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_quiz_attempts (
    id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id  INT UNSIGNED NOT NULL,
    quiz_id  INT UNSIGNED NOT NULL,
    score    INT NOT NULL DEFAULT 0,
    total    INT NOT NULL DEFAULT 0,
    taken_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_attempts_user (user_id),
    CONSTRAINT fk_attempts_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_attempts_quiz FOREIGN KEY (quiz_id) REFERENCES quizzes (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS bookmarks (
    user_id    INT UNSIGNED NOT NULL,
    topic_id   INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, topic_id),
    CONSTRAINT fk_bookmarks_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_bookmarks_topic FOREIGN KEY (topic_id) REFERENCES topics (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_notes (
    user_id    INT UNSIGNED NOT NULL,
    topic_id   INT UNSIGNED NOT NULL,
    note_text  MEDIUMTEXT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, topic_id),
    CONSTRAINT fk_notes_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_notes_topic FOREIGN KEY (topic_id) REFERENCES topics (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS achievements (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    code        VARCHAR(80) NOT NULL,
    title       VARCHAR(160) NOT NULL,
    description VARCHAR(400) NULL,
    icon        VARCHAR(60) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_achievements_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_achievements (
    user_id        INT UNSIGNED NOT NULL,
    achievement_id INT UNSIGNED NOT NULL,
    earned_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, achievement_id),
    CONSTRAINT fk_ua_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_ua_achievement FOREIGN KEY (achievement_id) REFERENCES achievements (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Practice problem "solved" tracking (questions solved by a user)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS user_problem_solved (
    user_id    INT UNSIGNED NOT NULL,
    problem_id INT UNSIGNED NOT NULL,
    solved_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, problem_id),
    KEY idx_ups_problem (problem_id),
    KEY idx_ups_solved (solved_at),
    CONSTRAINT fk_ups_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_ups_problem FOREIGN KEY (problem_id) REFERENCES practice_problems (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Built-in compiler: per-problem test cases & starter code
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS problem_testcases (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    problem_id      INT UNSIGNED NOT NULL,
    stdin           MEDIUMTEXT NULL,
    expected_output MEDIUMTEXT NOT NULL,
    is_sample       TINYINT(1) NOT NULL DEFAULT 0,
    sort_order      INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_tc_problem (problem_id, sort_order),
    CONSTRAINT fk_tc_problem FOREIGN KEY (problem_id) REFERENCES practice_problems (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS problem_starters (
    problem_id INT UNSIGNED NOT NULL,
    language   ENUM('php','cpp','java','python') NOT NULL,
    code       MEDIUMTEXT NOT NULL,
    PRIMARY KEY (problem_id, language),
    CONSTRAINT fk_starter_problem FOREIGN KEY (problem_id) REFERENCES practice_problems (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Track best code submissions (latest per user/problem/language).
CREATE TABLE IF NOT EXISTS user_submissions (
    user_id    INT UNSIGNED NOT NULL,
    problem_id INT UNSIGNED NOT NULL,
    language   ENUM('php','cpp','java','python') NOT NULL,
    code       MEDIUMTEXT NOT NULL,
    passed     INT NOT NULL DEFAULT 0,
    total      INT NOT NULL DEFAULT 0,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, problem_id, language),
    CONSTRAINT fk_sub_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_sub_problem FOREIGN KEY (problem_id) REFERENCES practice_problems (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Group Study
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS study_groups (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name       VARCHAR(120) NOT NULL,
    join_code  VARCHAR(12) NOT NULL,
    created_by INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_group_code (join_code),
    CONSTRAINT fk_group_creator FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- One group per user is enforced by the user_id PRIMARY KEY.
CREATE TABLE IF NOT EXISTS group_members (
    user_id   INT UNSIGNED NOT NULL,
    group_id  INT UNSIGNED NOT NULL,
    joined_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    KEY idx_gm_group (group_id),
    CONSTRAINT fk_gm_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_gm_group FOREIGN KEY (group_id) REFERENCES study_groups (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
