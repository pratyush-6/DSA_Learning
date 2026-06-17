<?php
return [
    'title'       => 'Union Find (Disjoint Set)',
    'slug'        => 'union-find',
    'level'       => 'advanced',
    'icon'        => 'bi-bezier2',
    'sort_order'  => 24,
    'description' => 'Track disjoint groups with near-constant-time union and find operations.',

    'topics' => [
        [
            'title'   => 'Disjoint Set Union (DSU)',
            'slug'    => 'dsu-basics',
            'summary' => 'Union by rank + path compression → nearly O(1).',
            'theory_md' => <<<MD
A **Disjoint Set Union (DSU)** / **Union-Find** maintains a collection of disjoint sets
and answers: *"are these two elements in the same set?"* It supports:
- **find(x):** the representative (root) of x's set.
- **union(a, b):** merge the two sets containing a and b.

Two optimizations make it nearly constant time:
1. **Path compression:** during `find`, point nodes directly at the root.
2. **Union by rank/size:** attach the smaller tree under the larger.

Together they give **O(α(n))** amortized per operation, where α is the inverse Ackermann
function — effectively a small constant (< 5 for any practical n).
MD,
            'complexity_md' => "find / union: **O(α(n))** amortized ≈ O(1). Space: O(n).",
            'real_world_md' => <<<MD
- **Kruskal’s MST** algorithm (detect cycles while adding edges).
- **Network connectivity** ("are these computers connected?").
- **Image processing** (connected components / flood fill grouping).
- **Account/identity merging** and **percolation** problems.
MD,
            'code' => [
                ['language' => 'python', 'label' => 'DSU with both optimizations', 'code' => <<<'CODE'
class DSU:
    def __init__(self, n):
        self.parent = list(range(n))
        self.rank = [0] * n
    def find(self, x):
        while self.parent[x] != x:
            self.parent[x] = self.parent[self.parent[x]]  # path compression
            x = self.parent[x]
        return x
    def union(self, a, b):
        ra, rb = self.find(a), self.find(b)
        if ra == rb: return False
        if self.rank[ra] < self.rank[rb]: ra, rb = rb, ra
        self.parent[rb] = ra
        if self.rank[ra] == self.rank[rb]: self.rank[ra] += 1
        return True
CODE],
                ['language' => 'php', 'label' => 'DSU with both optimizations', 'code' => <<<'CODE'
<?php
class DSU {
    private array $parent, $rank;
    public function __construct(int $n) {
        $this->parent = range(0, $n - 1);
        $this->rank = array_fill(0, $n, 0);
    }
    public function find(int $x): int {
        while ($this->parent[$x] !== $x) {
            $this->parent[$x] = $this->parent[$this->parent[$x]]; // path compression
            $x = $this->parent[$x];
        }
        return $x;
    }
    public function union(int $a, int $b): bool {
        $ra = $this->find($a); $rb = $this->find($b);
        if ($ra === $rb) return false;
        if ($this->rank[$ra] < $this->rank[$rb]) { [$ra, $rb] = [$rb, $ra]; }
        $this->parent[$rb] = $ra;
        if ($this->rank[$ra] === $this->rank[$rb]) $this->rank[$ra]++;
        return true;
    }
}
CODE],
                ['language' => 'java', 'label' => 'DSU with both optimizations', 'code' => <<<'CODE'
class DSU {
    int[] parent, rank;
    DSU(int n){ parent = new int[n]; rank = new int[n];
        for(int i=0;i<n;i++) parent[i] = i; }
    int find(int x){
        while(parent[x] != x){ parent[x] = parent[parent[x]]; x = parent[x]; }
        return x;
    }
    boolean union(int a, int b){
        int ra = find(a), rb = find(b);
        if(ra == rb) return false;
        if(rank[ra] < rank[rb]){ int t=ra; ra=rb; rb=t; }
        parent[rb] = ra;
        if(rank[ra] == rank[rb]) rank[ra]++;
        return true;
    }
}
CODE],
                ['language' => 'cpp', 'label' => 'DSU with both optimizations', 'code' => <<<'CODE'
struct DSU {
    vector<int> parent, rnk;
    DSU(int n): parent(n), rnk(n, 0) { iota(parent.begin(), parent.end(), 0); }
    int find(int x){
        while(parent[x] != x){ parent[x] = parent[parent[x]]; x = parent[x]; }
        return x;
    }
    bool unite(int a, int b){
        int ra = find(a), rb = find(b);
        if(ra == rb) return false;
        if(rnk[ra] < rnk[rb]) swap(ra, rb);
        parent[rb] = ra;
        if(rnk[ra] == rnk[rb]) rnk[ra]++;
        return true;
    }
};
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'What two optimizations make union-find nearly O(1)?',
         'answer_md' => '**Path compression** (flatten the tree during find) and **union by rank/size** (attach the smaller tree under the larger). Together they give O(α(n)) amortized — effectively constant.',
         'companies' => ['Google', 'Amazon', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Count connected components in a graph using union-find.',
         'answer_md' => 'Start with n components; for each edge, `union` its endpoints and decrement the count when a real merge happens. The final count is the number of components.',
         'companies' => ['Amazon', 'Meta']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'Where is union-find used in MST algorithms?',
         'answer_md' => 'In **Kruskal’s** algorithm: edges are sorted by weight and added if their endpoints are in different sets (union); if they are already connected, adding the edge would form a cycle, so it is skipped.',
         'companies' => ['Google']],
    ],

    'problems' => [
        ['title' => 'Number of Connected Components', 'slug' => 'uf-connected-components', 'difficulty' => 'medium',
         'statement_md' => "Given `n` nodes labeled 0..n-1 and a list of undirected edges, return the number of connected components.",
         'examples_md' => "```\nn = 5, edges = [[0,1],[1,2],[3,4]] -> 2 components\n```",
         'solutions' => [
            'python' => ['code' => "def count_components(n, edges):\n    dsu = DSU(n)\n    count = n\n    for a, b in edges:\n        if dsu.union(a, b):\n            count -= 1\n    return count", 'explanation_md' => 'Each successful union merges two components, reducing the count by one.'],
            'php' => ['code' => "<?php\nfunction countComponents(int \$n, array \$edges): int {\n    \$dsu = new DSU(\$n); \$count = \$n;\n    foreach (\$edges as [\$a, \$b]) if (\$dsu->union(\$a, \$b)) \$count--;\n    return \$count;\n}", 'explanation_md' => ''],
            'java' => ['code' => "int countComponents(int n, int[][] edges){\n    DSU dsu = new DSU(n); int count = n;\n    for(int[] e : edges) if(dsu.union(e[0], e[1])) count--;\n    return count;\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "int countComponents(int n, vector<vector<int>>& edges){\n    DSU dsu(n); int count = n;\n    for(auto& e : edges) if(dsu.unite(e[0], e[1])) count--;\n    return count;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Union Find Quiz',
        'questions' => [
            ['question' => 'Amortized time per union/find with both optimizations is:',
             'options' => [['text' => 'O(α(n)) ≈ O(1)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(log² n)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false]]],
            ['question' => 'Path compression does what?',
             'options' => [['text' => 'Points nodes directly at the root during find', 'correct' => true], ['text' => 'Sorts the elements', 'correct' => false], ['text' => 'Deletes a set', 'correct' => false], ['text' => 'Balances a BST', 'correct' => false]]],
            ['question' => 'Union-find is central to which MST algorithm?',
             'options' => [['text' => "Kruskal's", 'correct' => true], ['text' => 'Dijkstra', 'correct' => false], ['text' => 'BFS', 'correct' => false], ['text' => 'Quick sort', 'correct' => false]]],
        ],
    ],
];
