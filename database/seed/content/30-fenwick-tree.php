<?php
return [
    'title'       => 'Fenwick Tree (Binary Indexed Tree)',
    'slug'        => 'fenwick-tree',
    'level'       => 'advanced',
    'icon'        => 'bi-diagram-2-fill',
    'sort_order'  => 31,
    'description' => 'A compact structure for prefix sums with point updates in O(log n).',

    'topics' => [
        [
            'title'   => 'Binary Indexed Tree (BIT)',
            'slug'    => 'bit-basics',
            'summary' => 'Prefix sums and point updates in O(log n) with tiny code.',
            'theory_md' => <<<MD
A **Fenwick Tree** (Binary Indexed Tree, BIT) maintains **prefix sums** of an array
while allowing **point updates**, both in **O(log n)** — with far less code than a
segment tree.

The trick uses the **lowest set bit**, `i & (-i)`:
- **update(i, delta):** add `delta` at index i, then move `i += i & (-i)` to cover all
  ranges that include i.
- **query(i):** sum of `[1..i]`; accumulate while moving `i -= i & (-i)`.
- A **range sum** `[l, r]` = `query(r) - query(l - 1)`.

BIT is **1-indexed**. It is the go-to structure for **inversion counts**, **order
statistics**, and any "running prefix aggregate with updates" problem.

> Segment tree is more general (min/max/range-update); Fenwick is smaller and faster
> for **sum/prefix** use cases.
MD,
            'complexity_md' => "build O(n log n) (or O(n) with a trick); update O(log n); prefix/range query O(log n). Space O(n).",
            'real_world_md' => "- **Counting inversions** while sorting / ranking.\n- **Frequency tables** with live updates (leaderboards, analytics).\n- **2D BIT** for grid range-sum dashboards.",
            'code' => [
                ['language' => 'python', 'label' => 'Fenwick tree', 'code' => <<<'CODE'
class BIT:
    def __init__(self, n):
        self.n = n
        self.tree = [0] * (n + 1)   # 1-indexed
    def update(self, i, delta):
        while i <= self.n:
            self.tree[i] += delta
            i += i & (-i)
    def query(self, i):             # prefix sum [1..i]
        s = 0
        while i > 0:
            s += self.tree[i]
            i -= i & (-i)
        return s
    def range_sum(self, l, r):
        return self.query(r) - self.query(l - 1)
CODE],
                ['language' => 'php', 'label' => 'Fenwick tree', 'code' => <<<'CODE'
<?php
class BIT {
    private array $tree;
    public function __construct(private int $n) {
        $this->tree = array_fill(0, $n + 1, 0);
    }
    public function update(int $i, int $delta): void {
        for (; $i <= $this->n; $i += $i & (-$i)) $this->tree[$i] += $delta;
    }
    public function query(int $i): int {
        $s = 0;
        for (; $i > 0; $i -= $i & (-$i)) $s += $this->tree[$i];
        return $s;
    }
    public function rangeSum(int $l, int $r): int {
        return $this->query($r) - $this->query($l - 1);
    }
}
CODE],
                ['language' => 'java', 'label' => 'Fenwick tree', 'code' => <<<'CODE'
class BIT {
    int n; long[] tree;
    BIT(int n){ this.n = n; tree = new long[n + 1]; }
    void update(int i, long delta){
        for(; i <= n; i += i & (-i)) tree[i] += delta;
    }
    long query(int i){
        long s = 0;
        for(; i > 0; i -= i & (-i)) s += tree[i];
        return s;
    }
    long rangeSum(int l, int r){ return query(r) - query(l - 1); }
}
CODE],
                ['language' => 'cpp', 'label' => 'Fenwick tree', 'code' => <<<'CODE'
struct BIT {
    int n; vector<long long> tree;
    BIT(int n): n(n), tree(n + 1, 0) {}
    void update(int i, long long delta){
        for(; i <= n; i += i & (-i)) tree[i] += delta;
    }
    long long query(int i){
        long long s = 0;
        for(; i > 0; i -= i & (-i)) s += tree[i];
        return s;
    }
    long long rangeSum(int l, int r){ return query(r) - query(l - 1); }
};
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'When would you choose a Fenwick tree over a segment tree?',
         'answer_md' => 'For **prefix-sum / cumulative-frequency** problems with point updates: a Fenwick tree is smaller, faster, and easier to code (O(log n) update/query). A segment tree is preferred when you need min/max, range updates, or non-invertible operations.',
         'companies' => ['Google', 'Amazon']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'What does the operation i & (-i) compute and why is it central to a BIT?',
         'answer_md' => 'It isolates the **lowest set bit** of i, which equals the size of the range that BIT node i is responsible for. Adding/subtracting it walks the tree in O(log n) for updates and prefix queries.',
         'companies' => ['Bloomberg']],
        ['type' => 'coding', 'difficulty' => 'hard',
         'question' => 'Count inversions in an array using a BIT.',
         'answer_md' => 'Compress values, then iterate right-to-left (or left-to-right), querying how many already-seen elements are smaller/larger via the BIT and updating frequencies. O(n log n).',
         'companies' => ['Amazon', 'Google']],
    ],

    'quiz' => [
        'title' => 'Fenwick Tree Quiz',
        'questions' => [
            ['question' => 'A Fenwick tree supports prefix sum and point update in:',
             'options' => [['text' => 'O(log n)', 'correct' => true], ['text' => 'O(1)', 'correct' => false], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false]]],
            ['question' => 'i & (-i) returns:',
             'options' => [['text' => 'The lowest set bit of i', 'correct' => true], ['text' => 'The highest set bit', 'correct' => false], ['text' => 'i doubled', 'correct' => false], ['text' => 'Zero always', 'correct' => false]]],
            ['question' => 'A Fenwick tree is typically:',
             'options' => [['text' => '1-indexed', 'correct' => true], ['text' => '0-indexed', 'correct' => false], ['text' => 'Unindexed', 'correct' => false], ['text' => 'Indexed by hash', 'correct' => false]]],
        ],
    ],
];
