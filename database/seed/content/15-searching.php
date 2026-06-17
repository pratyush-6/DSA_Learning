<?php
return [
    'title'       => 'Searching Algorithms',
    'slug'        => 'searching-algorithms',
    'level'       => 'intermediate',
    'icon'        => 'bi-search',
    'sort_order'  => 15,
    'description' => 'Linear search, binary search, and binary search on the answer.',

    'topics' => [
        [
            'title'   => 'Linear vs Binary Search',
            'slug'    => 'linear-binary-search',
            'summary' => 'Why sorting unlocks O(log n) search.',
            'theory_md' => <<<MD
**Linear search** scans every element until it finds the target — **O(n)**, works on any
list.

**Binary search** works only on a **sorted** array. It repeatedly looks at the middle:
if the target is smaller, search the left half; if larger, the right half. Each step
**halves** the search space → **O(log n)**.

```
lo, hi = 0, n-1
while lo <= hi:
    mid = (lo + hi) // 2
    if a[mid] == target: return mid
    if a[mid] < target: lo = mid + 1
    else: hi = mid - 1
```

> Watch for the classic bug: compute `mid = lo + (hi - lo) // 2` to avoid integer
> overflow in languages with fixed-width ints.
MD,
            'complexity_md' => "Linear: **O(n)**. Binary: **O(log n)** (requires sorted input). Both O(1) space iteratively.",
            'real_world_md' => "- **Dictionary / autocomplete** lookups.\n- **Database B-tree indexes** are a generalization of binary search.\n- **Git bisect** finds a buggy commit via binary search.",
            'code' => [
                ['language' => 'python', 'label' => 'Binary search', 'code' => <<<'CODE'
def binary_search(a, target):
    lo, hi = 0, len(a) - 1
    while lo <= hi:
        mid = lo + (hi - lo) // 2
        if a[mid] == target:
            return mid
        if a[mid] < target:
            lo = mid + 1
        else:
            hi = mid - 1
    return -1
CODE],
                ['language' => 'php', 'label' => 'Binary search', 'code' => <<<'CODE'
<?php
function binarySearch(array $a, int $target): int {
    $lo = 0; $hi = count($a) - 1;
    while ($lo <= $hi) {
        $mid = $lo + intdiv($hi - $lo, 2);
        if ($a[$mid] === $target) return $mid;
        if ($a[$mid] < $target) $lo = $mid + 1;
        else                    $hi = $mid - 1;
    }
    return -1;
}
CODE],
                ['language' => 'java', 'label' => 'Binary search', 'code' => <<<'CODE'
int binarySearch(int[] a, int target){
    int lo = 0, hi = a.length - 1;
    while(lo <= hi){
        int mid = lo + (hi - lo) / 2;
        if(a[mid] == target) return mid;
        if(a[mid] < target) lo = mid + 1;
        else hi = mid - 1;
    }
    return -1;
}
CODE],
                ['language' => 'cpp', 'label' => 'Binary search', 'code' => <<<'CODE'
int binarySearch(vector<int>& a, int target){
    int lo = 0, hi = a.size() - 1;
    while(lo <= hi){
        int mid = lo + (hi - lo) / 2;
        if(a[mid] == target) return mid;
        if(a[mid] < target) lo = mid + 1;
        else hi = mid - 1;
    }
    return -1;
}
CODE],
            ],
        ],
        [
            'title'   => 'Binary Search on the Answer',
            'slug'    => 'binary-search-on-answer',
            'summary' => 'A powerful pattern for optimization problems.',
            'theory_md' => <<<MD
Binary search isn't only for arrays. If a problem asks for the **minimum/maximum value
that satisfies a monotonic condition**, you can **binary search the answer space**.

Pattern: define `feasible(x)` that returns true/false and is **monotonic** (once true,
always true for larger x — or vice versa). Binary search for the boundary value.

Examples: "minimum capacity to ship packages in D days", "smallest divisor", "minimum
eating speed". This turns an O(n·max) brute force into **O(n·log(max))**.
MD,
            'complexity_md' => "O(n · log(range)) where range is the span of possible answers.",
            'real_world_md' => "Used in **capacity planning**, **rate limiting**, and **resource allocation** where you search for the smallest workable setting.",
            'code' => [
                ['language' => 'python', 'label' => 'Min eating speed (template)', 'code' => <<<'CODE'
def min_speed(piles, hours):
    def feasible(k):
        return sum((p + k - 1) // k for p in piles) <= hours
    lo, hi = 1, max(piles)
    while lo < hi:
        mid = (lo + hi) // 2
        if feasible(mid):
            hi = mid
        else:
            lo = mid + 1
    return lo
CODE],
                ['language' => 'php', 'label' => 'Min eating speed (template)', 'code' => <<<'CODE'
<?php
function minSpeed(array $piles, int $hours): int {
    $feasible = function(int $k) use ($piles, $hours): bool {
        $t = 0; foreach ($piles as $p) $t += intdiv($p + $k - 1, $k);
        return $t <= $hours;
    };
    $lo = 1; $hi = max($piles);
    while ($lo < $hi) {
        $mid = intdiv($lo + $hi, 2);
        if ($feasible($mid)) $hi = $mid; else $lo = $mid + 1;
    }
    return $lo;
}
CODE],
                ['language' => 'java', 'label' => 'Boundary search', 'code' => <<<'CODE'
int minSpeed(int[] piles, int hours){
    int lo = 1, hi = Arrays.stream(piles).max().getAsInt();
    while(lo < hi){
        int mid = lo + (hi - lo) / 2;
        long t = 0; for(int p : piles) t += (p + mid - 1) / mid;
        if(t <= hours) hi = mid; else lo = mid + 1;
    }
    return lo;
}
CODE],
                ['language' => 'cpp', 'label' => 'Boundary search', 'code' => <<<'CODE'
int minSpeed(vector<int>& piles, int hours){
    int lo = 1, hi = *max_element(piles.begin(), piles.end());
    while(lo < hi){
        int mid = lo + (hi - lo) / 2;
        long t = 0; for(int p : piles) t += (p + mid - 1) / mid;
        if(t <= hours) hi = mid; else lo = mid + 1;
    }
    return lo;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What is the prerequisite for binary search and what is its complexity?',
         'answer_md' => 'The data must be **sorted** (or otherwise monotonic). Binary search runs in **O(log n)** because each comparison halves the search space.',
         'companies' => ['Amazon', 'Adobe']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Search in a rotated sorted array.',
         'answer_md' => 'Modified binary search: at each step one half is sorted. Check which half is sorted and whether the target lies within it, then discard the other half. O(log n).',
         'companies' => ['Google', 'Amazon', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Find the first and last position of a target in a sorted array.',
         'answer_md' => 'Run binary search twice — once for the leftmost occurrence, once for the rightmost — by biasing the search left/right on equality. O(log n).',
         'companies' => ['Meta', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Binary Search', 'slug' => 'searching-binary-search', 'difficulty' => 'easy',
         'statement_md' => "Given a sorted array of distinct integers and a target, return its index or -1 if not present. Must run in O(log n).",
         'examples_md' => "```\nnums = [-1,0,3,5,9,12], target = 9 -> 4\nnums = [-1,0,3,5,9,12], target = 2 -> -1\n```",
         'solutions' => [
            'python' => ['code' => "def search(nums, target):\n    lo, hi = 0, len(nums) - 1\n    while lo <= hi:\n        mid = lo + (hi - lo) // 2\n        if nums[mid] == target: return mid\n        if nums[mid] < target: lo = mid + 1\n        else: hi = mid - 1\n    return -1", 'explanation_md' => 'Halve the range each step → O(log n).'],
            'php' => ['code' => "<?php\nfunction search(array \$nums, int \$target): int {\n    \$lo = 0; \$hi = count(\$nums) - 1;\n    while (\$lo <= \$hi) {\n        \$mid = \$lo + intdiv(\$hi - \$lo, 2);\n        if (\$nums[\$mid] === \$target) return \$mid;\n        if (\$nums[\$mid] < \$target) \$lo = \$mid + 1; else \$hi = \$mid - 1;\n    }\n    return -1;\n}", 'explanation_md' => ''],
            'java' => ['code' => "int search(int[] nums, int target){\n    int lo=0, hi=nums.length-1;\n    while(lo<=hi){\n        int mid = lo + (hi-lo)/2;\n        if(nums[mid]==target) return mid;\n        if(nums[mid]<target) lo=mid+1; else hi=mid-1;\n    }\n    return -1;\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "int search(vector<int>& nums, int target){\n    int lo=0, hi=nums.size()-1;\n    while(lo<=hi){\n        int mid = lo + (hi-lo)/2;\n        if(nums[mid]==target) return mid;\n        if(nums[mid]<target) lo=mid+1; else hi=mid-1;\n    }\n    return -1;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Searching Quiz',
        'questions' => [
            ['question' => 'Binary search requires the input to be:',
             'options' => [['text' => 'Sorted', 'correct' => true], ['text' => 'Unsorted', 'correct' => false], ['text' => 'A linked list', 'correct' => false], ['text' => 'A hash table', 'correct' => false]]],
            ['question' => 'Time complexity of binary search:',
             'options' => [['text' => 'O(log n)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false], ['text' => 'O(1)', 'correct' => false]]],
            ['question' => 'Why compute mid as lo + (hi - lo)/2?',
             'options' => [['text' => 'To avoid integer overflow', 'correct' => true], ['text' => 'It is faster', 'correct' => false], ['text' => 'It uses less memory', 'correct' => false], ['text' => 'It sorts the array', 'correct' => false]]],
        ],
    ],
];
