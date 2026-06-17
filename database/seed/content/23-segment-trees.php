<?php
return [
    'title'       => 'Segment Trees',
    'slug'        => 'segment-trees',
    'level'       => 'advanced',
    'icon'        => 'bi-bar-chart-steps',
    'sort_order'  => 23,
    'description' => 'Answer range queries (sum/min/max) and point updates in O(log n).',

    'topics' => [
        [
            'title'   => 'Range Queries with Segment Trees',
            'slug'    => 'segment-tree-basics',
            'summary' => 'A binary tree over array ranges for fast queries + updates.',
            'theory_md' => <<<MD
A **segment tree** answers **range queries** (sum, min, max, gcd…) and supports **point
updates**, both in **O(log n)**.

Each node represents a **range** `[l, r]`. Leaves are single elements; an internal node
combines its two children (e.g., stores their sum). For an array of n elements, the tree
uses about **2–4n** array slots.

- **Build:** O(n).
- **Query(l, r):** recurse, combining fully-covered segments → O(log n).
- **Update(i, value):** update the leaf and recombine up the path → O(log n).

> Compared to a **prefix-sum array** (O(1) query but O(n) update), a segment tree gives
> O(log n) for **both** query and update — ideal when the data changes.

**Lazy propagation** extends it to O(log n) **range updates**.
MD,
            'complexity_md' => "Build O(n); query O(log n); point update O(log n); range update O(log n) with lazy propagation. Space O(n).",
            'real_world_md' => "- **Range-sum / range-min dashboards** over changing data.\n- **Competitive programming** range problems.\n- **Genomics** interval statistics and **financial** time-window aggregates.",
            'code' => [
                ['language' => 'python', 'label' => 'Sum segment tree', 'code' => <<<'CODE'
class SegTree:
    def __init__(self, a):
        self.n = len(a)
        self.t = [0] * (2 * self.n)
        for i in range(self.n):
            self.t[self.n + i] = a[i]
        for i in range(self.n - 1, 0, -1):
            self.t[i] = self.t[2*i] + self.t[2*i+1]
    def update(self, i, val):
        i += self.n; self.t[i] = val
        i //= 2
        while i:
            self.t[i] = self.t[2*i] + self.t[2*i+1]; i //= 2
    def query(self, l, r):          # sum of [l, r)
        res = 0; l += self.n; r += self.n
        while l < r:
            if l & 1: res += self.t[l]; l += 1
            if r & 1: r -= 1; res += self.t[r]
            l //= 2; r //= 2
        return res
CODE],
                ['language' => 'php', 'label' => 'Sum segment tree', 'code' => <<<'CODE'
<?php
class SegTree {
    private int $n; private array $t;
    public function __construct(array $a) {
        $this->n = count($a);
        $this->t = array_fill(0, 2 * $this->n, 0);
        for ($i = 0; $i < $this->n; $i++) $this->t[$this->n + $i] = $a[$i];
        for ($i = $this->n - 1; $i > 0; $i--) $this->t[$i] = $this->t[2*$i] + $this->t[2*$i+1];
    }
    public function update(int $i, int $val): void {
        $i += $this->n; $this->t[$i] = $val; $i = intdiv($i, 2);
        while ($i) { $this->t[$i] = $this->t[2*$i] + $this->t[2*$i+1]; $i = intdiv($i, 2); }
    }
    public function query(int $l, int $r): int {   // sum of [l, r)
        $res = 0; $l += $this->n; $r += $this->n;
        while ($l < $r) {
            if ($l & 1) { $res += $this->t[$l]; $l++; }
            if ($r & 1) { $r--; $res += $this->t[$r]; }
            $l = intdiv($l, 2); $r = intdiv($r, 2);
        }
        return $res;
    }
}
CODE],
                ['language' => 'java', 'label' => 'Sum segment tree', 'code' => <<<'CODE'
class SegTree {
    int n; long[] t;
    SegTree(int[] a){
        n = a.length; t = new long[2*n];
        for(int i=0;i<n;i++) t[n+i] = a[i];
        for(int i=n-1;i>0;i--) t[i] = t[2*i] + t[2*i+1];
    }
    void update(int i, int val){
        i += n; t[i] = val;
        for(i/=2; i>0; i/=2) t[i] = t[2*i] + t[2*i+1];
    }
    long query(int l, int r){          // [l, r)
        long res = 0; l += n; r += n;
        while(l < r){
            if((l&1)==1) res += t[l++];
            if((r&1)==1) res += t[--r];
            l/=2; r/=2;
        }
        return res;
    }
}
CODE],
                ['language' => 'cpp', 'label' => 'Sum segment tree', 'code' => <<<'CODE'
struct SegTree {
    int n; vector<long long> t;
    SegTree(vector<int>& a){
        n = a.size(); t.assign(2*n, 0);
        for(int i=0;i<n;i++) t[n+i] = a[i];
        for(int i=n-1;i>0;i--) t[i] = t[2*i] + t[2*i+1];
    }
    void update(int i, int val){
        for(t[i+=n]=val, i/=2; i>0; i/=2) t[i] = t[2*i] + t[2*i+1];
    }
    long long query(int l, int r){      // [l, r)
        long long res = 0;
        for(l+=n, r+=n; l<r; l/=2, r/=2){
            if(l&1) res += t[l++];
            if(r&1) res += t[--r];
        }
        return res;
    }
};
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'When is a segment tree better than a prefix-sum array?',
         'answer_md' => 'When the underlying data **changes**. A prefix-sum array gives O(1) range queries but O(n) updates. A segment tree gives **O(log n) for both** queries and updates.',
         'companies' => ['Google', 'Amazon']],
        ['type' => 'conceptual', 'difficulty' => 'hard',
         'question' => 'What is lazy propagation and why is it needed?',
         'answer_md' => 'It defers range updates by storing a pending value at internal nodes and applying it only when that node is later visited. This enables **range updates** in O(log n) instead of O(n).',
         'companies' => ['Google', 'Bloomberg']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'What is the space complexity of a segment tree?',
         'answer_md' => 'O(n) — typically about 2n (iterative) to 4n (recursive) array slots for n elements.',
         'companies' => ['Atlassian']],
    ],

    'problems' => [
        ['title' => 'Range Sum Query - Mutable', 'slug' => 'segtree-range-sum-mutable', 'difficulty' => 'medium',
         'statement_md' => "Support two operations on an integer array: `update(index, value)` and `sumRange(left, right)` (inclusive). Both should be efficient under many mixed operations.",
         'examples_md' => "```\nnums = [1,3,5]\nsumRange(0,2) -> 9\nupdate(1, 2)\nsumRange(0,2) -> 8\n```",
         'solutions' => [
            'python' => ['code' => "class NumArray:\n    def __init__(self, nums):\n        self.st = SegTree(nums)\n    def update(self, index, val):\n        self.st.update(index, val)\n    def sumRange(self, left, right):\n        return self.st.query(left, right + 1)  # convert to [l, r)", 'explanation_md' => 'Wrap the iterative segment tree above. Both ops O(log n).'],
            'php' => ['code' => "<?php\nclass NumArray {\n    private SegTree \$st;\n    function __construct(array \$nums){ \$this->st = new SegTree(\$nums); }\n    function update(int \$i, int \$val): void { \$this->st->update(\$i, \$val); }\n    function sumRange(int \$l, int \$r): int { return \$this->st->query(\$l, \$r + 1); }\n}", 'explanation_md' => ''],
            'java' => ['code' => "class NumArray {\n    SegTree st;\n    NumArray(int[] nums){ st = new SegTree(nums); }\n    void update(int i, int val){ st.update(i, val); }\n    long sumRange(int l, int r){ return st.query(l, r + 1); }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "class NumArray {\n    SegTree st;\npublic:\n    NumArray(vector<int>& nums): st(nums) {}\n    void update(int i, int val){ st.update(i, val); }\n    long long sumRange(int l, int r){ return st.query(l, r + 1); }\n};", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Segment Trees Quiz',
        'questions' => [
            ['question' => 'A segment tree answers range queries and point updates in:',
             'options' => [['text' => 'O(log n)', 'correct' => true], ['text' => 'O(1)', 'correct' => false], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false]]],
            ['question' => 'Prefix-sum arrays struggle with which operation?',
             'options' => [['text' => 'Frequent updates', 'correct' => true], ['text' => 'Range queries', 'correct' => false], ['text' => 'Reading the first element', 'correct' => false], ['text' => 'Sorting', 'correct' => false]]],
            ['question' => 'Lazy propagation enables efficient:',
             'options' => [['text' => 'Range updates', 'correct' => true], ['text' => 'Hashing', 'correct' => false], ['text' => 'Sorting', 'correct' => false], ['text' => 'DFS', 'correct' => false]]],
        ],
    ],
];
