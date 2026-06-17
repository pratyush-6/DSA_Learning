<?php
return [
    'title'       => 'Time & Space Complexity',
    'slug'        => 'time-space-complexity',
    'level'       => 'beginner',
    'icon'        => 'bi-speedometer2',
    'sort_order'  => 2,
    'description' => 'Big-O notation: how to measure and compare the efficiency of algorithms.',

    'topics' => [
        [
            'title'   => 'Big-O Notation',
            'slug'    => 'big-o-notation',
            'summary' => 'Describing how runtime grows as input grows.',
            'theory_md' => <<<MD
**Big-O** describes how the running time (or memory) of an algorithm grows as the
input size `n` grows, ignoring constants and lower-order terms. It captures the
**worst-case** growth rate.

### Common complexities (fast → slow)
| Big-O | Name | Example |
|-------|------|---------|
| O(1) | constant | array index access |
| O(log n) | logarithmic | binary search |
| O(n) | linear | scanning a list |
| O(n log n) | linearithmic | merge sort, heap sort |
| O(n²) | quadratic | nested loops, bubble sort |
| O(2ⁿ) | exponential | naive subsets / recursion |
| O(n!) | factorial | permutations |

Rules of thumb:
- **Drop constants:** O(2n) → O(n).
- **Drop lower-order terms:** O(n² + n) → O(n²).
- **Nested loops multiply:** loop inside loop over n → O(n²).
MD,
            'complexity_md' => "Always ask about both **time** and **space**. A faster algorithm that uses too much memory may be unusable.",
            'real_world_md' => <<<MD
- A search that is O(n) feels instant on 1,000 rows but takes seconds on 100M rows.
- Binary search (O(log n)) finds a name in a sorted phonebook of a billion entries in ~30 steps.
MD,
            'code' => [
                ['language' => 'python', 'label' => 'O(1), O(n), O(n^2)', 'code' => <<<'CODE'
def constant(a):       # O(1)
    return a[0]

def linear(a):         # O(n)
    total = 0
    for x in a:
        total += x
    return total

def quadratic(a):      # O(n^2)
    pairs = []
    for i in a:
        for j in a:
            pairs.append((i, j))
    return pairs
CODE],
                ['language' => 'php', 'label' => 'O(1), O(n), O(n^2)', 'code' => <<<'CODE'
<?php
function constantTime(array $a) { return $a[0]; }          // O(1)

function linearTime(array $a) {                            // O(n)
    $t = 0; foreach ($a as $x) $t += $x; return $t;
}

function quadraticTime(array $a) {                         // O(n^2)
    foreach ($a as $i) foreach ($a as $j) { /* ... */ }
}
CODE],
                ['language' => 'java', 'label' => 'O(1), O(n), O(n^2)', 'code' => <<<'CODE'
int constant(int[] a) { return a[0]; }            // O(1)

int linear(int[] a) {                             // O(n)
    int t = 0; for (int x : a) t += x; return t;
}

void quadratic(int[] a) {                         // O(n^2)
    for (int i : a) for (int j : a) { /* ... */ }
}
CODE],
                ['language' => 'cpp', 'label' => 'O(1), O(n), O(n^2)', 'code' => <<<'CODE'
int constant(vector<int>& a){ return a[0]; }      // O(1)

long linear(vector<int>& a){                      // O(n)
    long t=0; for(int x:a) t+=x; return t;
}

void quadratic(vector<int>& a){                   // O(n^2)
    for(int i:a) for(int j:a){ /* ... */ }
}
CODE],
            ],
        ],
        [
            'title'   => 'Space Complexity',
            'slug'    => 'space-complexity',
            'summary' => 'Counting the extra memory an algorithm uses.',
            'theory_md' => <<<MD
**Space complexity** measures the extra memory an algorithm needs as a function of
input size, *not counting* the input itself (that part is "auxiliary space").

- Reversing an array **in place**: O(1) extra space.
- Building a new reversed copy: O(n) extra space.
- Recursion uses **call-stack** memory: depth d → O(d) space.

There is often a **time–space trade-off**: hashing uses extra memory (space) to make
lookups faster (time).
MD,
            'complexity_md' => "Recursive depth counts! A recursion that goes n deep uses O(n) stack space even if it does O(1) work per call.",
            'real_world_md' => "Memoization (caching results) trades memory for speed — central to dynamic programming.",
            'code' => [
                ['language' => 'python', 'label' => 'In-place vs copy', 'code' => <<<'CODE'
def reverse_inplace(a):   # O(1) extra space
    i, j = 0, len(a) - 1
    while i < j:
        a[i], a[j] = a[j], a[i]
        i += 1; j -= 1
    return a

def reverse_copy(a):      # O(n) extra space
    return a[::-1]
CODE],
                ['language' => 'php', 'label' => 'In-place vs copy', 'code' => <<<'CODE'
<?php
// O(1) extra space
function reverseInPlace(array $a): array {
    $i = 0; $j = count($a) - 1;
    while ($i < $j) { [$a[$i], $a[$j]] = [$a[$j], $a[$i]]; $i++; $j--; }
    return $a;
}
// O(n) extra space
function reverseCopy(array $a): array { return array_reverse($a); }
CODE],
                ['language' => 'java', 'label' => 'In-place reverse', 'code' => <<<'CODE'
void reverseInPlace(int[] a) {           // O(1) extra space
    int i = 0, j = a.length - 1;
    while (i < j) {
        int t = a[i]; a[i] = a[j]; a[j] = t;
        i++; j--;
    }
}
CODE],
                ['language' => 'cpp', 'label' => 'In-place reverse', 'code' => <<<'CODE'
void reverseInPlace(vector<int>& a){     // O(1) extra space
    int i=0, j=a.size()-1;
    while(i<j){ swap(a[i],a[j]); i++; j--; }
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What does Big-O notation measure?',
         'answer_md' => 'The **asymptotic upper bound** on how an algorithm’s time or space grows with input size, ignoring constants and lower-order terms. It expresses worst-case growth rate.',
         'companies' => ['Amazon', 'Adobe']],
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'Why do we drop constants and lower-order terms in Big-O?',
         'answer_md' => 'Because Big-O describes growth as n → ∞. For large n, the highest-order term dominates and constants do not change the growth *class*. O(2n+5) and O(n) scale the same way.',
         'companies' => ['Microsoft']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'What is the complexity of two consecutive loops vs two nested loops over n?',
         'answer_md' => 'Consecutive loops: O(n) + O(n) = **O(n)**. Nested loops: O(n) × O(n) = **O(n²)**. Sequential work adds; nested work multiplies.',
         'companies' => ['Google']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'What is amortized time complexity? Give an example.',
         'answer_md' => 'The average cost per operation over a sequence, even if some individual operations are expensive. Example: appending to a dynamic array is **amortized O(1)** — occasional O(n) resizes are spread across many cheap appends.',
         'companies' => ['Meta', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Classify the Complexity', 'slug' => 'complexity-classify', 'difficulty' => 'easy',
         'statement_md' => "Given a function with a single loop from 0 to n that does constant work, what is its time complexity? Implement a function that sums 1..n and confirm it is O(n).",
         'examples_md' => "```\nsumTo(5) = 15\n```",
         'solutions' => [
            'python' => ['code' => "def sum_to(n):      # O(n) time, O(1) space\n    total = 0\n    for i in range(1, n+1):\n        total += i\n    return total\n\n# O(1) closed form:\ndef sum_to_fast(n):\n    return n * (n + 1) // 2", 'explanation_md' => 'The loop runs n times → O(n). The closed form is O(1).'],
            'php' => ['code' => "<?php\nfunction sumTo(int \$n): int {     // O(n)\n    \$t = 0; for (\$i = 1; \$i <= \$n; \$i++) \$t += \$i; return \$t;\n}\nfunction sumToFast(int \$n): int { return \$n * (\$n + 1) / 2; } // O(1)", 'explanation_md' => ''],
            'java' => ['code' => "int sumTo(int n){ int t=0; for(int i=1;i<=n;i++) t+=i; return t; } // O(n)\nint sumToFast(int n){ return n*(n+1)/2; }                          // O(1)", 'explanation_md' => ''],
            'cpp' => ['code' => "long sumTo(int n){ long t=0; for(int i=1;i<=n;i++) t+=i; return t; } // O(n)\nlong sumToFast(int n){ return (long)n*(n+1)/2; }                    // O(1)", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Complexity Quiz',
        'questions' => [
            ['question' => 'What is the time complexity of binary search?',
             'explanation_md' => 'Each step halves the search space.',
             'options' => [['text' => 'O(log n)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(1)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false]]],
            ['question' => 'Two nested loops, each running n times, give what complexity?',
             'options' => [['text' => 'O(n²)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(2n)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false]]],
            ['question' => 'O(2n + 100) simplifies to:',
             'explanation_md' => 'Drop constants and coefficients.',
             'options' => [['text' => 'O(n)', 'correct' => true], ['text' => 'O(2n)', 'correct' => false], ['text' => 'O(100)', 'correct' => false], ['text' => 'O(n²)', 'correct' => false]]],
            ['question' => 'Which is the fastest-growing (worst) complexity?',
             'options' => [['text' => 'O(n!)', 'correct' => true], ['text' => 'O(n²)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false], ['text' => 'O(2ⁿ)', 'correct' => false]]],
        ],
    ],
];
