<?php
return [
    'title'       => 'Dynamic Programming',
    'slug'        => 'dynamic-programming',
    'level'       => 'advanced',
    'icon'        => 'bi-grid-1x2',
    'sort_order'  => 21,
    'description' => 'Solve problems with overlapping subproblems by storing and reusing answers.',

    'topics' => [
        [
            'title'   => 'Memoization vs Tabulation',
            'slug'    => 'dp-memo-tabulation',
            'summary' => 'Top-down caching vs bottom-up table filling.',
            'theory_md' => <<<MD
**Dynamic Programming (DP)** solves problems that have:
1. **Overlapping subproblems** — the same subproblem is solved many times.
2. **Optimal substructure** — the optimal answer is built from optimal sub-answers.

Two implementation styles:
- **Top-down (memoization):** ordinary recursion that **caches** each result so it is
  computed once. Easy to write from the recurrence.
- **Bottom-up (tabulation):** fill a table from base cases upward, no recursion.
  Often more space/time efficient and avoids stack limits.

**Steps to design a DP:** (1) define the **state**, (2) write the **recurrence/transition**,
(3) set **base cases**, (4) decide the **iteration order**, (5) optionally **optimize space**.
MD,
            'complexity_md' => "Complexity = (number of states) × (work per state). Example: 0/1 knapsack has O(n·W) states → O(n·W) time. Space can often be reduced to one or two rows.",
            'real_world_md' => <<<MD
- **Edit distance** (spell-check, diff tools, DNA alignment).
- **Resource allocation / knapsack** (budgeting, cargo loading).
- **Shortest paths** (Floyd–Warshall, Bellman–Ford).
- **Text justification, autocorrect, and route planning.**
MD,
            'code' => [
                ['language' => 'python', 'label' => 'Climbing stairs (both styles)', 'code' => <<<'CODE'
# Top-down (memoization)
def climb_memo(n, memo={}):
    if n <= 2: return n
    if n in memo: return memo[n]
    memo[n] = climb_memo(n-1, memo) + climb_memo(n-2, memo)
    return memo[n]

# Bottom-up, O(1) space
def climb_dp(n):
    a, b = 1, 2
    if n <= 2: return n
    for _ in range(3, n + 1):
        a, b = b, a + b
    return b
CODE],
                ['language' => 'php', 'label' => 'Climbing stairs (both styles)', 'code' => <<<'CODE'
<?php
function climbMemo(int $n, array &$memo = []): int {
    if ($n <= 2) return $n;
    if (isset($memo[$n])) return $memo[$n];
    return $memo[$n] = climbMemo($n - 1, $memo) + climbMemo($n - 2, $memo);
}
function climbDP(int $n): int {            // O(1) space
    if ($n <= 2) return $n;
    $a = 1; $b = 2;
    for ($i = 3; $i <= $n; $i++) { [$a, $b] = [$b, $a + $b]; }
    return $b;
}
CODE],
                ['language' => 'java', 'label' => 'Climbing stairs (bottom-up)', 'code' => <<<'CODE'
int climb(int n){
    if(n <= 2) return n;
    int a = 1, b = 2;
    for(int i = 3; i <= n; i++){ int c = a + b; a = b; b = c; }
    return b;
}
CODE],
                ['language' => 'cpp', 'label' => 'Climbing stairs (bottom-up)', 'code' => <<<'CODE'
int climb(int n){
    if(n <= 2) return n;
    int a = 1, b = 2;
    for(int i = 3; i <= n; i++){ int c = a + b; a = b; b = c; }
    return b;
}
CODE],
            ],
        ],
        [
            'title'   => '0/1 Knapsack',
            'slug'    => 'dp-knapsack',
            'summary' => 'The canonical DP: maximize value under a weight budget.',
            'theory_md' => <<<MD
Given items with **weights** and **values** and a capacity **W**, choose a subset to
maximize total value without exceeding W (each item taken at most once).

**State:** `dp[i][w]` = best value using the first `i` items with capacity `w`.
**Transition:** for item i, either skip it or take it:
```
dp[i][w] = max(
    dp[i-1][w],                          # skip
    dp[i-1][w - wt[i]] + val[i]          # take (if it fits)
)
```
This is **O(n·W)** time. Because each row depends only on the previous row, you can
compress to a single 1-D array of size W (iterate w **downward** to avoid reusing an
item).
MD,
            'complexity_md' => "Time **O(n·W)**, space **O(W)** with the 1-D optimization. (Pseudo-polynomial in W.)",
            'real_world_md' => "Budget allocation, cargo/container loading, ad selection under a spend cap, and CPU/memory packing.",
            'code' => [
                ['language' => 'python', 'label' => '1-D knapsack', 'code' => <<<'CODE'
def knapsack(weights, values, W):
    dp = [0] * (W + 1)
    for i in range(len(weights)):
        for w in range(W, weights[i] - 1, -1):   # iterate downward
            dp[w] = max(dp[w], dp[w - weights[i]] + values[i])
    return dp[W]
CODE],
                ['language' => 'php', 'label' => '1-D knapsack', 'code' => <<<'CODE'
<?php
function knapsack(array $weights, array $values, int $W): int {
    $dp = array_fill(0, $W + 1, 0);
    for ($i = 0; $i < count($weights); $i++) {
        for ($w = $W; $w >= $weights[$i]; $w--) {
            $dp[$w] = max($dp[$w], $dp[$w - $weights[$i]] + $values[$i]);
        }
    }
    return $dp[$W];
}
CODE],
                ['language' => 'java', 'label' => '1-D knapsack', 'code' => <<<'CODE'
int knapsack(int[] weights, int[] values, int W){
    int[] dp = new int[W + 1];
    for(int i = 0; i < weights.length; i++)
        for(int w = W; w >= weights[i]; w--)
            dp[w] = Math.max(dp[w], dp[w - weights[i]] + values[i]);
    return dp[W];
}
CODE],
                ['language' => 'cpp', 'label' => '1-D knapsack', 'code' => <<<'CODE'
int knapsack(vector<int>& weights, vector<int>& values, int W){
    vector<int> dp(W + 1, 0);
    for(int i = 0; i < (int)weights.size(); i++)
        for(int w = W; w >= weights[i]; w--)
            dp[w] = max(dp[w], dp[w - weights[i]] + values[i]);
    return dp[W];
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'What two properties must a problem have to use dynamic programming?',
         'answer_md' => '**Overlapping subproblems** (same subproblems recur, so caching helps) and **optimal substructure** (the optimum is composed of sub-optima). Without overlap, plain divide-and-conquer suffices.',
         'companies' => ['Amazon', 'Google', 'Microsoft']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'Difference between memoization and tabulation?',
         'answer_md' => 'Memoization is **top-down**: recursion + a cache, computing states on demand. Tabulation is **bottom-up**: iteratively fill a table from base cases. Tabulation avoids recursion overhead/stack limits and often enables space optimization.',
         'companies' => ['Meta', 'Adobe']],
        ['type' => 'coding', 'difficulty' => 'hard',
         'question' => 'Compute the edit (Levenshtein) distance between two strings.',
         'answer_md' => 'dp[i][j] = min edits to convert first i chars to first j chars. If chars match, carry dp[i-1][j-1]; else 1 + min(insert, delete, replace). O(m·n).',
         'companies' => ['Google', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Coin Change (Min Coins)', 'slug' => 'dp-coin-change', 'difficulty' => 'medium',
         'statement_md' => "Given coin denominations and an amount, return the fewest coins needed to make that amount, or -1 if impossible.",
         'examples_md' => "```\ncoins = [1,2,5], amount = 11 -> 3  (5 + 5 + 1)\ncoins = [2], amount = 3      -> -1\n```",
         'constraints_md' => "- 1 ≤ coins.length ≤ 12\n- 0 ≤ amount ≤ 10^4",
         'solutions' => [
            'python' => ['code' => "def coin_change(coins, amount):\n    INF = amount + 1\n    dp = [0] + [INF] * amount\n    for a in range(1, amount + 1):\n        for c in coins:\n            if c <= a:\n                dp[a] = min(dp[a], dp[a - c] + 1)\n    return dp[amount] if dp[amount] != INF else -1", 'explanation_md' => 'dp[a] = min coins for amount a. Each amount tries every coin → O(amount × coins).'],
            'php' => ['code' => "<?php\nfunction coinChange(array \$coins, int \$amount): int {\n    \$INF = \$amount + 1;\n    \$dp = array_fill(0, \$amount + 1, \$INF);\n    \$dp[0] = 0;\n    for (\$a = 1; \$a <= \$amount; \$a++)\n        foreach (\$coins as \$c)\n            if (\$c <= \$a) \$dp[\$a] = min(\$dp[\$a], \$dp[\$a - \$c] + 1);\n    return \$dp[\$amount] === \$INF ? -1 : \$dp[\$amount];\n}", 'explanation_md' => ''],
            'java' => ['code' => "int coinChange(int[] coins, int amount){\n    int INF = amount + 1;\n    int[] dp = new int[amount + 1];\n    Arrays.fill(dp, INF); dp[0] = 0;\n    for(int a = 1; a <= amount; a++)\n        for(int c : coins)\n            if(c <= a) dp[a] = Math.min(dp[a], dp[a - c] + 1);\n    return dp[amount] == INF ? -1 : dp[amount];\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "int coinChange(vector<int>& coins, int amount){\n    int INF = amount + 1;\n    vector<int> dp(amount + 1, INF); dp[0] = 0;\n    for(int a = 1; a <= amount; a++)\n        for(int c : coins)\n            if(c <= a) dp[a] = min(dp[a], dp[a - c] + 1);\n    return dp[amount] == INF ? -1 : dp[amount];\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Dynamic Programming Quiz',
        'questions' => [
            ['question' => 'DP is appropriate when a problem has overlapping subproblems and:',
             'options' => [['text' => 'Optimal substructure', 'correct' => true], ['text' => 'No recursion', 'correct' => false], ['text' => 'Sorted input', 'correct' => false], ['text' => 'Only one subproblem', 'correct' => false]]],
            ['question' => 'Memoization is best described as:',
             'options' => [['text' => 'Top-down recursion with caching', 'correct' => true], ['text' => 'Bottom-up table filling', 'correct' => false], ['text' => 'Greedy selection', 'correct' => false], ['text' => 'Random sampling', 'correct' => false]]],
            ['question' => '0/1 knapsack time complexity is:',
             'options' => [['text' => 'O(n·W)', 'correct' => true], ['text' => 'O(n log n)', 'correct' => false], ['text' => 'O(2ⁿ)', 'correct' => false], ['text' => 'O(n)', 'correct' => false]]],
        ],
    ],
];
