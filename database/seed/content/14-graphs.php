<?php
return [
    'title'       => 'Graphs',
    'slug'        => 'graphs',
    'level'       => 'intermediate',
    'icon'        => 'bi-share',
    'sort_order'  => 14,
    'description' => 'Networks of vertices and edges — model relationships, maps, and dependencies.',

    'topics' => [
        [
            'title'   => 'Graph Representations',
            'slug'    => 'graph-representations',
            'summary' => 'Adjacency list vs adjacency matrix; directed/undirected, weighted.',
            'theory_md' => <<<MD
A **graph** is a set of **vertices (nodes)** connected by **edges**. Graphs can be:
- **Directed** (edges have direction) or **undirected**.
- **Weighted** (edges carry a cost) or unweighted.
- **Cyclic** or **acyclic** (a DAG = directed acyclic graph).

Two common representations:
- **Adjacency list:** for each vertex, a list of its neighbors. Space **O(V + E)**.
  Best for **sparse** graphs (most real graphs).
- **Adjacency matrix:** a V×V grid where `M[i][j]` marks an edge. Space **O(V²)**, O(1)
  edge lookup. Best for **dense** graphs.
MD,
            'complexity_md' => "| | Adj. List | Adj. Matrix |\n|---|---|---|\n| Space | O(V+E) | O(V²) |\n| Has edge? | O(deg) | O(1) |\n| Iterate neighbors | O(deg) | O(V) |",
            'real_world_md' => <<<MD
- **Social networks** (friends), **maps** (roads), **the web** (links).
- **Dependency graphs** (build systems, package managers).
- **Recommendation engines** and **network routing**.
MD,
            'code' => [
                ['language' => 'python', 'label' => 'Adjacency list', 'code' => <<<'CODE'
from collections import defaultdict
graph = defaultdict(list)
def add_edge(u, v):       # undirected
    graph[u].append(v)
    graph[v].append(u)

add_edge(1, 2); add_edge(1, 3); add_edge(2, 4)
print(graph[1])           # [2, 3]
CODE],
                ['language' => 'php', 'label' => 'Adjacency list', 'code' => <<<'CODE'
<?php
$graph = [];
function addEdge(array &$g, int $u, int $v): void {  // undirected
    $g[$u][] = $v;
    $g[$v][] = $u;
}
addEdge($graph, 1, 2);
addEdge($graph, 1, 3);
print_r($graph[1]);       // [2, 3]
CODE],
                ['language' => 'java', 'label' => 'Adjacency list', 'code' => <<<'CODE'
import java.util.*;
Map<Integer,List<Integer>> graph = new HashMap<>();
void addEdge(int u, int v){
    graph.computeIfAbsent(u, k -> new ArrayList<>()).add(v);
    graph.computeIfAbsent(v, k -> new ArrayList<>()).add(u);
}
CODE],
                ['language' => 'cpp', 'label' => 'Adjacency list', 'code' => <<<'CODE'
#include <vector>
using namespace std;
vector<vector<int>> graph(n);    // n vertices
void addEdge(int u, int v){
    graph[u].push_back(v);
    graph[v].push_back(u);       // undirected
}
CODE],
            ],
        ],
        [
            'title'   => 'BFS and DFS Traversal',
            'slug'    => 'graph-bfs-dfs',
            'summary' => 'The two fundamental graph traversals.',
            'theory_md' => <<<MD
**BFS (Breadth-First Search)** explores level by level using a **queue**. It finds the
**shortest path in unweighted graphs**.

**DFS (Depth-First Search)** dives as deep as possible before backtracking, using
**recursion or a stack**. It is the basis of cycle detection, topological sort, and
connected components.

Both visit each vertex and edge once → **O(V + E)**. Always track **visited** vertices
to avoid infinite loops on cycles.
MD,
            'complexity_md' => "BFS / DFS: **O(V + E)** time, O(V) space for the visited set and frontier.",
            'real_world_md' => "BFS → shortest hops in social networks, web crawling by level. DFS → maze solving, dependency resolution, detecting deadlocks.",
            'code' => [
                ['language' => 'python', 'label' => 'BFS & DFS', 'code' => <<<'CODE'
from collections import deque
def bfs(graph, start):
    seen = {start}; q = deque([start]); order = []
    while q:
        u = q.popleft(); order.append(u)
        for v in graph[u]:
            if v not in seen:
                seen.add(v); q.append(v)
    return order

def dfs(graph, u, seen):
    seen.add(u)
    for v in graph[u]:
        if v not in seen:
            dfs(graph, v, seen)
CODE],
                ['language' => 'php', 'label' => 'BFS & DFS', 'code' => <<<'CODE'
<?php
function bfs(array $g, int $start): array {
    $seen = [$start => true]; $q = [$start]; $order = [];
    while ($q) {
        $u = array_shift($q); $order[] = $u;
        foreach ($g[$u] ?? [] as $v) {
            if (!isset($seen[$v])) { $seen[$v] = true; $q[] = $v; }
        }
    }
    return $order;
}
function dfs(array $g, int $u, array &$seen): void {
    $seen[$u] = true;
    foreach ($g[$u] ?? [] as $v) if (!isset($seen[$v])) dfs($g, $v, $seen);
}
CODE],
                ['language' => 'java', 'label' => 'BFS', 'code' => <<<'CODE'
List<Integer> bfs(Map<Integer,List<Integer>> g, int start){
    List<Integer> order = new ArrayList<>();
    Set<Integer> seen = new HashSet<>(); seen.add(start);
    Queue<Integer> q = new LinkedList<>(); q.add(start);
    while(!q.isEmpty()){
        int u = q.poll(); order.add(u);
        for(int v : g.getOrDefault(u, List.of()))
            if(seen.add(v)) q.add(v);
    }
    return order;
}
CODE],
                ['language' => 'cpp', 'label' => 'BFS', 'code' => <<<'CODE'
vector<int> bfs(vector<vector<int>>& g, int start){
    vector<int> order; vector<bool> seen(g.size(), false);
    queue<int> q; q.push(start); seen[start] = true;
    while(!q.empty()){
        int u = q.front(); q.pop(); order.push_back(u);
        for(int v : g[u]) if(!seen[v]){ seen[v]=true; q.push(v); }
    }
    return order;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'When would you use an adjacency list vs an adjacency matrix?',
         'answer_md' => 'Use an **adjacency list** for sparse graphs (O(V+E) space, efficient neighbor iteration) — the common case. Use an **adjacency matrix** for dense graphs or when you need O(1) edge-existence checks (O(V²) space).',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Find the number of connected components in an undirected graph.',
         'answer_md' => 'Run DFS/BFS from every unvisited vertex; each launch marks one component. Count the launches. O(V+E). (Union-Find also works.)',
         'companies' => ['Google', 'Meta']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Detect a cycle in a directed graph.',
         'answer_md' => 'DFS with three states (unvisited, in-progress, done). If you reach an **in-progress** node, there is a back edge → a cycle. O(V+E).',
         'companies' => ['Amazon', 'Uber']],
    ],

    'problems' => [
        ['title' => 'Number of Islands', 'slug' => 'graphs-number-of-islands', 'difficulty' => 'medium',
         'statement_md' => "Given a 2D grid of `'1'` (land) and `'0'` (water), count the islands. An island is connected horizontally or vertically.",
         'examples_md' => "```\n11000\n11000\n00100\n00011   -> 3 islands\n```",
         'constraints_md' => "- 1 ≤ rows, cols ≤ 300",
         'solutions' => [
            'python' => ['code' => "def num_islands(grid):\n    if not grid: return 0\n    rows, cols = len(grid), len(grid[0])\n    def dfs(r, c):\n        if r < 0 or c < 0 or r >= rows or c >= cols or grid[r][c] != '1':\n            return\n        grid[r][c] = '0'   # sink\n        dfs(r+1,c); dfs(r-1,c); dfs(r,c+1); dfs(r,c-1)\n    count = 0\n    for r in range(rows):\n        for c in range(cols):\n            if grid[r][c] == '1':\n                count += 1; dfs(r, c)\n    return count", 'explanation_md' => 'Each unvisited land cell starts a DFS that sinks its whole island. O(rows·cols).'],
            'php' => ['code' => "<?php\nfunction numIslands(array \$grid): int {\n    \$rows = count(\$grid); if (!\$rows) return 0;\n    \$cols = count(\$grid[0]);\n    \$dfs = function(\$r, \$c) use (&\$dfs, &\$grid, \$rows, \$cols) {\n        if (\$r < 0 || \$c < 0 || \$r >= \$rows || \$c >= \$cols || \$grid[\$r][\$c] !== '1') return;\n        \$grid[\$r][\$c] = '0';\n        \$dfs(\$r+1,\$c); \$dfs(\$r-1,\$c); \$dfs(\$r,\$c+1); \$dfs(\$r,\$c-1);\n    };\n    \$count = 0;\n    for (\$r=0;\$r<\$rows;\$r++) for (\$c=0;\$c<\$cols;\$c++)\n        if (\$grid[\$r][\$c]==='1'){ \$count++; \$dfs(\$r,\$c); }\n    return \$count;\n}", 'explanation_md' => ''],
            'java' => ['code' => "int numIslands(char[][] grid){\n    int rows = grid.length, cols = grid[0].length, count = 0;\n    for(int r=0;r<rows;r++) for(int c=0;c<cols;c++)\n        if(grid[r][c]=='1'){ count++; dfs(grid,r,c); }\n    return count;\n}\nvoid dfs(char[][] g, int r, int c){\n    if(r<0||c<0||r>=g.length||c>=g[0].length||g[r][c]!='1') return;\n    g[r][c]='0';\n    dfs(g,r+1,c); dfs(g,r-1,c); dfs(g,r,c+1); dfs(g,r,c-1);\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void dfs(vector<vector<char>>& g, int r, int c){\n    if(r<0||c<0||r>=g.size()||c>=g[0].size()||g[r][c]!='1') return;\n    g[r][c]='0';\n    dfs(g,r+1,c); dfs(g,r-1,c); dfs(g,r,c+1); dfs(g,r,c-1);\n}\nint numIslands(vector<vector<char>>& grid){\n    int count = 0;\n    for(int r=0;r<grid.size();r++) for(int c=0;c<grid[0].size();c++)\n        if(grid[r][c]=='1'){ count++; dfs(grid,r,c); }\n    return count;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Graphs Quiz',
        'questions' => [
            ['question' => 'Adjacency list space complexity is:',
             'options' => [['text' => 'O(V + E)', 'correct' => true], ['text' => 'O(V²)', 'correct' => false], ['text' => 'O(E²)', 'correct' => false], ['text' => 'O(1)', 'correct' => false]]],
            ['question' => 'Which traversal finds shortest paths in an unweighted graph?',
             'options' => [['text' => 'BFS', 'correct' => true], ['text' => 'DFS', 'correct' => false], ['text' => 'Inorder', 'correct' => false], ['text' => 'Random walk', 'correct' => false]]],
            ['question' => 'BFS/DFS time complexity is:',
             'options' => [['text' => 'O(V + E)', 'correct' => true], ['text' => 'O(V·E)', 'correct' => false], ['text' => 'O(V²)', 'correct' => false], ['text' => 'O(log V)', 'correct' => false]]],
        ],
    ],
];
