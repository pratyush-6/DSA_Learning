<?php
return [
    'title'       => 'Introduction to DSA',
    'slug'        => 'introduction-to-dsa',
    'level'       => 'beginner',
    'icon'        => 'bi-signpost-2',
    'sort_order'  => 1,
    'description' => 'What data structures and algorithms are, and why they matter for real software and interviews.',

    'topics' => [
        [
            'title'   => 'What are Data Structures & Algorithms?',
            'slug'    => 'what-is-dsa',
            'summary' => 'The vocabulary of efficient programming.',
            'theory_md' => <<<MD
A **data structure** is a way of organizing and storing data so it can be used
efficiently. An **algorithm** is a step-by-step procedure to solve a problem.

> **Program = Data Structures + Algorithms.**

Choosing the right data structure often matters more than clever code. For example,
checking "is this value present?" is **O(n)** in a plain array but **O(1)** in a
hash set. Same goal, hugely different speed.

### Why learn DSA?
- **Performance:** the right structure can turn minutes into milliseconds.
- **Scalability:** code that works on 100 items may crawl on 10 million.
- **Interviews:** nearly every tech company tests DSA.
- **Problem-solving:** DSA trains you to break problems into smaller pieces.
MD,
            'real_world_md' => <<<MD
- **Google Maps** uses graphs + shortest-path algorithms to route you.
- **Databases** use B-trees and hash indexes for fast lookups.
- **Undo/redo** uses stacks.
- **Autocomplete** uses tries.
- **Task schedulers** use heaps / priority queues.
MD,
            'code' => [
                ['language' => 'python', 'label' => 'Right structure = right speed', 'code' => <<<'CODE'
# Membership test: list is O(n), set is O(1)
items_list = [1, 2, 3, 4, 5]
items_set = {1, 2, 3, 4, 5}

print(3 in items_list)  # scans elements -> O(n)
print(3 in items_set)   # hashed lookup -> O(1)
CODE],
                ['language' => 'php', 'label' => 'Right structure = right speed', 'code' => <<<'CODE'
<?php
$list = [1, 2, 3, 4, 5];          // in_array is O(n)
$set  = array_flip($list);        // keys give O(1) lookup

var_dump(in_array(3, $list));     // O(n)
var_dump(isset($set[3]));         // O(1)
CODE],
                ['language' => 'java', 'label' => 'Right structure = right speed', 'code' => <<<'CODE'
import java.util.*;
List<Integer> list = List.of(1,2,3,4,5);  // contains is O(n)
Set<Integer> set = new HashSet<>(list);   // contains is O(1)
System.out.println(list.contains(3));
System.out.println(set.contains(3));
CODE],
                ['language' => 'cpp', 'label' => 'Right structure = right speed', 'code' => <<<'CODE'
#include <vector>
#include <unordered_set>
using namespace std;
vector<int> v = {1,2,3,4,5};                 // find is O(n)
unordered_set<int> s = {1,2,3,4,5};          // count is O(1)
// s.count(3) -> 1 in average O(1)
CODE],
            ],
        ],
        [
            'title'   => 'How to Approach a DSA Problem',
            'slug'    => 'dsa-problem-approach',
            'summary' => 'A repeatable framework for solving and explaining problems.',
            'theory_md' => <<<MD
A reliable framework (great for interviews too):

1. **Understand** the problem. Restate it. Ask about input size and edge cases.
2. **Examples.** Work a small example by hand, including edge cases (empty, one element, duplicates).
3. **Brute force first.** Get *a* correct solution and state its complexity.
4. **Optimize.** Look for repeated work, sorting, hashing, two pointers, etc.
5. **Code** cleanly.
6. **Test** against your examples and edge cases.

Always state the **time and space complexity** of your solution.
MD,
            'real_world_md' => "This is exactly how senior engineers reason during design reviews — start simple, measure, then optimize the bottleneck.",
            'code' => [],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What is the difference between a data structure and an algorithm?',
         'answer_md' => 'A **data structure** organizes/stores data (e.g., array, tree). An **algorithm** is a sequence of steps that operates on data to solve a problem (e.g., binary search). They work together.',
         'companies' => ['Amazon']],
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'Why might two correct programs have very different performance?',
         'answer_md' => 'Because they use different data structures/algorithms with different time complexities. The same task can be O(n²) or O(n log n) depending on approach, which matters enormously at scale.',
         'companies' => ['Microsoft']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'Walk me through how you would approach a problem you have never seen.',
         'answer_md' => 'Clarify the problem and constraints → work examples by hand → write a brute-force solution and state its complexity → identify repeated work and optimize (hashing, sorting, two pointers, DP) → code cleanly → test edge cases.',
         'companies' => ['Google', 'Meta']],
    ],

    'problems' => [
        ['title' => 'FizzBuzz', 'slug' => 'intro-fizzbuzz', 'difficulty' => 'easy',
         'statement_md' => "Print numbers 1..n. For multiples of 3 print `Fizz`, for multiples of 5 print `Buzz`, for multiples of both print `FizzBuzz`.",
         'examples_md' => "```\nn = 5 -> 1, 2, Fizz, 4, Buzz\n```",
         'solutions' => [
            'python' => ['code' => "def fizzbuzz(n):\n    for i in range(1, n+1):\n        if i % 15 == 0: print('FizzBuzz')\n        elif i % 3 == 0: print('Fizz')\n        elif i % 5 == 0: print('Buzz')\n        else: print(i)", 'explanation_md' => 'Check divisibility by 15 first (3 and 5).'],
            'php' => ['code' => "<?php\nfor (\$i = 1; \$i <= \$n; \$i++) {\n    if (\$i % 15 == 0) echo \"FizzBuzz\\n\";\n    elseif (\$i % 3 == 0) echo \"Fizz\\n\";\n    elseif (\$i % 5 == 0) echo \"Buzz\\n\";\n    else echo \"\$i\\n\";\n}", 'explanation_md' => ''],
            'java' => ['code' => "for (int i = 1; i <= n; i++) {\n    if (i % 15 == 0) System.out.println(\"FizzBuzz\");\n    else if (i % 3 == 0) System.out.println(\"Fizz\");\n    else if (i % 5 == 0) System.out.println(\"Buzz\");\n    else System.out.println(i);\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "for (int i = 1; i <= n; i++) {\n    if (i % 15 == 0) cout << \"FizzBuzz\\n\";\n    else if (i % 3 == 0) cout << \"Fizz\\n\";\n    else if (i % 5 == 0) cout << \"Buzz\\n\";\n    else cout << i << \"\\n\";\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Introduction Quiz',
        'questions' => [
            ['question' => 'Which best describes an algorithm?',
             'options' => [
                ['text' => 'A step-by-step procedure to solve a problem', 'correct' => true],
                ['text' => 'A way to store data in memory', 'correct' => false],
                ['text' => 'A programming language', 'correct' => false],
                ['text' => 'A type of computer hardware', 'correct' => false]]],
            ['question' => 'Checking membership is fastest in which structure?',
             'options' => [
                ['text' => 'Hash set', 'correct' => true],
                ['text' => 'Unsorted array', 'correct' => false],
                ['text' => 'Linked list', 'correct' => false],
                ['text' => 'Stack', 'correct' => false]]],
            ['question' => 'What should you usually do first when solving a new problem?',
             'options' => [
                ['text' => 'Understand it and try small examples', 'correct' => true],
                ['text' => 'Write the most optimized solution immediately', 'correct' => false],
                ['text' => 'Pick a random data structure', 'correct' => false],
                ['text' => 'Submit and see if it passes', 'correct' => false]]],
        ],
    ],
];
