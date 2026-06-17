<?php
return [
    'title'       => 'Priority Queues',
    'slug'        => 'priority-queues',
    'level'       => 'intermediate',
    'icon'        => 'bi-sort-down',
    'sort_order'  => 13,
    'description' => 'Queues where elements are served by priority rather than arrival order.',

    'topics' => [
        [
            'title'   => 'Priority Queue Concepts',
            'slug'    => 'priority-queue-concepts',
            'summary' => 'An abstract data type usually implemented with a heap.',
            'theory_md' => <<<MD
A **priority queue (PQ)** is an abstract data type where each element has a **priority**,
and **dequeue removes the highest-priority element** (not the oldest, as in a plain
queue).

It is almost always implemented with a **binary heap**, giving:
- **insert with priority:** O(log n)
- **extract highest priority:** O(log n)
- **peek:** O(1)

You can store `(priority, item)` pairs. For a "min priority queue", smaller numbers mean
higher priority.

> Plain queue = order by **time of arrival**. Priority queue = order by **priority**.
MD,
            'complexity_md' => "insert / extract: **O(log n)**, peek: **O(1)**, build: O(n). Space O(n).",
            'real_world_md' => <<<MD
- **OS task scheduling** (run highest-priority process).
- **Dijkstra / Prim / A\*** pathfinding.
- **Huffman coding** (data compression).
- **Event simulation** (process the next-soonest event).
- **Bandwidth / packet QoS** management.
MD,
            'code' => [
                ['language' => 'python', 'label' => '(priority, item) tuples', 'code' => <<<'CODE'
import heapq
pq = []
heapq.heappush(pq, (2, "email"))     # priority 2
heapq.heappush(pq, (1, "alarm"))     # priority 1 (higher)
heapq.heappush(pq, (3, "cleanup"))
print(heapq.heappop(pq))  # (1, 'alarm') -> served first
CODE],
                ['language' => 'php', 'label' => 'SplPriorityQueue', 'code' => <<<'CODE'
<?php
$pq = new SplPriorityQueue();
$pq->insert("email", 2);     // value, priority (higher = first)
$pq->insert("alarm", 5);
$pq->insert("cleanup", 1);
echo $pq->extract();         // "alarm" (highest priority)
CODE],
                ['language' => 'java', 'label' => 'PriorityQueue with comparator', 'code' => <<<'CODE'
import java.util.*;
record Task(String name, int prio) {}
PriorityQueue<Task> pq =
    new PriorityQueue<>(Comparator.comparingInt(Task::prio)); // min-prio first
pq.offer(new Task("email", 2));
pq.offer(new Task("alarm", 1));
System.out.println(pq.poll().name()); // alarm
CODE],
                ['language' => 'cpp', 'label' => 'priority_queue of pairs', 'code' => <<<'CODE'
#include <queue>
using namespace std;
// min priority first using greater<>
priority_queue<pair<int,string>, vector<pair<int,string>>, greater<>> pq;
pq.push({2, "email"});
pq.push({1, "alarm"});
cout << pq.top().second;   // alarm
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'How does a priority queue differ from a normal queue?',
         'answer_md' => 'A normal queue is FIFO (order by arrival). A priority queue serves elements by **priority** — the highest (or lowest) priority element comes out first regardless of insertion order.',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'What data structure typically backs a priority queue and why?',
         'answer_md' => 'A **binary heap**, because it provides O(log n) insert and extract-min/max and O(1) peek with simple array storage — a great balance for priority operations.',
         'companies' => ['Google']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Merge k sorted lists.',
         'answer_md' => 'Put the head of each list into a **min priority queue**; repeatedly extract the smallest and push its next node. O(N log k) where N is total nodes, k is number of lists.',
         'companies' => ['Amazon', 'Meta', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Last Stone Weight', 'slug' => 'pq-last-stone-weight', 'difficulty' => 'easy',
         'statement_md' => "Each turn, smash the two heaviest stones together. If equal, both are destroyed; otherwise the lighter is destroyed and the heavier becomes the difference. Return the weight of the last remaining stone (or 0).",
         'examples_md' => "```\n[2,7,4,1,8,1] -> 1\n```",
         'solutions' => [
            'python' => ['code' => "import heapq\ndef last_stone_weight(stones):\n    h = [-s for s in stones]   # max-heap via negation\n    heapq.heapify(h)\n    while len(h) > 1:\n        a = -heapq.heappop(h)\n        b = -heapq.heappop(h)\n        if a != b:\n            heapq.heappush(h, -(a - b))\n    return -h[0] if h else 0", 'explanation_md' => 'A max-heap always gives the two heaviest in O(log n). Total O(n log n).'],
            'php' => ['code' => "<?php\nfunction lastStoneWeight(array \$stones): int {\n    \$h = new SplMaxHeap();\n    foreach (\$stones as \$s) \$h->insert(\$s);\n    while (count(\$h) > 1) {\n        \$a = \$h->extract(); \$b = \$h->extract();\n        if (\$a !== \$b) \$h->insert(\$a - \$b);\n    }\n    return count(\$h) ? \$h->top() : 0;\n}", 'explanation_md' => ''],
            'java' => ['code' => "int lastStoneWeight(int[] stones){\n    PriorityQueue<Integer> pq = new PriorityQueue<>(Collections.reverseOrder());\n    for(int s : stones) pq.offer(s);\n    while(pq.size() > 1){\n        int a = pq.poll(), b = pq.poll();\n        if(a != b) pq.offer(a - b);\n    }\n    return pq.isEmpty() ? 0 : pq.peek();\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "int lastStoneWeight(vector<int>& stones){\n    priority_queue<int> pq(stones.begin(), stones.end());\n    while(pq.size() > 1){\n        int a = pq.top(); pq.pop();\n        int b = pq.top(); pq.pop();\n        if(a != b) pq.push(a - b);\n    }\n    return pq.empty() ? 0 : pq.top();\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Priority Queues Quiz',
        'questions' => [
            ['question' => 'A priority queue serves elements by:',
             'options' => [['text' => 'Priority', 'correct' => true], ['text' => 'Arrival time only', 'correct' => false], ['text' => 'Random order', 'correct' => false], ['text' => 'Alphabetical order always', 'correct' => false]]],
            ['question' => 'Priority queues are usually implemented with a:',
             'options' => [['text' => 'Binary heap', 'correct' => true], ['text' => 'Hash table', 'correct' => false], ['text' => 'Stack', 'correct' => false], ['text' => 'Plain array sorted on every insert', 'correct' => false]]],
            ['question' => 'Which algorithm relies on a priority queue?',
             'options' => [['text' => "Dijkstra's shortest path", 'correct' => true], ['text' => 'Bubble sort', 'correct' => false], ['text' => 'Linear search', 'correct' => false], ['text' => 'Two-pointer reversal', 'correct' => false]]],
        ],
    ],
];
