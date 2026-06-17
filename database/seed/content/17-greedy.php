<?php
return [
    'title'       => 'Greedy Algorithms',
    'slug'        => 'greedy-algorithms',
    'level'       => 'intermediate',
    'icon'        => 'bi-lightning-charge',
    'sort_order'  => 17,
    'description' => 'Make the locally optimal choice at each step, hoping for a global optimum.',

    'topics' => [
        [
            'title'   => 'The Greedy Strategy',
            'slug'    => 'greedy-strategy',
            'summary' => 'When local choices lead to a global optimum.',
            'theory_md' => <<<MD
A **greedy algorithm** builds a solution step by step, always taking the choice that
looks best **right now**, never reconsidering.

Greedy works only when the problem has:
1. **Greedy choice property** — a global optimum can be reached by local optimal choices.
2. **Optimal substructure** — an optimal solution contains optimal solutions to subproblems.

> Greedy is fast but **not always correct**. Always justify why the greedy choice is
> safe (often via an exchange argument), or it may give a wrong answer where **dynamic
> programming** is needed instead.

Classic correct greedy problems: **activity selection** (sort by end time), **fractional
knapsack**, **Huffman coding**, **Dijkstra**, and **interval scheduling**.
MD,
            'complexity_md' => "Most greedy algorithms are dominated by a **sort**: **O(n log n)**, then a single O(n) pass.",
            'real_world_md' => <<<MD
- **Scheduling** meetings/jobs to maximize throughput.
- **Coin change** with canonical currency systems.
- **Data compression** (Huffman).
- **Network routing** and **caching eviction** heuristics.
MD,
            'code' => [
                ['language' => 'python', 'label' => 'Activity selection', 'code' => <<<'CODE'
def max_activities(intervals):
    intervals.sort(key=lambda x: x[1])  # sort by end time
    count, end = 0, float('-inf')
    for s, e in intervals:
        if s >= end:        # non-overlapping
            count += 1
            end = e
    return count
CODE],
                ['language' => 'php', 'label' => 'Activity selection', 'code' => <<<'CODE'
<?php
function maxActivities(array $intervals): int {
    usort($intervals, fn($a, $b) => $a[1] <=> $b[1]); // by end time
    $count = 0; $end = -INF;
    foreach ($intervals as [$s, $e]) {
        if ($s >= $end) { $count++; $end = $e; }
    }
    return $count;
}
CODE],
                ['language' => 'java', 'label' => 'Activity selection', 'code' => <<<'CODE'
int maxActivities(int[][] intervals){
    Arrays.sort(intervals, (a, b) -> a[1] - b[1]);
    int count = 0, end = Integer.MIN_VALUE;
    for(int[] iv : intervals)
        if(iv[0] >= end){ count++; end = iv[1]; }
    return count;
}
CODE],
                ['language' => 'cpp', 'label' => 'Activity selection', 'code' => <<<'CODE'
int maxActivities(vector<pair<int,int>>& iv){
    sort(iv.begin(), iv.end(), [](auto& a, auto& b){ return a.second < b.second; });
    int count = 0, end = INT_MIN;
    for(auto& [s, e] : iv)
        if(s >= end){ count++; end = e; }
    return count;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What is a greedy algorithm and when does it work?',
         'answer_md' => 'It makes the locally optimal choice at each step. It works when the problem has the **greedy choice property** and **optimal substructure**. Otherwise it may be suboptimal — use DP.',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'Why is the standard coin-change problem not always solvable greedily?',
         'answer_md' => 'Greedy (always take the largest coin) fails for non-canonical systems. E.g., coins {1,3,4} for 6: greedy gives 4+1+1 (3 coins) but optimal is 3+3 (2 coins). The general problem needs **dynamic programming**.',
         'companies' => ['Google', 'Meta']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Given jump lengths at each index, can you reach the last index? (Jump Game)',
         'answer_md' => 'Track the farthest reachable index while scanning. If your current index ever exceeds the farthest reach, return false. Greedy, O(n).',
         'companies' => ['Amazon', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Jump Game', 'slug' => 'greedy-jump-game', 'difficulty' => 'medium',
         'statement_md' => "Given an array where each element is the max jump length from that position, determine if you can reach the last index starting from index 0.",
         'examples_md' => "```\n[2,3,1,1,4] -> true\n[3,2,1,0,4] -> false\n```",
         'solutions' => [
            'python' => ['code' => "def can_jump(nums):\n    farthest = 0\n    for i, n in enumerate(nums):\n        if i > farthest:\n            return False\n        farthest = max(farthest, i + n)\n    return True", 'explanation_md' => 'Greedily track the farthest reachable index; if a position is unreachable, fail. O(n).'],
            'php' => ['code' => "<?php\nfunction canJump(array \$nums): bool {\n    \$farthest = 0;\n    foreach (\$nums as \$i => \$n) {\n        if (\$i > \$farthest) return false;\n        \$farthest = max(\$farthest, \$i + \$n);\n    }\n    return true;\n}", 'explanation_md' => ''],
            'java' => ['code' => "boolean canJump(int[] nums){\n    int farthest = 0;\n    for(int i = 0; i < nums.length; i++){\n        if(i > farthest) return false;\n        farthest = Math.max(farthest, i + nums[i]);\n    }\n    return true;\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "bool canJump(vector<int>& nums){\n    int farthest = 0;\n    for(int i = 0; i < nums.size(); i++){\n        if(i > farthest) return false;\n        farthest = max(farthest, i + nums[i]);\n    }\n    return true;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Greedy Quiz',
        'questions' => [
            ['question' => 'A greedy algorithm makes choices that are:',
             'options' => [['text' => 'Locally optimal at each step', 'correct' => true], ['text' => 'Globally optimal by trying all options', 'correct' => false], ['text' => 'Random', 'correct' => false], ['text' => 'Always reconsidered later', 'correct' => false]]],
            ['question' => 'Activity selection is solved greedily by sorting on:',
             'options' => [['text' => 'Earliest end time', 'correct' => true], ['text' => 'Earliest start time', 'correct' => false], ['text' => 'Longest duration', 'correct' => false], ['text' => 'Random order', 'correct' => false]]],
            ['question' => 'When greedy fails on optimization, the usual fallback is:',
             'options' => [['text' => 'Dynamic programming', 'correct' => true], ['text' => 'Linear search', 'correct' => false], ['text' => 'Hashing', 'correct' => false], ['text' => 'Binary search', 'correct' => false]]],
        ],
    ],
];
