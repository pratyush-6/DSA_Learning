<?php
return [
    'title'       => 'Sliding Window',
    'slug'        => 'sliding-window',
    'level'       => 'intermediate',
    'icon'        => 'bi-aspect-ratio',
    'sort_order'  => 18,
    'description' => 'Turn nested-loop subarray/substring problems into O(n) with a moving window.',

    'topics' => [
        [
            'title'   => 'Fixed and Variable Windows',
            'slug'    => 'sliding-window-basics',
            'summary' => 'Slide a contiguous range and update the answer incrementally.',
            'theory_md' => <<<MD
The **sliding window** technique maintains a contiguous range `[left, right]` over an
array/string and updates a running result as the window moves — avoiding recomputation.

**Fixed-size window** (size k): add the new right element, remove the element leaving on
the left. Great for "max sum of k consecutive elements".

**Variable-size window:** expand `right` to include elements; when a constraint is
violated, shrink from `left`. Great for "longest substring without repeats", "smallest
subarray with sum ≥ target".

This converts many **O(n²)** brute-force subarray problems into **O(n)**.
MD,
            'complexity_md' => "Each element enters and leaves the window at most once → **O(n)** time, O(k) or O(distinct) space.",
            'real_world_md' => "- **Network throughput / rate limiting** over a time window.\n- **Moving averages** in analytics dashboards.\n- **Streaming** anomaly detection.",
            'code' => [
                ['language' => 'python', 'label' => 'Max sum of size k + longest unique', 'code' => <<<'CODE'
def max_sum_k(a, k):
    window = sum(a[:k]); best = window
    for i in range(k, len(a)):
        window += a[i] - a[i - k]   # add new, drop old
        best = max(best, window)
    return best

def longest_unique(s):
    seen = {}; left = best = 0
    for right, c in enumerate(s):
        if c in seen and seen[c] >= left:
            left = seen[c] + 1
        seen[c] = right
        best = max(best, right - left + 1)
    return best
CODE],
                ['language' => 'php', 'label' => 'Max sum of size k + longest unique', 'code' => <<<'CODE'
<?php
function maxSumK(array $a, int $k): int {
    $window = array_sum(array_slice($a, 0, $k)); $best = $window;
    for ($i = $k; $i < count($a); $i++) {
        $window += $a[$i] - $a[$i - $k];
        $best = max($best, $window);
    }
    return $best;
}
function longestUnique(string $s): int {
    $seen = []; $left = 0; $best = 0;
    for ($right = 0; $right < strlen($s); $right++) {
        $c = $s[$right];
        if (isset($seen[$c]) && $seen[$c] >= $left) $left = $seen[$c] + 1;
        $seen[$c] = $right;
        $best = max($best, $right - $left + 1);
    }
    return $best;
}
CODE],
                ['language' => 'java', 'label' => 'Longest unique substring', 'code' => <<<'CODE'
int longestUnique(String s){
    Map<Character,Integer> seen = new HashMap<>();
    int left = 0, best = 0;
    for(int right = 0; right < s.length(); right++){
        char c = s.charAt(right);
        if(seen.containsKey(c) && seen.get(c) >= left)
            left = seen.get(c) + 1;
        seen.put(c, right);
        best = Math.max(best, right - left + 1);
    }
    return best;
}
CODE],
                ['language' => 'cpp', 'label' => 'Longest unique substring', 'code' => <<<'CODE'
int longestUnique(string s){
    unordered_map<char,int> seen;
    int left = 0, best = 0;
    for(int right = 0; right < (int)s.size(); right++){
        char c = s[right];
        if(seen.count(c) && seen[c] >= left) left = seen[c] + 1;
        seen[c] = right;
        best = max(best, right - left + 1);
    }
    return best;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'When is the sliding window technique applicable?',
         'answer_md' => 'For problems on **contiguous** subarrays/substrings where you can update the result incrementally as the window moves (sum, count, distinct chars). It turns O(n²) scans into O(n).',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Longest substring without repeating characters.',
         'answer_md' => 'Expand a window with a right pointer; track last-seen index of each char. On a repeat inside the window, move left past it. Track max length. O(n).',
         'companies' => ['Amazon', 'Google', 'Adobe']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Minimum size subarray whose sum is ≥ target.',
         'answer_md' => 'Grow the window adding to a running sum; while sum ≥ target, record length and shrink from the left. O(n).',
         'companies' => ['Meta', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Longest Substring Without Repeating Characters', 'slug' => 'sw-longest-unique-substring', 'difficulty' => 'medium',
         'statement_md' => "Given a string, find the length of the longest substring without repeating characters.",
         'examples_md' => "```\n\"abcabcbb\" -> 3 (\"abc\")\n\"bbbbb\"    -> 1 (\"b\")\n\"pwwkew\"   -> 3 (\"wke\")\n```",
         'solutions' => [
            'python' => ['code' => "def length_of_longest(s):\n    seen = {}; left = best = 0\n    for right, c in enumerate(s):\n        if c in seen and seen[c] >= left:\n            left = seen[c] + 1\n        seen[c] = right\n        best = max(best, right - left + 1)\n    return best", 'explanation_md' => 'The window holds only distinct chars; left jumps past any repeat. O(n).'],
            'php' => ['code' => "<?php\nfunction lengthOfLongest(string \$s): int {\n    \$seen = []; \$left = 0; \$best = 0;\n    for (\$right = 0; \$right < strlen(\$s); \$right++) {\n        \$c = \$s[\$right];\n        if (isset(\$seen[\$c]) && \$seen[\$c] >= \$left) \$left = \$seen[\$c] + 1;\n        \$seen[\$c] = \$right;\n        \$best = max(\$best, \$right - \$left + 1);\n    }\n    return \$best;\n}", 'explanation_md' => ''],
            'java' => ['code' => "int lengthOfLongest(String s){\n    Map<Character,Integer> seen = new HashMap<>();\n    int left = 0, best = 0;\n    for(int right = 0; right < s.length(); right++){\n        char c = s.charAt(right);\n        if(seen.containsKey(c) && seen.get(c) >= left) left = seen.get(c) + 1;\n        seen.put(c, right);\n        best = Math.max(best, right - left + 1);\n    }\n    return best;\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "int lengthOfLongest(string s){\n    unordered_map<char,int> seen;\n    int left = 0, best = 0;\n    for(int right = 0; right < (int)s.size(); right++){\n        char c = s[right];\n        if(seen.count(c) && seen[c] >= left) left = seen[c] + 1;\n        seen[c] = right;\n        best = max(best, right - left + 1);\n    }\n    return best;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Sliding Window Quiz',
        'questions' => [
            ['question' => 'Sliding window applies to problems about:',
             'options' => [['text' => 'Contiguous subarrays/substrings', 'correct' => true], ['text' => 'Sorting arbitrary data', 'correct' => false], ['text' => 'Tree traversal', 'correct' => false], ['text' => 'Graph coloring', 'correct' => false]]],
            ['question' => 'Sliding window typically turns an O(n²) scan into:',
             'options' => [['text' => 'O(n)', 'correct' => true], ['text' => 'O(log n)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false], ['text' => 'O(1)', 'correct' => false]]],
            ['question' => 'In a variable-size window, you shrink from the left when:',
             'options' => [['text' => 'A constraint is violated', 'correct' => true], ['text' => 'The array ends', 'correct' => false], ['text' => 'Every iteration', 'correct' => false], ['text' => 'Never', 'correct' => false]]],
        ],
    ],
];
