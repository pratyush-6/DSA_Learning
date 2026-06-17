<?php
/**
 * Content: Arrays (Beginner). This file is the reference template for the
 * structure every content file follows.
 */
return [
    'title'       => 'Arrays',
    'slug'        => 'arrays',
    'level'       => 'beginner',
    'icon'        => 'bi-grid-3x3-gap',
    'sort_order'  => 3,
    'description' => 'Contiguous, index-based collections — the most fundamental data structure.',

    'topics' => [
        [
            'title'   => 'Introduction to Arrays',
            'slug'    => 'arrays-introduction',
            'summary' => 'What an array is, how it is stored in memory, and why indexing is O(1).',
            'theory_md' => <<<MD
An **array** is a collection of elements stored in **contiguous memory locations**,
each identified by an **index**. Most languages use **0-based indexing**, so the
first element is at index `0`.

Because the elements sit next to each other in memory, the address of any element
can be computed directly:

```
address(i) = base_address + i * size_of_element
```

This is why reading or writing an element by its index is a constant-time **O(1)**
operation — the computer does not need to scan through the array.

### Key properties
- **Fixed type:** all elements are usually the same type.
- **Random access:** any index is reachable in O(1).
- **Fixed size** (in low-level languages like C++/Java); PHP and Python arrays/lists grow dynamically.
MD,
            'complexity_md' => <<<MD
| Operation              | Time   | Why |
|------------------------|--------|-----|
| Access by index        | O(1)   | direct address calculation |
| Update by index        | O(1)   | direct write |
| Search (unsorted)      | O(n)   | may scan every element |
| Insert/delete at end   | O(1)*  | amortised for dynamic arrays |
| Insert/delete at start/middle | O(n) | elements must shift |

*Space:* **O(n)** for `n` elements.
MD,
            'real_world_md' => <<<MD
- **Image pixels:** a photo is a 2D array of color values.
- **Spreadsheets:** rows and columns map naturally to a 2D array.
- **Leaderboards / scores:** a list of player scores you index into.
- **Buffers:** audio/video data streamed into a fixed-size array (buffer).
MD,
            'subtopics' => [
                [
                    'title'   => 'Declaring and Traversing',
                    'body_md' => "Traversing means visiting each element once, typically with a `for` loop from index `0` to `n-1`. This is the basis of almost every array algorithm.",
                ],
            ],
            'code' => [
                ['language' => 'php', 'label' => 'Declare, access, traverse', 'code' => <<<'CODE'
<?php
$nums = [10, 20, 30, 40];
echo $nums[2];          // 30  (O(1) access)
$nums[1] = 99;          // update

foreach ($nums as $i => $value) {
    echo "$i => $value\n";
}
echo count($nums);      // 4
CODE],
                ['language' => 'cpp', 'label' => 'Declare, access, traverse', 'code' => <<<'CODE'
#include <iostream>
using namespace std;

int main() {
    int nums[4] = {10, 20, 30, 40};
    cout << nums[2] << endl;   // 30
    nums[1] = 99;              // update

    for (int i = 0; i < 4; i++)
        cout << i << " => " << nums[i] << endl;
    return 0;
}
CODE],
                ['language' => 'java', 'label' => 'Declare, access, traverse', 'code' => <<<'CODE'
public class Main {
    public static void main(String[] args) {
        int[] nums = {10, 20, 30, 40};
        System.out.println(nums[2]);   // 30
        nums[1] = 99;                  // update

        for (int i = 0; i < nums.length; i++)
            System.out.println(i + " => " + nums[i]);
    }
}
CODE],
                ['language' => 'python', 'label' => 'Declare, access, traverse', 'code' => <<<'CODE'
nums = [10, 20, 30, 40]
print(nums[2])          # 30  (O(1) access)
nums[1] = 99            # update

for i, value in enumerate(nums):
    print(i, "=>", value)
print(len(nums))        # 4
CODE],
            ],
        ],
        [
            'title'   => 'Insertion and Deletion',
            'slug'    => 'arrays-insertion-deletion',
            'summary' => 'Why inserting or deleting in the middle of an array costs O(n).',
            'theory_md' => <<<MD
Inserting or deleting at the **end** of a dynamic array is cheap (amortised O(1)).
But inserting or deleting at the **beginning or middle** forces every element after
that position to **shift**, which is **O(n)**.

```
Insert 99 at index 1 in [10, 20, 30]:
[10, __, 20, 30]   <- shift 20 and 30 right
[10, 99, 20, 30]
```

If you frequently insert/delete in the middle, a **linked list** may be a better fit.
MD,
            'complexity_md' => "- Append at end: **O(1)** amortised\n- Insert/delete at start or middle: **O(n)**",
            'real_world_md' => "- **Undo history** where the newest action is appended at the end.\n- **Playlists** where reordering shifts items.",
            'code' => [
                ['language' => 'php', 'label' => 'Insert and delete', 'code' => <<<'CODE'
<?php
$a = [10, 20, 30];
array_splice($a, 1, 0, [99]); // insert 99 at index 1 -> [10,99,20,30]
array_splice($a, 2, 1);       // delete index 2       -> [10,99,30]
$a[] = 40;                     // append               -> [10,99,30,40]
print_r($a);
CODE],
                ['language' => 'cpp', 'label' => 'Insert and delete (vector)', 'code' => <<<'CODE'
#include <iostream>
#include <vector>
using namespace std;

int main() {
    vector<int> a = {10, 20, 30};
    a.insert(a.begin() + 1, 99); // [10,99,20,30]
    a.erase(a.begin() + 2);      // [10,99,30]
    a.push_back(40);             // [10,99,30,40]
    for (int x : a) cout << x << " ";
}
CODE],
                ['language' => 'java', 'label' => 'Insert and delete (ArrayList)', 'code' => <<<'CODE'
import java.util.*;

public class Main {
    public static void main(String[] args) {
        List<Integer> a = new ArrayList<>(List.of(10, 20, 30));
        a.add(1, 99);   // [10,99,20,30]
        a.remove(2);    // [10,99,30]
        a.add(40);      // [10,99,30,40]
        System.out.println(a);
    }
}
CODE],
                ['language' => 'python', 'label' => 'Insert and delete', 'code' => <<<'CODE'
a = [10, 20, 30]
a.insert(1, 99)   # [10, 99, 20, 30]
del a[2]          # [10, 99, 30]
a.append(40)      # [10, 99, 30, 40]
print(a)
CODE],
            ],
        ],
    ],

    'interview' => [
        [
            'type' => 'conceptual', 'difficulty' => 'easy',
            'question' => 'Why is array access by index O(1)?',
            'answer_md' => "Because elements are stored **contiguously**, the address of element `i` is computed directly as `base + i * element_size`. No traversal is needed, so access is constant time.",
            'companies' => ['Amazon', 'Microsoft'],
        ],
        [
            'type' => 'coding', 'difficulty' => 'easy',
            'question' => 'Find the maximum element in an array.',
            'answer_md' => "Track a running maximum while scanning once — **O(n)** time, **O(1)** space.\n\n```python\nbest = arr[0]\nfor x in arr[1:]:\n    best = max(best, x)\n```",
            'companies' => ['Google'],
        ],
        [
            'type' => 'coding', 'difficulty' => 'medium',
            'question' => 'Move all zeros in an array to the end while keeping the order of non-zero elements.',
            'answer_md' => "Use a **two-pointer** write index: copy each non-zero forward, then fill the rest with zeros. O(n) time, O(1) space.",
            'companies' => ['Meta', 'Bloomberg'],
        ],
    ],

    'problems' => [
        [
            'title' => 'Two Sum', 'slug' => 'arrays-two-sum', 'difficulty' => 'easy',
            'statement_md' => "Given an array of integers `nums` and an integer `target`, return the **indices** of the two numbers that add up to `target`. Exactly one solution exists and you may not use the same element twice.",
            'examples_md' => "```\nInput:  nums = [2, 7, 11, 15], target = 9\nOutput: [0, 1]   (because 2 + 7 = 9)\n```",
            'constraints_md' => "- 2 ≤ nums.length ≤ 10^4\n- Only one valid answer exists.",
            'solutions' => [
                'php' => ['code' => <<<'CODE'
<?php
function twoSum(array $nums, int $target): array {
    $seen = [];
    foreach ($nums as $i => $n) {
        $need = $target - $n;
        if (isset($seen[$need])) return [$seen[$need], $i];
        $seen[$n] = $i;
    }
    return [];
}
CODE, 'explanation_md' => 'Hash map of value → index lets us check the complement in O(1). One pass, **O(n)** time.'],
                'python' => ['code' => <<<'CODE'
def two_sum(nums, target):
    seen = {}
    for i, n in enumerate(nums):
        if target - n in seen:
            return [seen[target - n], i]
        seen[n] = i
    return []
CODE, 'explanation_md' => 'Store each value as you go; return as soon as the complement is found.'],
                'cpp' => ['code' => <<<'CODE'
#include <vector>
#include <unordered_map>
using namespace std;

vector<int> twoSum(vector<int>& nums, int target) {
    unordered_map<int,int> seen;
    for (int i = 0; i < nums.size(); i++) {
        int need = target - nums[i];
        if (seen.count(need)) return {seen[need], i};
        seen[nums[i]] = i;
    }
    return {};
}
CODE, 'explanation_md' => 'unordered_map gives average O(1) lookups.'],
                'java' => ['code' => <<<'CODE'
import java.util.*;

int[] twoSum(int[] nums, int target) {
    Map<Integer,Integer> seen = new HashMap<>();
    for (int i = 0; i < nums.length; i++) {
        int need = target - nums[i];
        if (seen.containsKey(need)) return new int[]{seen.get(need), i};
        seen.put(nums[i], i);
    }
    return new int[0];
}
CODE, 'explanation_md' => 'HashMap lookup is average O(1); total O(n).'],
            ],
        ],
    ],

    'quiz' => [
        'title' => 'Arrays Quiz',
        'questions' => [
            [
                'question' => 'What is the time complexity of accessing an array element by its index?',
                'explanation_md' => 'The address is computed directly, so access is constant time.',
                'options' => [
                    ['text' => 'O(1)', 'correct' => true],
                    ['text' => 'O(n)', 'correct' => false],
                    ['text' => 'O(log n)', 'correct' => false],
                    ['text' => 'O(n²)', 'correct' => false],
                ],
            ],
            [
                'question' => 'Inserting an element at the beginning of an array of size n takes:',
                'explanation_md' => 'All n existing elements must shift one position to the right.',
                'options' => [
                    ['text' => 'O(n)', 'correct' => true],
                    ['text' => 'O(1)', 'correct' => false],
                    ['text' => 'O(log n)', 'correct' => false],
                    ['text' => 'O(n log n)', 'correct' => false],
                ],
            ],
            [
                'question' => 'Arrays store their elements in:',
                'options' => [
                    ['text' => 'Contiguous memory locations', 'correct' => true],
                    ['text' => 'Random scattered locations linked by pointers', 'correct' => false],
                    ['text' => 'A binary tree structure', 'correct' => false],
                    ['text' => 'A hash table', 'correct' => false],
                ],
            ],
        ],
    ],
];
