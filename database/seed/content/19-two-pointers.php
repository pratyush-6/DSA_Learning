<?php
return [
    'title'       => 'Two Pointers',
    'slug'        => 'two-pointers',
    'level'       => 'intermediate',
    'icon'        => 'bi-arrows-collapse',
    'sort_order'  => 19,
    'description' => 'Use two indices moving through data to achieve O(n) on sorted/array problems.',

    'topics' => [
        [
            'title'   => 'The Two-Pointer Patterns',
            'slug'    => 'two-pointer-patterns',
            'summary' => 'Opposite-ends, same-direction, and fast/slow pointers.',
            'theory_md' => <<<MD
The **two-pointer** technique uses two indices to scan data, replacing many nested
loops with a single O(n) pass. Three common patterns:

1. **Opposite ends:** `left` at start, `right` at end, moving toward each other.
   Used for **pair sums in a sorted array**, **palindrome checks**, **container with
   most water**, and **reversing in place**.
2. **Same direction (slow/fast write index):** one pointer reads, one writes. Used for
   **removing duplicates in place** and **moving zeros**.
3. **Fast/slow (Floyd):** different speeds — **cycle detection**, **finding the middle**.

Two pointers usually need the data **sorted** (for the opposite-ends sum pattern) or rely
on an in-place rearrangement invariant.
MD,
            'complexity_md' => "**O(n)** time (each pointer moves at most n steps), **O(1)** extra space. Sorting first, if needed, adds O(n log n).",
            'real_world_md' => "- **Merging** two sorted streams/files.\n- **De-duplication** of sorted logs.\n- **Reading from both ends** of a buffer.",
            'code' => [
                ['language' => 'python', 'label' => 'Pair sum + remove duplicates', 'code' => <<<'CODE'
def pair_sum_sorted(a, target):     # opposite ends
    i, j = 0, len(a) - 1
    while i < j:
        s = a[i] + a[j]
        if s == target: return (i, j)
        if s < target: i += 1
        else: j -= 1
    return None

def remove_duplicates(a):           # same direction
    if not a: return 0
    w = 1
    for r in range(1, len(a)):
        if a[r] != a[w - 1]:
            a[w] = a[r]; w += 1
    return w                         # new length
CODE],
                ['language' => 'php', 'label' => 'Pair sum + remove duplicates', 'code' => <<<'CODE'
<?php
function pairSumSorted(array $a, int $target): ?array {
    $i = 0; $j = count($a) - 1;
    while ($i < $j) {
        $s = $a[$i] + $a[$j];
        if ($s === $target) return [$i, $j];
        if ($s < $target) $i++; else $j--;
    }
    return null;
}
function removeDuplicates(array &$a): int {
    if (!$a) return 0;
    $w = 1;
    for ($r = 1; $r < count($a); $r++)
        if ($a[$r] !== $a[$w - 1]) $a[$w++] = $a[$r];
    return $w;
}
CODE],
                ['language' => 'java', 'label' => 'Pair sum (sorted)', 'code' => <<<'CODE'
int[] pairSumSorted(int[] a, int target){
    int i = 0, j = a.length - 1;
    while(i < j){
        int s = a[i] + a[j];
        if(s == target) return new int[]{i, j};
        if(s < target) i++; else j--;
    }
    return new int[0];
}
CODE],
                ['language' => 'cpp', 'label' => 'Pair sum (sorted)', 'code' => <<<'CODE'
pair<int,int> pairSumSorted(vector<int>& a, int target){
    int i = 0, j = a.size() - 1;
    while(i < j){
        int s = a[i] + a[j];
        if(s == target) return {i, j};
        if(s < target) i++; else j--;
    }
    return {-1, -1};
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'How do two pointers reduce time complexity?',
         'answer_md' => 'Instead of nested loops checking all pairs (O(n²)), two pointers move through the data once, each advancing at most n times → **O(n)**, often O(1) extra space.',
         'companies' => ['Amazon', 'Adobe']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Container With Most Water — maximize area between two lines.',
         'answer_md' => 'Two pointers at both ends; area = min(height[i], height[j]) × width. Move the pointer at the **shorter** line inward (the only move that can increase area). O(n).',
         'companies' => ['Amazon', 'Google', 'Meta']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => '3Sum: find all unique triplets summing to zero.',
         'answer_md' => 'Sort, then fix one element and use the opposite-ends two-pointer scan on the rest, skipping duplicates. O(n²).',
         'companies' => ['Amazon', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Two Sum II (Sorted Input)', 'slug' => 'tp-two-sum-sorted', 'difficulty' => 'easy',
         'statement_md' => "Given a **1-indexed sorted** array and a target, return the indices (1-based) of the two numbers that add up to the target. Exactly one solution exists.",
         'examples_md' => "```\nnumbers = [2,7,11,15], target = 9 -> [1, 2]\n```",
         'constraints_md' => "- The array is sorted in non-decreasing order.",
         'solutions' => [
            'python' => ['code' => "def two_sum_sorted(numbers, target):\n    i, j = 0, len(numbers) - 1\n    while i < j:\n        s = numbers[i] + numbers[j]\n        if s == target:\n            return [i + 1, j + 1]\n        if s < target:\n            i += 1\n        else:\n            j -= 1\n    return []", 'explanation_md' => 'Because the array is sorted, moving the pointers adjusts the sum predictably. O(n), O(1) space.'],
            'php' => ['code' => "<?php\nfunction twoSumSorted(array \$numbers, int \$target): array {\n    \$i = 0; \$j = count(\$numbers) - 1;\n    while (\$i < \$j) {\n        \$s = \$numbers[\$i] + \$numbers[\$j];\n        if (\$s === \$target) return [\$i + 1, \$j + 1];\n        if (\$s < \$target) \$i++; else \$j--;\n    }\n    return [];\n}", 'explanation_md' => ''],
            'java' => ['code' => "int[] twoSum(int[] numbers, int target){\n    int i = 0, j = numbers.length - 1;\n    while(i < j){\n        int s = numbers[i] + numbers[j];\n        if(s == target) return new int[]{i+1, j+1};\n        if(s < target) i++; else j--;\n    }\n    return new int[0];\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "vector<int> twoSum(vector<int>& numbers, int target){\n    int i = 0, j = numbers.size() - 1;\n    while(i < j){\n        int s = numbers[i] + numbers[j];\n        if(s == target) return {i+1, j+1};\n        if(s < target) i++; else j--;\n    }\n    return {};\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Two Pointers Quiz',
        'questions' => [
            ['question' => 'The opposite-ends two-pointer pair-sum pattern requires the array to be:',
             'options' => [['text' => 'Sorted', 'correct' => true], ['text' => 'Unsorted', 'correct' => false], ['text' => 'A linked list', 'correct' => false], ['text' => 'All positive', 'correct' => false]]],
            ['question' => 'Typical space complexity of a two-pointer solution:',
             'options' => [['text' => 'O(1)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(n²)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false]]],
            ['question' => 'In Container With Most Water you move the pointer at the:',
             'options' => [['text' => 'Shorter line', 'correct' => true], ['text' => 'Taller line', 'correct' => false], ['text' => 'Left always', 'correct' => false], ['text' => 'Middle', 'correct' => false]]],
        ],
    ],
];
