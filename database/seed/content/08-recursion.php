<?php
return [
    'title'       => 'Recursion',
    'slug'        => 'recursion',
    'level'       => 'beginner',
    'icon'        => 'bi-arrow-repeat',
    'sort_order'  => 8,
    'description' => 'Functions that call themselves — the foundation of trees, graphs, backtracking, and DP.',

    'topics' => [
        [
            'title'   => 'Recursion Fundamentals',
            'slug'    => 'recursion-fundamentals',
            'summary' => 'Base case, recursive case, and the call stack.',
            'theory_md' => <<<MD
**Recursion** is when a function solves a problem by calling itself on a **smaller**
version of the same problem.

Every correct recursion has two parts:
1. **Base case** — the smallest input where the answer is known directly (stops recursion).
2. **Recursive case** — reduce the problem and call yourself, combining results.

> Forgetting the base case (or never shrinking the input) causes **infinite recursion**
> and a **stack overflow**.

Each call gets its own stack frame, so recursion depth `d` uses **O(d)** stack space.
MD,
            'complexity_md' => "Complexity depends on the recurrence. Factorial: O(n) time, O(n) stack. Naive Fibonacci: O(2ⁿ). Use the **recursion tree** or the **Master Theorem** to analyze.",
            'real_world_md' => <<<MD
- **Tree/graph traversal** (DFS).
- **Divide & conquer** (merge sort, quick sort, binary search).
- **Backtracking** (sudoku, N-queens, permutations).
- **Parsing** nested structures (JSON, file systems).
MD,
            'code' => [
                ['language' => 'python', 'label' => 'Factorial & Fibonacci', 'code' => <<<'CODE'
def factorial(n):
    if n <= 1:           # base case
        return 1
    return n * factorial(n - 1)   # recursive case

def fib(n):
    if n < 2:
        return n
    return fib(n - 1) + fib(n - 2)  # O(2^n) without memoization
CODE],
                ['language' => 'php', 'label' => 'Factorial & Fibonacci', 'code' => <<<'CODE'
<?php
function factorial(int $n): int {
    if ($n <= 1) return 1;            // base case
    return $n * factorial($n - 1);    // recursive case
}
function fib(int $n): int {
    if ($n < 2) return $n;
    return fib($n - 1) + fib($n - 2);
}
CODE],
                ['language' => 'java', 'label' => 'Factorial & Fibonacci', 'code' => <<<'CODE'
long factorial(int n) {
    if (n <= 1) return 1;             // base case
    return n * factorial(n - 1);
}
int fib(int n) {
    if (n < 2) return n;
    return fib(n - 1) + fib(n - 2);
}
CODE],
                ['language' => 'cpp', 'label' => 'Factorial & Fibonacci', 'code' => <<<'CODE'
long factorial(int n){
    if(n <= 1) return 1;              // base case
    return n * factorial(n - 1);
}
int fib(int n){
    if(n < 2) return n;
    return fib(n - 1) + fib(n - 2);
}
CODE],
            ],
        ],
        [
            'title'   => 'Recursion vs Iteration & Memoization',
            'slug'    => 'recursion-vs-iteration',
            'summary' => 'When to recurse, and how to make slow recursion fast.',
            'theory_md' => <<<MD
Any recursion can be rewritten as a loop (sometimes with an explicit stack). Recursion
is often **cleaner** for tree-shaped problems; iteration avoids stack-overflow risk and
function-call overhead.

**Naive recursion can repeat work.** Naive `fib(n)` recomputes the same values
exponentially. **Memoization** caches results so each subproblem is computed once,
turning O(2ⁿ) into **O(n)** — this is the gateway to **dynamic programming**.
MD,
            'complexity_md' => "Memoized Fibonacci: **O(n)** time, O(n) space. Tail-recursive loops can be O(1) space when converted to iteration.",
            'real_world_md' => "Memoization underlies **DP**, route caching, and any system that avoids recomputing expensive pure functions.",
            'code' => [
                ['language' => 'python', 'label' => 'Memoized Fibonacci', 'code' => <<<'CODE'
from functools import lru_cache

@lru_cache(maxsize=None)
def fib(n):
    if n < 2:
        return n
    return fib(n - 1) + fib(n - 2)   # now O(n)
CODE],
                ['language' => 'php', 'label' => 'Memoized Fibonacci', 'code' => <<<'CODE'
<?php
function fib(int $n, array &$memo = []): int {
    if ($n < 2) return $n;
    if (isset($memo[$n])) return $memo[$n];
    return $memo[$n] = fib($n - 1, $memo) + fib($n - 2, $memo);
}
CODE],
                ['language' => 'java', 'label' => 'Memoized Fibonacci', 'code' => <<<'CODE'
int fib(int n, Integer[] memo) {
    if (n < 2) return n;
    if (memo[n] != null) return memo[n];
    return memo[n] = fib(n - 1, memo) + fib(n - 2, memo);
}
CODE],
                ['language' => 'cpp', 'label' => 'Memoized Fibonacci', 'code' => <<<'CODE'
vector<int> memo;
int fib(int n){
    if(n < 2) return n;
    if(memo[n] != -1) return memo[n];
    return memo[n] = fib(n-1) + fib(n-2);
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What two components must every recursive function have?',
         'answer_md' => 'A **base case** (stops recursion) and a **recursive case** that makes progress toward the base case by reducing the problem size. Missing either causes infinite recursion / stack overflow.',
         'companies' => ['Amazon', 'Adobe']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'Why is naive recursive Fibonacci exponential, and how do you fix it?',
         'answer_md' => 'It recomputes overlapping subproblems (the recursion tree branches twice each level → O(2ⁿ)). **Memoization** caches each result so every subproblem is computed once → O(n).',
         'companies' => ['Google', 'Microsoft']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'What is tail recursion?',
         'answer_md' => 'A recursive call that is the **last** operation in the function. Some compilers optimize it into a loop (tail-call optimization), using O(1) stack space. PHP/Python do not do this automatically.',
         'companies' => ['Meta']],
    ],

    'problems' => [
        ['title' => 'Power of a Number', 'slug' => 'recursion-power', 'difficulty' => 'easy',
         'statement_md' => "Compute `x` raised to the power `n` (`x^n`) using recursion in **O(log n)** time (fast exponentiation).",
         'examples_md' => "```\npow(2, 10) = 1024\npow(3, 4)  = 81\n```",
         'solutions' => [
            'python' => ['code' => "def power(x, n):\n    if n == 0:\n        return 1\n    half = power(x, n // 2)\n    return half * half * (x if n % 2 else 1)", 'explanation_md' => 'Each call halves n → O(log n) multiplications instead of O(n).'],
            'php' => ['code' => "<?php\nfunction power(float \$x, int \$n): float {\n    if (\$n === 0) return 1;\n    \$half = power(\$x, intdiv(\$n, 2));\n    return \$half * \$half * (\$n % 2 ? \$x : 1);\n}", 'explanation_md' => ''],
            'java' => ['code' => "double power(double x, int n){\n    if(n == 0) return 1;\n    double half = power(x, n/2);\n    return half * half * (n % 2 != 0 ? x : 1);\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "double power(double x, int n){\n    if(n == 0) return 1;\n    double half = power(x, n/2);\n    return half * half * (n % 2 ? x : 1);\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Recursion Quiz',
        'questions' => [
            ['question' => 'What happens if a recursive function has no valid base case?',
             'options' => [['text' => 'Infinite recursion / stack overflow', 'correct' => true], ['text' => 'It returns 0', 'correct' => false], ['text' => 'It runs in O(1)', 'correct' => false], ['text' => 'The compiler fixes it', 'correct' => false]]],
            ['question' => 'Memoized Fibonacci runs in:',
             'options' => [['text' => 'O(n)', 'correct' => true], ['text' => 'O(2ⁿ)', 'correct' => false], ['text' => 'O(n²)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false]]],
            ['question' => 'Recursion depth d uses how much stack space?',
             'options' => [['text' => 'O(d)', 'correct' => true], ['text' => 'O(1)', 'correct' => false], ['text' => 'O(d²)', 'correct' => false], ['text' => 'O(log d)', 'correct' => false]]],
        ],
    ],
];
