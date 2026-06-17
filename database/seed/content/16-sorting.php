<?php
return [
    'title'       => 'Sorting Algorithms',
    'slug'        => 'sorting-algorithms',
    'level'       => 'intermediate',
    'icon'        => 'bi-sort-numeric-down',
    'sort_order'  => 16,
    'description' => 'From O(n²) bubble sort to O(n log n) merge & quick sort, plus stability.',

    'topics' => [
        [
            'title'   => 'Comparison Sorts Overview',
            'slug'    => 'comparison-sorts',
            'summary' => 'Bubble, insertion, merge, and quick sort and their trade-offs.',
            'theory_md' => <<<MD
| Algorithm | Best | Average | Worst | Space | Stable |
|---|---|---|---|---|---|
| Bubble | O(n) | O(n²) | O(n²) | O(1) | Yes |
| Insertion | O(n) | O(n²) | O(n²) | O(1) | Yes |
| Merge | O(n log n) | O(n log n) | O(n log n) | O(n) | Yes |
| Quick | O(n log n) | O(n log n) | O(n²) | O(log n) | No |
| Heap | O(n log n) | O(n log n) | O(n log n) | O(1) | No |

- **Stable** sort keeps equal elements in their original relative order (matters when
  sorting records by multiple keys).
- **Comparison sorts** cannot beat **O(n log n)** in the worst case (proven lower bound).
- **Merge sort:** divide in half, sort each, **merge**. Predictable, stable, uses O(n) extra space.
- **Quick sort:** pick a **pivot**, partition smaller/larger, recurse. Fast in practice,
  but O(n²) on bad pivots (mitigated by random/median pivots).
MD,
            'complexity_md' => "Best general-purpose: **O(n log n)** (merge/quick/heap). Use insertion sort for tiny or nearly-sorted inputs.",
            'real_world_md' => "Language libraries use hybrids: **Timsort** (Python/Java objects) is a stable merge+insertion hybrid; C++ `sort` uses **introsort** (quick+heap+insertion).",
            'code' => [
                ['language' => 'python', 'label' => 'Merge sort', 'code' => <<<'CODE'
def merge_sort(a):
    if len(a) <= 1:
        return a
    mid = len(a) // 2
    left = merge_sort(a[:mid])
    right = merge_sort(a[mid:])
    res, i, j = [], 0, 0
    while i < len(left) and j < len(right):
        if left[i] <= right[j]:
            res.append(left[i]); i += 1
        else:
            res.append(right[j]); j += 1
    res.extend(left[i:]); res.extend(right[j:])
    return res
CODE],
                ['language' => 'php', 'label' => 'Merge sort', 'code' => <<<'CODE'
<?php
function mergeSort(array $a): array {
    if (count($a) <= 1) return $a;
    $mid = intdiv(count($a), 2);
    $left = mergeSort(array_slice($a, 0, $mid));
    $right = mergeSort(array_slice($a, $mid));
    $res = []; $i = $j = 0;
    while ($i < count($left) && $j < count($right)) {
        if ($left[$i] <= $right[$j]) $res[] = $left[$i++];
        else                         $res[] = $right[$j++];
    }
    return array_merge($res, array_slice($left, $i), array_slice($right, $j));
}
CODE],
                ['language' => 'java', 'label' => 'Quick sort (partition)', 'code' => <<<'CODE'
void quickSort(int[] a, int lo, int hi){
    if(lo >= hi) return;
    int pivot = a[hi], i = lo;
    for(int j = lo; j < hi; j++)
        if(a[j] < pivot){ int t=a[i]; a[i]=a[j]; a[j]=t; i++; }
    int t=a[i]; a[i]=a[hi]; a[hi]=t;
    quickSort(a, lo, i-1);
    quickSort(a, i+1, hi);
}
CODE],
                ['language' => 'cpp', 'label' => 'std::sort', 'code' => <<<'CODE'
#include <algorithm>
#include <vector>
using namespace std;
vector<int> v = {5,2,8,1};
sort(v.begin(), v.end());            // ascending, O(n log n)
sort(v.begin(), v.end(), greater<int>()); // descending
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What does it mean for a sort to be stable, and when does it matter?',
         'answer_md' => 'A **stable** sort preserves the relative order of elements with equal keys. It matters when sorting by multiple criteria (e.g., sort by name, then stably by age keeps name order within each age).',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'Compare merge sort and quick sort.',
         'answer_md' => 'Merge sort is **stable**, guaranteed O(n log n), but uses O(n) extra space. Quick sort sorts **in place** and is usually faster in practice, but is unstable and O(n²) on bad pivots (avoid with randomized/median-of-three pivots).',
         'companies' => ['Google', 'Meta']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'Why can no comparison sort be faster than O(n log n) in the worst case?',
         'answer_md' => 'There are n! possible orderings; a comparison sort’s decision tree must distinguish all of them, requiring at least log₂(n!) ≈ n log n comparisons. Non-comparison sorts (counting/radix) can do O(n) under special conditions.',
         'companies' => ['Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Sort Colors (Dutch National Flag)', 'slug' => 'sorting-sort-colors', 'difficulty' => 'medium',
         'statement_md' => "Given an array with values 0, 1, and 2, sort it in place in one pass (do not just count).",
         'examples_md' => "```\n[2,0,2,1,1,0] -> [0,0,1,1,2,2]\n```",
         'constraints_md' => "- Values are only 0, 1, or 2.",
         'solutions' => [
            'python' => ['code' => "def sort_colors(nums):\n    low, mid, high = 0, 0, len(nums) - 1\n    while mid <= high:\n        if nums[mid] == 0:\n            nums[low], nums[mid] = nums[mid], nums[low]\n            low += 1; mid += 1\n        elif nums[mid] == 1:\n            mid += 1\n        else:\n            nums[mid], nums[high] = nums[high], nums[mid]\n            high -= 1", 'explanation_md' => 'Three pointers partition into <1, =1, >1 in a single O(n) pass, O(1) space.'],
            'php' => ['code' => "<?php\nfunction sortColors(array &\$nums): void {\n    \$low = \$mid = 0; \$high = count(\$nums) - 1;\n    while (\$mid <= \$high) {\n        if (\$nums[\$mid] === 0) { [\$nums[\$low],\$nums[\$mid]]=[\$nums[\$mid],\$nums[\$low]]; \$low++; \$mid++; }\n        elseif (\$nums[\$mid] === 1) { \$mid++; }\n        else { [\$nums[\$mid],\$nums[\$high]]=[\$nums[\$high],\$nums[\$mid]]; \$high--; }\n    }\n}", 'explanation_md' => ''],
            'java' => ['code' => "void sortColors(int[] nums){\n    int low=0, mid=0, high=nums.length-1;\n    while(mid <= high){\n        if(nums[mid]==0){ int t=nums[low]; nums[low]=nums[mid]; nums[mid]=t; low++; mid++; }\n        else if(nums[mid]==1){ mid++; }\n        else { int t=nums[mid]; nums[mid]=nums[high]; nums[high]=t; high--; }\n    }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void sortColors(vector<int>& nums){\n    int low=0, mid=0, high=nums.size()-1;\n    while(mid <= high){\n        if(nums[mid]==0) swap(nums[low++], nums[mid++]);\n        else if(nums[mid]==1) mid++;\n        else swap(nums[mid], nums[high--]);\n    }\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Sorting Quiz',
        'questions' => [
            ['question' => 'Worst-case time of quick sort:',
             'options' => [['text' => 'O(n²)', 'correct' => true], ['text' => 'O(n log n)', 'correct' => false], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false]]],
            ['question' => 'Which sort is stable and guaranteed O(n log n)?',
             'options' => [['text' => 'Merge sort', 'correct' => true], ['text' => 'Quick sort', 'correct' => false], ['text' => 'Heap sort', 'correct' => false], ['text' => 'Selection sort', 'correct' => false]]],
            ['question' => 'Lower bound for comparison-based sorting:',
             'options' => [['text' => 'O(n log n)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false], ['text' => 'O(n²)', 'correct' => false]]],
        ],
    ],
];
