<?php
return [
    'title'       => 'System Design for Coding Interviews',
    'slug'        => 'system-design-for-coding-interviews',
    'level'       => 'advanced',
    'icon'        => 'bi-hdd-network',
    'sort_order'  => 28,
    'description' => 'Bridge DSA and system design: caches, rate limiters, and scalable building blocks.',

    'topics' => [
        [
            'title'   => 'DSA-Backed System Design Building Blocks',
            'slug'    => 'system-design-building-blocks',
            'summary' => 'How data structures power real systems and the design interview.',
            'theory_md' => <<<MD
"System design for coding interviews" sits between pure DSA and large-scale architecture.
Many medium/hard interview problems are really **mini system designs** built from the
data structures you have learned.

**A framework for design questions:**
1. **Clarify requirements** — functional + non-functional (scale, latency, consistency).
2. **Estimate** — reads/writes per second, storage, memory.
3. **Define APIs** and the **data model**.
4. **Pick data structures** — the heart of the design (hash map for O(1) lookup, heap for
   priorities, queue for buffering, trie for prefixes, LRU for caching).
5. **Scale** — caching, sharding, replication, load balancing.
6. **Discuss trade-offs** (CAP theorem, consistency vs availability).

**Classic DSA → component mappings:**
- **Hash map + doubly linked list → LRU cache.**
- **Sliding window / token bucket → rate limiter.**
- **Heap / priority queue → schedulers, top-K, leaderboards.**
- **Trie → autocomplete / typeahead.**
- **Consistent hashing → distributing keys across servers.**
- **Bloom filter → fast "probably-not-present" checks.**
MD,
            'complexity_md' => "Aim for O(1) hot-path operations (cache get/put), bounded memory, and predictable latency. State the complexity of each operation in your design.",
            'real_world_md' => <<<MD
- **LRU cache:** OS page cache, CDN edge caches, database buffer pools.
- **Rate limiter:** API gateways (e.g., 100 requests/min per user).
- **Leaderboard:** games and social apps (top-K with a heap or sorted set).
- **Autocomplete:** search bars backed by a trie.
MD,
            'subtopics' => [
                [
                    'title'   => 'Design an LRU Cache',
                    'body_md' => "An **LRU (Least Recently Used) cache** evicts the least-recently-used item when full. Combine a **hash map** (key → node, O(1) lookup) with a **doubly linked list** ordered by recency (move-to-front on access, evict from the tail). Both `get` and `put` are **O(1)**.",
                ],
            ],
            'code' => [
                ['language' => 'python', 'label' => 'LRU cache (OrderedDict)', 'code' => <<<'CODE'
from collections import OrderedDict
class LRUCache:
    def __init__(self, capacity):
        self.cache = OrderedDict()
        self.cap = capacity
    def get(self, key):
        if key not in self.cache:
            return -1
        self.cache.move_to_end(key)     # mark most-recent
        return self.cache[key]
    def put(self, key, value):
        if key in self.cache:
            self.cache.move_to_end(key)
        self.cache[key] = value
        if len(self.cache) > self.cap:
            self.cache.popitem(last=False)  # evict least-recent
CODE],
                ['language' => 'php', 'label' => 'LRU cache (ordered array)', 'code' => <<<'CODE'
<?php
class LRUCache {
    private array $map = [];   // key => value, insertion order = recency
    public function __construct(private int $cap) {}
    public function get(int $key): int {
        if (!isset($this->map[$key])) return -1;
        $val = $this->map[$key];
        unset($this->map[$key]);          // remove and
        $this->map[$key] = $val;          // re-append (most recent)
        return $val;
    }
    public function put(int $key, int $value): void {
        if (isset($this->map[$key])) unset($this->map[$key]);
        $this->map[$key] = $value;
        if (count($this->map) > $this->cap) {
            array_shift($this->map);      // evict least recent
        }
    }
}
CODE],
                ['language' => 'java', 'label' => 'LRU cache (LinkedHashMap)', 'code' => <<<'CODE'
import java.util.*;
class LRUCache extends LinkedHashMap<Integer,Integer> {
    private final int cap;
    LRUCache(int capacity){ super(capacity, 0.75f, true); cap = capacity; }
    public int get(int key){ return super.getOrDefault(key, -1); }
    public void put(int key, int value){ super.put(key, value); }
    protected boolean removeEldestEntry(Map.Entry<Integer,Integer> e){
        return size() > cap;              // auto-evict least recent
    }
}
CODE],
                ['language' => 'cpp', 'label' => 'LRU cache (list + hash map)', 'code' => <<<'CODE'
#include <list>
#include <unordered_map>
using namespace std;
class LRUCache {
    int cap;
    list<pair<int,int>> dll;                         // front = most recent
    unordered_map<int, list<pair<int,int>>::iterator> mp;
public:
    LRUCache(int capacity): cap(capacity) {}
    int get(int key){
        if(!mp.count(key)) return -1;
        dll.splice(dll.begin(), dll, mp[key]);       // move to front
        return mp[key]->second;
    }
    void put(int key, int value){
        if(mp.count(key)){ mp[key]->second = value; dll.splice(dll.begin(), dll, mp[key]); return; }
        if((int)dll.size() == cap){ mp.erase(dll.back().first); dll.pop_back(); }
        dll.push_front({key, value});
        mp[key] = dll.begin();
    }
};
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'Design an LRU cache with O(1) get and put. What data structures do you use?',
         'answer_md' => 'A **hash map** (key → node) for O(1) lookup plus a **doubly linked list** ordered by recency. On access, move the node to the front; when full, evict the tail. Both operations are O(1).',
         'companies' => ['Amazon', 'Google', 'Microsoft', 'Meta']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'How would you design a rate limiter that allows N requests per minute per user?',
         'answer_md' => 'Use a **token bucket** or **sliding window** per user (key in a hash map). Token bucket refills tokens at a steady rate; each request consumes one and is rejected if none remain. For distributed systems, store counters in Redis with TTLs.',
         'companies' => ['Amazon', 'Uber', 'Atlassian']],
        ['type' => 'conceptual', 'difficulty' => 'hard',
         'question' => 'How do you build a real-time leaderboard for millions of players?',
         'answer_md' => 'Use a **sorted set** (e.g., Redis ZSET, backed by a skip list/heap) for O(log n) rank updates and range queries. For top-K snapshots, a **heap** suffices. Shard by region and cache hot pages.',
         'companies' => ['Google', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'LRU Cache', 'slug' => 'sd-lru-cache', 'difficulty' => 'medium',
         'statement_md' => "Design a data structure for an LRU cache supporting `get(key)` and `put(key, value)` in **O(1)** average time. When capacity is exceeded, evict the least recently used key.",
         'examples_md' => "```\nLRUCache cap=2\nput(1,1); put(2,2); get(1)->1; put(3,3) // evicts 2; get(2)->-1\n```",
         'constraints_md' => "- 1 ≤ capacity ≤ 3000\n- get/put called up to 10^5 times",
         'solutions' => [
            'python' => ['code' => "from collections import OrderedDict\nclass LRUCache:\n    def __init__(self, capacity):\n        self.cache = OrderedDict(); self.cap = capacity\n    def get(self, key):\n        if key not in self.cache: return -1\n        self.cache.move_to_end(key)\n        return self.cache[key]\n    def put(self, key, value):\n        if key in self.cache: self.cache.move_to_end(key)\n        self.cache[key] = value\n        if len(self.cache) > self.cap:\n            self.cache.popitem(last=False)", 'explanation_md' => 'OrderedDict keeps recency order; move_to_end on access, popitem(last=False) evicts the oldest. O(1).'],
            'php' => ['code' => "<?php\nclass LRUCache {\n    private array \$map = [];\n    function __construct(private int \$cap) {}\n    function get(int \$key): int {\n        if (!isset(\$this->map[\$key])) return -1;\n        \$v = \$this->map[\$key]; unset(\$this->map[\$key]); \$this->map[\$key] = \$v;\n        return \$v;\n    }\n    function put(int \$key, int \$value): void {\n        if (isset(\$this->map[\$key])) unset(\$this->map[\$key]);\n        \$this->map[\$key] = \$value;\n        if (count(\$this->map) > \$this->cap) array_shift(\$this->map);\n    }\n}", 'explanation_md' => 'PHP arrays preserve insertion order; re-inserting marks recency, array_shift evicts the oldest.'],
            'java' => ['code' => "class LRUCache extends LinkedHashMap<Integer,Integer> {\n    private final int cap;\n    LRUCache(int capacity){ super(capacity, 0.75f, true); cap = capacity; }\n    public int get(int key){ return super.getOrDefault(key, -1); }\n    public void put(int key, int value){ super.put(key, value); }\n    protected boolean removeEldestEntry(Map.Entry<Integer,Integer> e){ return size() > cap; }\n}", 'explanation_md' => 'LinkedHashMap in access-order mode handles recency; removeEldestEntry auto-evicts.'],
            'cpp' => ['code' => "class LRUCache {\n    int cap;\n    list<pair<int,int>> dll;\n    unordered_map<int, list<pair<int,int>>::iterator> mp;\npublic:\n    LRUCache(int capacity): cap(capacity) {}\n    int get(int key){\n        if(!mp.count(key)) return -1;\n        dll.splice(dll.begin(), dll, mp[key]);\n        return mp[key]->second;\n    }\n    void put(int key, int value){\n        if(mp.count(key)){ mp[key]->second = value; dll.splice(dll.begin(), dll, mp[key]); return; }\n        if((int)dll.size() == cap){ mp.erase(dll.back().first); dll.pop_back(); }\n        dll.push_front({key, value}); mp[key] = dll.begin();\n    }\n};", 'explanation_md' => 'A doubly linked list (recency) + hash map (iterator lookup) gives O(1) get/put.'],
         ]],
    ],

    'quiz' => [
        'title' => 'System Design Quiz',
        'questions' => [
            ['question' => 'An LRU cache is typically built from:',
             'options' => [['text' => 'A hash map + doubly linked list', 'correct' => true], ['text' => 'A single array', 'correct' => false], ['text' => 'A binary search tree only', 'correct' => false], ['text' => 'A stack', 'correct' => false]]],
            ['question' => 'Which structure powers autocomplete/typeahead?',
             'options' => [['text' => 'Trie', 'correct' => true], ['text' => 'Heap', 'correct' => false], ['text' => 'Stack', 'correct' => false], ['text' => 'Union-Find', 'correct' => false]]],
            ['question' => 'A good first step in a system design interview is to:',
             'options' => [['text' => 'Clarify functional & non-functional requirements', 'correct' => true], ['text' => 'Immediately write code', 'correct' => false], ['text' => 'Choose a database vendor', 'correct' => false], ['text' => 'Optimize before designing', 'correct' => false]]],
            ['question' => 'A rate limiter allowing N requests/min is commonly implemented with:',
             'options' => [['text' => 'Token bucket / sliding window', 'correct' => true], ['text' => 'Merge sort', 'correct' => false], ['text' => 'Binary search', 'correct' => false], ['text' => 'DFS', 'correct' => false]]],
        ],
    ],
];
