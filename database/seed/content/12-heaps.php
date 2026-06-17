<?php
return [
    'title'       => 'Heaps',
    'slug'        => 'heaps',
    'level'       => 'intermediate',
    'icon'        => 'bi-triangle',
    'sort_order'  => 12,
    'description' => 'Complete binary trees that keep the min or max instantly accessible.',

    'topics' => [
        [
            'title'   => 'Binary Heaps',
            'slug'    => 'binary-heaps',
            'summary' => 'A complete tree stored in an array, with the heap property.',
            'theory_md' => <<<MD
A **binary heap** is a **complete binary tree** satisfying the **heap property**:
- **Min-heap:** every parent ≤ its children → the **minimum** is at the root.
- **Max-heap:** every parent ≥ its children → the **maximum** is at the root.

Because the tree is complete, it is stored compactly in an **array**:
- node at index `i` → children at `2i+1` and `2i+2`, parent at `(i-1)/2`.

Operations:
- **peek** (min/max): O(1)
- **push**: add at the end, **bubble up**: O(log n)
- **pop** (extract root): swap with last, remove, **bubble down**: O(log n)
- **build-heap** from an array: O(n)
MD,
            'complexity_md' => "| Operation | Time |\n|---|---|\n| peek min/max | O(1) |\n| push | O(log n) |\n| pop | O(log n) |\n| build heap | O(n) |\n\nSpace: O(n).",
            'real_world_md' => <<<MD
- **Priority queues** (next chapter) — task scheduling by priority.
- **Dijkstra’s** and **Prim’s** algorithms.
- **Heap sort** (O(n log n), in place).
- **Top-K** problems and **median of a stream** (two heaps).
MD,
            'code' => [
                ['language' => 'python', 'label' => 'heapq (min-heap)', 'code' => <<<'CODE'
import heapq
h = []
heapq.heappush(h, 5)
heapq.heappush(h, 1)
heapq.heappush(h, 3)
print(h[0])            # peek min -> 1
print(heapq.heappop(h))# 1
# Max-heap: push negatives
CODE],
                ['language' => 'php', 'label' => 'SplMinHeap', 'code' => <<<'CODE'
<?php
$h = new SplMinHeap();
$h->insert(5);
$h->insert(1);
$h->insert(3);
echo $h->top();        // peek min -> 1
echo $h->extract();    // 1
// SplMaxHeap for max-heap
CODE],
                ['language' => 'java', 'label' => 'PriorityQueue', 'code' => <<<'CODE'
import java.util.*;
PriorityQueue<Integer> pq = new PriorityQueue<>(); // min-heap
pq.offer(5); pq.offer(1); pq.offer(3);
System.out.println(pq.peek());  // 1
System.out.println(pq.poll());  // 1
// max-heap: new PriorityQueue<>(Collections.reverseOrder())
CODE],
                ['language' => 'cpp', 'label' => 'priority_queue', 'code' => <<<'CODE'
#include <queue>
using namespace std;
// max-heap by default
priority_queue<int> maxh;
maxh.push(5); maxh.push(1); maxh.push(3);
cout << maxh.top();  // 5
maxh.pop();
// min-heap:
priority_queue<int, vector<int>, greater<int>> minh;
CODE],
            ],
        ],
        [
            'title'   => 'Top-K with a Heap',
            'slug'    => 'top-k-heap',
            'summary' => 'Keep a heap of size k to find the k largest/smallest efficiently.',
            'theory_md' => <<<MD
To find the **k largest** elements of n items: keep a **min-heap of size k**. Scan the
data; if the heap has fewer than k items, push; otherwise if the current item is larger
than the heap's minimum, pop the min and push the current. At the end the heap holds the
k largest.

This is **O(n log k)** time and **O(k)** space — much better than sorting everything
(O(n log n)) when k ≪ n.
MD,
            'complexity_md' => "Top-K: **O(n log k)** time, **O(k)** space.",
            'real_world_md' => "Powers **'top trending'**, **leaderboards**, and **nearest-neighbor** shortlists over huge streams.",
            'code' => [
                ['language' => 'python', 'label' => 'K largest', 'code' => <<<'CODE'
import heapq
def k_largest(nums, k):
    h = []
    for n in nums:
        heapq.heappush(h, n)
        if len(h) > k:
            heapq.heappop(h)   # drop the smallest
    return sorted(h, reverse=True)
# or simply: heapq.nlargest(k, nums)
CODE],
                ['language' => 'php', 'label' => 'K largest', 'code' => <<<'CODE'
<?php
function kLargest(array $nums, int $k): array {
    $h = new SplMinHeap();
    foreach ($nums as $n) {
        $h->insert($n);
        if (count($h) > $k) $h->extract();
    }
    return iterator_to_array($h);
}
CODE],
                ['language' => 'java', 'label' => 'K largest', 'code' => <<<'CODE'
int[] kLargest(int[] nums, int k){
    PriorityQueue<Integer> pq = new PriorityQueue<>(); // min-heap
    for(int n : nums){
        pq.offer(n);
        if(pq.size() > k) pq.poll();
    }
    int[] res = new int[k];
    for(int i=0;i<k;i++) res[i] = pq.poll();
    return res;
}
CODE],
                ['language' => 'cpp', 'label' => 'K largest', 'code' => <<<'CODE'
vector<int> kLargest(vector<int>& nums, int k){
    priority_queue<int, vector<int>, greater<int>> minh; // min-heap
    for(int n : nums){
        minh.push(n);
        if((int)minh.size() > k) minh.pop();
    }
    vector<int> res;
    while(!minh.empty()){ res.push_back(minh.top()); minh.pop(); }
    return res;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What is the heap property and where is the min/max located?',
         'answer_md' => 'In a min-heap every parent ≤ its children, so the **minimum is at the root** (peek in O(1)). A max-heap is the mirror image. Heaps are complete trees stored in arrays.',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Find the k-th largest element in an array.',
         'answer_md' => 'Maintain a **min-heap of size k**; the root is the k-th largest after processing all elements. O(n log k). (Quickselect gives average O(n).)',
         'companies' => ['Google', 'Amazon', 'Meta']],
        ['type' => 'coding', 'difficulty' => 'hard',
         'question' => 'Find the median of a data stream.',
         'answer_md' => 'Maintain two heaps: a max-heap for the lower half and a min-heap for the upper half, balanced in size. The median is the top(s). Each insert is O(log n).',
         'companies' => ['Google', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Kth Largest Element', 'slug' => 'heaps-kth-largest', 'difficulty' => 'medium',
         'statement_md' => "Given an integer array `nums` and an integer `k`, return the k-th largest element.",
         'examples_md' => "```\nnums = [3,2,1,5,6,4], k = 2 -> 5\n```",
         'solutions' => [
            'python' => ['code' => "import heapq\ndef find_kth_largest(nums, k):\n    h = []\n    for n in nums:\n        heapq.heappush(h, n)\n        if len(h) > k:\n            heapq.heappop(h)\n    return h[0]", 'explanation_md' => 'A size-k min-heap keeps the k largest; its root is the answer. O(n log k).'],
            'php' => ['code' => "<?php\nfunction findKthLargest(array \$nums, int \$k): int {\n    \$h = new SplMinHeap();\n    foreach (\$nums as \$n) {\n        \$h->insert(\$n);\n        if (count(\$h) > \$k) \$h->extract();\n    }\n    return \$h->top();\n}", 'explanation_md' => ''],
            'java' => ['code' => "int findKthLargest(int[] nums, int k){\n    PriorityQueue<Integer> pq = new PriorityQueue<>();\n    for(int n : nums){ pq.offer(n); if(pq.size() > k) pq.poll(); }\n    return pq.peek();\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "int findKthLargest(vector<int>& nums, int k){\n    priority_queue<int, vector<int>, greater<int>> pq;\n    for(int n : nums){ pq.push(n); if((int)pq.size() > k) pq.pop(); }\n    return pq.top();\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Heaps Quiz',
        'questions' => [
            ['question' => 'Peeking the minimum of a min-heap is:',
             'options' => [['text' => 'O(1)', 'correct' => true], ['text' => 'O(log n)', 'correct' => false], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false]]],
            ['question' => 'Pushing and popping from a heap is:',
             'options' => [['text' => 'O(log n)', 'correct' => true], ['text' => 'O(1)', 'correct' => false], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(n²)', 'correct' => false]]],
            ['question' => 'For Top-K largest with k ≪ n, the heap approach costs:',
             'options' => [['text' => 'O(n log k)', 'correct' => true], ['text' => 'O(n log n)', 'correct' => false], ['text' => 'O(n²)', 'correct' => false], ['text' => 'O(k)', 'correct' => false]]],
        ],
    ],
];
