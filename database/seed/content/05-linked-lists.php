<?php
return [
    'title'       => 'Linked Lists',
    'slug'        => 'linked-lists',
    'level'       => 'beginner',
    'icon'        => 'bi-link-45deg',
    'sort_order'  => 5,
    'description' => 'Nodes connected by pointers — flexible insertion/deletion without shifting.',

    'topics' => [
        [
            'title'   => 'Singly Linked Lists',
            'slug'    => 'singly-linked-list',
            'summary' => 'A chain of nodes, each pointing to the next.',
            'theory_md' => <<<MD
A **linked list** is a sequence of **nodes**. Each node holds a **value** and a
**pointer/reference** to the next node. The list is accessed via a **head** pointer;
the last node points to `null`.

```
head -> [10|*] -> [20|*] -> [30|null]
```

Unlike arrays, nodes are **not** contiguous in memory, so there is **no O(1) random
access** — to reach the i-th node you must walk from the head (O(n)). But inserting or
deleting a node (given its position) is **O(1)** because you just relink pointers; no
shifting required.
MD,
            'complexity_md' => "| Operation | Linked List | Array |\n|---|---|---|\n| Access i-th | O(n) | O(1) |\n| Insert/delete at head | O(1) | O(n) |\n| Insert/delete at tail | O(n)* | O(1) |\n| Search | O(n) | O(n) |\n\n*O(1) if you keep a tail pointer. Space: O(n) plus pointer overhead per node.",
            'real_world_md' => "- **Music/playlist** next-song navigation.\n- **Browser-like** structures and **LRU caches** (doubly linked list + hash map).\n- **Undo stacks** and **adjacency lists** for graphs.",
            'code' => [
                ['language' => 'python', 'label' => 'Node + traversal + insert at head', 'code' => <<<'CODE'
class Node:
    def __init__(self, val, nxt=None):
        self.val = val
        self.next = nxt

def push_front(head, val):      # O(1)
    return Node(val, head)

def traverse(head):             # O(n)
    cur = head
    while cur:
        print(cur.val)
        cur = cur.next
CODE],
                ['language' => 'php', 'label' => 'Node + traversal', 'code' => <<<'CODE'
<?php
class Node {
    public ?Node $next = null;
    public function __construct(public int $val) {}
}

function pushFront(?Node $head, int $val): Node {  // O(1)
    $n = new Node($val);
    $n->next = $head;
    return $n;
}

function traverse(?Node $head): void {             // O(n)
    for ($cur = $head; $cur; $cur = $cur->next) echo $cur->val, "\n";
}
CODE],
                ['language' => 'java', 'label' => 'Node + insert at head', 'code' => <<<'CODE'
class Node {
    int val; Node next;
    Node(int v) { val = v; }
}

Node pushFront(Node head, int val) {  // O(1)
    Node n = new Node(val);
    n.next = head;
    return n;
}
CODE],
                ['language' => 'cpp', 'label' => 'Node + insert at head', 'code' => <<<'CODE'
struct Node {
    int val;
    Node* next;
    Node(int v): val(v), next(nullptr) {}
};

Node* pushFront(Node* head, int val) {  // O(1)
    Node* n = new Node(val);
    n->next = head;
    return n;
}
CODE],
            ],
        ],
        [
            'title'   => 'Reversing a Linked List',
            'slug'    => 'reverse-linked-list',
            'summary' => 'The most-asked linked list interview question.',
            'theory_md' => <<<MD
To reverse a singly linked list, walk the list and flip each `next` pointer to point
**backwards**. Keep three pointers: `prev`, `cur`, and `next`.

```
prev=null  cur=head
while cur:
    next = cur.next   # save
    cur.next = prev   # flip
    prev = cur        # advance
    cur = next
return prev            # new head
```

This is **O(n)** time and **O(1)** space — a must-know pattern.
MD,
            'complexity_md' => "Iterative reversal: **O(n)** time, **O(1)** space. Recursive reversal is O(n) time but O(n) stack space.",
            'real_world_md' => "Pointer relinking is the core skill behind **doubly linked lists**, **LRU caches**, and **in-place list manipulation**.",
            'code' => [
                ['language' => 'python', 'label' => 'Iterative reverse', 'code' => <<<'CODE'
def reverse(head):
    prev = None
    cur = head
    while cur:
        nxt = cur.next
        cur.next = prev
        prev = cur
        cur = nxt
    return prev
CODE],
                ['language' => 'php', 'label' => 'Iterative reverse', 'code' => <<<'CODE'
<?php
function reverse(?Node $head): ?Node {
    $prev = null;
    $cur = $head;
    while ($cur) {
        $nxt = $cur->next;
        $cur->next = $prev;
        $prev = $cur;
        $cur = $nxt;
    }
    return $prev;
}
CODE],
                ['language' => 'java', 'label' => 'Iterative reverse', 'code' => <<<'CODE'
Node reverse(Node head) {
    Node prev = null, cur = head;
    while (cur != null) {
        Node nxt = cur.next;
        cur.next = prev;
        prev = cur;
        cur = nxt;
    }
    return prev;
}
CODE],
                ['language' => 'cpp', 'label' => 'Iterative reverse', 'code' => <<<'CODE'
Node* reverse(Node* head){
    Node* prev = nullptr;
    Node* cur = head;
    while(cur){
        Node* nxt = cur->next;
        cur->next = prev;
        prev = cur;
        cur = nxt;
    }
    return prev;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'When would you prefer a linked list over an array?',
         'answer_md' => 'When you do many **insertions/deletions at the front or middle** and do not need random access. Linked lists avoid the O(n) element shifting that arrays require, at the cost of O(n) indexing and extra pointer memory.',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'easy',
         'question' => 'Detect whether a linked list has a cycle.',
         'answer_md' => 'Use **Floyd’s slow/fast pointers**: advance one by 1 and the other by 2. If they meet, there is a cycle. O(n) time, O(1) space.',
         'companies' => ['Google', 'Meta', 'Bloomberg']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Find the middle node of a linked list in one pass.',
         'answer_md' => 'Two pointers: move `slow` by 1 and `fast` by 2. When `fast` reaches the end, `slow` is at the middle.',
         'companies' => ['Amazon', 'Adobe']],
    ],

    'problems' => [
        ['title' => 'Detect Cycle in Linked List', 'slug' => 'linkedlist-detect-cycle', 'difficulty' => 'easy',
         'statement_md' => "Given the head of a linked list, return `true` if the list has a cycle.",
         'examples_md' => "```\n1 -> 2 -> 3 -> 4 -> (back to 2)  => true\n1 -> 2 -> 3 -> null              => false\n```",
         'solutions' => [
            'python' => ['code' => "def has_cycle(head):\n    slow = fast = head\n    while fast and fast.next:\n        slow = slow.next\n        fast = fast.next.next\n        if slow is fast:\n            return True\n    return False", 'explanation_md' => 'Floyd’s tortoise & hare: if there is a cycle the fast pointer laps the slow one. O(n) time, O(1) space.'],
            'php' => ['code' => "<?php\nfunction hasCycle(?Node \$head): bool {\n    \$slow = \$fast = \$head;\n    while (\$fast && \$fast->next) {\n        \$slow = \$slow->next;\n        \$fast = \$fast->next->next;\n        if (\$slow === \$fast) return true;\n    }\n    return false;\n}", 'explanation_md' => ''],
            'java' => ['code' => "boolean hasCycle(Node head){\n    Node slow = head, fast = head;\n    while(fast != null && fast.next != null){\n        slow = slow.next;\n        fast = fast.next.next;\n        if(slow == fast) return true;\n    }\n    return false;\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "bool hasCycle(Node* head){\n    Node *slow=head, *fast=head;\n    while(fast && fast->next){\n        slow=slow->next;\n        fast=fast->next->next;\n        if(slow==fast) return true;\n    }\n    return false;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Linked Lists Quiz',
        'questions' => [
            ['question' => 'Accessing the i-th element of a singly linked list is:',
             'options' => [['text' => 'O(n)', 'correct' => true], ['text' => 'O(1)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false], ['text' => 'O(n²)', 'correct' => false]]],
            ['question' => 'Which algorithm detects a cycle in O(1) space?',
             'options' => [['text' => "Floyd's slow/fast pointers", 'correct' => true], ['text' => 'Storing all nodes in a list', 'correct' => false], ['text' => 'Binary search', 'correct' => false], ['text' => 'Merge sort', 'correct' => false]]],
            ['question' => 'Inserting a node at the head of a linked list is:',
             'options' => [['text' => 'O(1)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false]]],
        ],
    ],
];
