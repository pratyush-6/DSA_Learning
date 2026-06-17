<?php
return [
    'title'       => 'Queues',
    'slug'        => 'queues',
    'level'       => 'beginner',
    'icon'        => 'bi-list-ol',
    'sort_order'  => 7,
    'description' => 'First-In-First-Out (FIFO) structure used in scheduling, buffering, and BFS.',

    'topics' => [
        [
            'title'   => 'The Queue (FIFO)',
            'slug'    => 'queue-basics',
            'summary' => 'Enqueue at the back, dequeue from the front.',
            'theory_md' => <<<MD
A **queue** follows **FIFO** — *First In, First Out*, like people waiting in line.

Core operations (all **O(1)** with the right backing structure):
- **enqueue(x)** — add to the back
- **dequeue()** — remove from the front
- **front()** — peek at the front

> Important: do **not** implement a queue with `array_shift` / removing from the front
> of a plain array, because that is **O(n)**. Use a **deque** (double-ended queue) or a
> linked list with head & tail pointers for O(1) operations.

A **circular queue** reuses a fixed-size array by wrapping indices around with modulo.
MD,
            'complexity_md' => "enqueue / dequeue / front: **O(1)** (with a deque or linked list). Space: O(n).",
            'real_world_md' => <<<MD
- **CPU / printer job scheduling.**
- **Message queues** (RabbitMQ, Kafka) between services.
- **BFS** (breadth-first search) on graphs and trees.
- **Buffers** for streaming data and keyboard input.
MD,
            'code' => [
                ['language' => 'python', 'label' => 'collections.deque', 'code' => <<<'CODE'
from collections import deque
q = deque()
q.append(10)        # enqueue
q.append(20)
print(q[0])         # front -> 10
print(q.popleft())  # dequeue -> 10
CODE],
                ['language' => 'php', 'label' => 'SplQueue (O(1))', 'code' => <<<'CODE'
<?php
$q = new SplQueue();
$q->enqueue(10);
$q->enqueue(20);
echo $q->bottom();   // front -> 10
echo $q->dequeue();  // 10
var_dump($q->isEmpty());
CODE],
                ['language' => 'java', 'label' => 'ArrayDeque as queue', 'code' => <<<'CODE'
import java.util.*;
Queue<Integer> q = new ArrayDeque<>();
q.offer(10);         // enqueue
q.offer(20);
System.out.println(q.peek());  // front -> 10
System.out.println(q.poll());  // dequeue -> 10
CODE],
                ['language' => 'cpp', 'label' => 'std::queue', 'code' => <<<'CODE'
#include <queue>
using namespace std;
queue<int> q;
q.push(10);          // enqueue
q.push(20);
cout << q.front();   // 10
q.pop();             // dequeue
cout << q.empty();
CODE],
            ],
        ],
        [
            'title'   => 'Deques & Variations',
            'slug'    => 'deque-variations',
            'summary' => 'Double-ended queues, circular queues, and priority queues.',
            'theory_md' => <<<MD
- **Deque (double-ended queue):** insert/remove at **both** ends in O(1). Powers the
  **sliding-window maximum** algorithm.
- **Circular queue:** a fixed array whose front/rear indices wrap with modulo — no
  shifting, constant memory.
- **Priority queue:** elements come out by **priority**, not arrival order (covered in
  the Heaps chapter).
MD,
            'complexity_md' => "Deque operations at both ends: **O(1)**. Circular queue enqueue/dequeue: O(1).",
            'real_world_md' => "Deques back **undo histories with bounded size**, **sliding-window analytics**, and **work-stealing schedulers**.",
            'code' => [
                ['language' => 'python', 'label' => 'Deque both ends', 'code' => <<<'CODE'
from collections import deque
d = deque()
d.append(1)       # back
d.appendleft(0)   # front
d.pop()           # remove back
d.popleft()       # remove front
CODE],
                ['language' => 'php', 'label' => 'SplDoublyLinkedList', 'code' => <<<'CODE'
<?php
$d = new SplDoublyLinkedList();
$d->push(1);       // back
$d->unshift(0);    // front
$d->pop();         // remove back
$d->shift();       // remove front
CODE],
                ['language' => 'java', 'label' => 'ArrayDeque both ends', 'code' => <<<'CODE'
Deque<Integer> d = new ArrayDeque<>();
d.addLast(1);    // back
d.addFirst(0);   // front
d.pollLast();
d.pollFirst();
CODE],
                ['language' => 'cpp', 'label' => 'std::deque', 'code' => <<<'CODE'
#include <deque>
deque<int> d;
d.push_back(1);
d.push_front(0);
d.pop_back();
d.pop_front();
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What is the difference between a stack and a queue?',
         'answer_md' => 'A **stack** is LIFO (last in, first out); a **queue** is FIFO (first in, first out). Stacks suit backtracking/undo; queues suit scheduling/BFS.',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Implement a queue using two stacks.',
         'answer_md' => 'Use an `in` stack for enqueues and an `out` stack for dequeues. When `out` is empty, pour all of `in` into `out` (reversing order). Each element is moved at most once → **amortized O(1)**.',
         'companies' => ['Google', 'Meta']],
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'Why is BFS implemented with a queue?',
         'answer_md' => 'BFS explores nodes level by level. A FIFO queue ensures nodes discovered earlier (closer to the source) are processed first, guaranteeing shortest paths in unweighted graphs.',
         'companies' => ['Amazon']],
    ],

    'problems' => [
        ['title' => 'Implement Queue using Stacks', 'slug' => 'queues-using-stacks', 'difficulty' => 'easy',
         'statement_md' => "Implement a FIFO queue using only two stacks, supporting `push`, `pop`, `peek`, and `empty`.",
         'examples_md' => "```\npush(1); push(2); peek() -> 1; pop() -> 1; empty() -> false\n```",
         'solutions' => [
            'python' => ['code' => "class MyQueue:\n    def __init__(self):\n        self.inb, self.outb = [], []\n    def push(self, x):\n        self.inb.append(x)\n    def _move(self):\n        if not self.outb:\n            while self.inb:\n                self.outb.append(self.inb.pop())\n    def pop(self):\n        self._move(); return self.outb.pop()\n    def peek(self):\n        self._move(); return self.outb[-1]\n    def empty(self):\n        return not self.inb and not self.outb", 'explanation_md' => 'Transfer happens only when `out` is empty → amortized O(1) per operation.'],
            'php' => ['code' => "<?php\nclass MyQueue {\n    private array \$in = [], \$out = [];\n    function push(\$x){ \$this->in[] = \$x; }\n    private function move(){ if(!\$this->out) while(\$this->in) \$this->out[] = array_pop(\$this->in); }\n    function pop(){ \$this->move(); return array_pop(\$this->out); }\n    function peek(){ \$this->move(); return end(\$this->out); }\n    function empty(): bool { return !\$this->in && !\$this->out; }\n}", 'explanation_md' => ''],
            'java' => ['code' => "class MyQueue {\n    Deque<Integer> in = new ArrayDeque<>(), out = new ArrayDeque<>();\n    void push(int x){ in.push(x); }\n    void move(){ if(out.isEmpty()) while(!in.isEmpty()) out.push(in.pop()); }\n    int pop(){ move(); return out.pop(); }\n    int peek(){ move(); return out.peek(); }\n    boolean empty(){ return in.isEmpty() && out.isEmpty(); }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "class MyQueue {\n    stack<int> in, out;\n    void move(){ if(out.empty()) while(!in.empty()){ out.push(in.top()); in.pop(); } }\npublic:\n    void push(int x){ in.push(x); }\n    int pop(){ move(); int v=out.top(); out.pop(); return v; }\n    int peek(){ move(); return out.top(); }\n    bool empty(){ return in.empty() && out.empty(); }\n};", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Queues Quiz',
        'questions' => [
            ['question' => 'A queue follows which principle?',
             'options' => [['text' => 'FIFO', 'correct' => true], ['text' => 'LIFO', 'correct' => false], ['text' => 'Random', 'correct' => false], ['text' => 'Sorted', 'correct' => false]]],
            ['question' => 'Why avoid array_shift / removing from the front of a plain array for a queue?',
             'options' => [['text' => 'It is O(n) because elements shift', 'correct' => true], ['text' => 'It is not allowed by the language', 'correct' => false], ['text' => 'It corrupts memory', 'correct' => false], ['text' => 'It is always O(1)', 'correct' => false]]],
            ['question' => 'Which traversal uses a queue?',
             'options' => [['text' => 'Breadth-First Search (BFS)', 'correct' => true], ['text' => 'Depth-First Search (DFS)', 'correct' => false], ['text' => 'Binary search', 'correct' => false], ['text' => 'Insertion sort', 'correct' => false]]],
        ],
    ],
];
