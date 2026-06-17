<?php
return [
    'title'       => 'Advanced Graph Algorithms',
    'slug'        => 'advanced-graph-algorithms',
    'level'       => 'advanced',
    'icon'        => 'bi-diagram-3',
    'sort_order'  => 25,
    'description' => 'Shortest paths (Dijkstra, Bellman-Ford), topological sort, and MST.',

    'topics' => [
        [
            'title'   => 'Shortest Paths',
            'slug'    => 'shortest-paths',
            'summary' => 'Dijkstra, Bellman-Ford, and when to use each.',
            'theory_md' => <<<MD
- **BFS:** shortest path in an **unweighted** graph. O(V + E).
- **Dijkstra:** shortest paths from a source in a graph with **non-negative** weights,
  using a **min priority queue**. O((V + E) log V). Greedy: always expand the closest
  unsettled node.
- **Bellman-Ford:** handles **negative** edge weights and **detects negative cycles**.
  Relax all edges V−1 times. O(V·E) — slower but more general.
- **Floyd–Warshall:** all-pairs shortest paths via DP. O(V³), good for small dense graphs.

> Use Dijkstra by default; switch to Bellman-Ford only when negative weights are possible.
MD,
            'complexity_md' => "| Algorithm | Time | Weights |\n|---|---|---|\n| BFS | O(V+E) | unweighted |\n| Dijkstra | O((V+E) log V) | non-negative |\n| Bellman-Ford | O(V·E) | negative ok |\n| Floyd-Warshall | O(V³) | all pairs |",
            'real_world_md' => "- **GPS / maps** routing (Dijkstra, A*).\n- **Network packet routing** (link-state protocols).\n- **Currency arbitrage** detection (Bellman-Ford negative cycles).",
            'code' => [
                ['language' => 'python', 'label' => 'Dijkstra', 'code' => <<<'CODE'
import heapq
def dijkstra(graph, src, n):    # graph[u] = [(v, w), ...]
    dist = [float('inf')] * n
    dist[src] = 0
    pq = [(0, src)]
    while pq:
        d, u = heapq.heappop(pq)
        if d > dist[u]:
            continue
        for v, w in graph[u]:
            if d + w < dist[v]:
                dist[v] = d + w
                heapq.heappush(pq, (dist[v], v))
    return dist
CODE],
                ['language' => 'php', 'label' => 'Dijkstra', 'code' => <<<'CODE'
<?php
function dijkstra(array $graph, int $src, int $n): array {
    $dist = array_fill(0, $n, INF);
    $dist[$src] = 0;
    $pq = new SplPriorityQueue();
    $pq->insert($src, 0);               // priority negated internally; use -dist
    $pq->insert([$src, 0], 0);
    // Simpler: use a min-heap of [dist, node]
    $heap = new SplMinHeap();
    $heap->insert([0, $src]);
    while (!$heap->isEmpty()) {
        [$d, $u] = $heap->extract();
        if ($d > $dist[$u]) continue;
        foreach ($graph[$u] ?? [] as [$v, $w]) {
            if ($d + $w < $dist[$v]) {
                $dist[$v] = $d + $w;
                $heap->insert([$dist[$v], $v]);
            }
        }
    }
    return $dist;
}
CODE],
                ['language' => 'java', 'label' => 'Dijkstra', 'code' => <<<'CODE'
int[] dijkstra(List<int[]>[] graph, int src, int n){
    int[] dist = new int[n];
    Arrays.fill(dist, Integer.MAX_VALUE); dist[src] = 0;
    PriorityQueue<int[]> pq = new PriorityQueue<>((a,b) -> a[0] - b[0]);
    pq.offer(new int[]{0, src});
    while(!pq.isEmpty()){
        int[] cur = pq.poll(); int d = cur[0], u = cur[1];
        if(d > dist[u]) continue;
        for(int[] e : graph[u]){
            int v = e[0], w = e[1];
            if(d + w < dist[v]){ dist[v] = d + w; pq.offer(new int[]{dist[v], v}); }
        }
    }
    return dist;
}
CODE],
                ['language' => 'cpp', 'label' => 'Dijkstra', 'code' => <<<'CODE'
vector<int> dijkstra(vector<vector<pair<int,int>>>& g, int src, int n){
    vector<int> dist(n, INT_MAX); dist[src] = 0;
    priority_queue<pair<int,int>, vector<pair<int,int>>, greater<>> pq;
    pq.push({0, src});
    while(!pq.empty()){
        auto [d, u] = pq.top(); pq.pop();
        if(d > dist[u]) continue;
        for(auto [v, w] : g[u])
            if(d + w < dist[v]){ dist[v] = d + w; pq.push({dist[v], v}); }
    }
    return dist;
}
CODE],
            ],
        ],
        [
            'title'   => 'Topological Sort',
            'slug'    => 'topological-sort',
            'summary' => 'Order the vertices of a DAG so every edge points forward.',
            'theory_md' => <<<MD
A **topological sort** of a **DAG** (directed acyclic graph) lists vertices so that for
every edge u → v, u appears before v. It only exists if there are **no cycles**.

Two approaches:
- **Kahn’s algorithm (BFS):** repeatedly remove a node with **in-degree 0**. If you can't
  remove all nodes, a cycle exists.
- **DFS:** push nodes onto a stack on the way out (post-order); the reversed stack is a
  topological order.

Both are **O(V + E)**.
MD,
            'complexity_md' => "Topological sort: **O(V + E)** time, O(V) space.",
            'real_world_md' => "- **Build systems / package managers** (compile dependencies first).\n- **Task scheduling** with prerequisites (course schedules).\n- **Spreadsheet** cell recalculation order.",
            'code' => [
                ['language' => 'python', 'label' => "Kahn's algorithm", 'code' => <<<'CODE'
from collections import deque
def topo_sort(n, edges):           # edges: list of (u, v)
    graph = [[] for _ in range(n)]
    indeg = [0] * n
    for u, v in edges:
        graph[u].append(v); indeg[v] += 1
    q = deque(i for i in range(n) if indeg[i] == 0)
    order = []
    while q:
        u = q.popleft(); order.append(u)
        for v in graph[u]:
            indeg[v] -= 1
            if indeg[v] == 0: q.append(v)
    return order if len(order) == n else []  # [] means a cycle exists
CODE],
                ['language' => 'php', 'label' => "Kahn's algorithm", 'code' => <<<'CODE'
<?php
function topoSort(int $n, array $edges): array {
    $graph = array_fill(0, $n, []); $indeg = array_fill(0, $n, 0);
    foreach ($edges as [$u, $v]) { $graph[$u][] = $v; $indeg[$v]++; }
    $q = [];
    for ($i = 0; $i < $n; $i++) if ($indeg[$i] === 0) $q[] = $i;
    $order = [];
    while ($q) {
        $u = array_shift($q); $order[] = $u;
        foreach ($graph[$u] as $v) if (--$indeg[$v] === 0) $q[] = $v;
    }
    return count($order) === $n ? $order : [];
}
CODE],
                ['language' => 'java', 'label' => "Kahn's algorithm", 'code' => <<<'CODE'
int[] topoSort(int n, int[][] edges){
    List<List<Integer>> g = new ArrayList<>();
    for(int i=0;i<n;i++) g.add(new ArrayList<>());
    int[] indeg = new int[n];
    for(int[] e : edges){ g.get(e[0]).add(e[1]); indeg[e[1]]++; }
    Queue<Integer> q = new LinkedList<>();
    for(int i=0;i<n;i++) if(indeg[i]==0) q.add(i);
    int[] order = new int[n]; int idx = 0;
    while(!q.isEmpty()){
        int u = q.poll(); order[idx++] = u;
        for(int v : g.get(u)) if(--indeg[v]==0) q.add(v);
    }
    return idx == n ? order : new int[0];
}
CODE],
                ['language' => 'cpp', 'label' => "Kahn's algorithm", 'code' => <<<'CODE'
vector<int> topoSort(int n, vector<vector<int>>& edges){
    vector<vector<int>> g(n); vector<int> indeg(n, 0);
    for(auto& e : edges){ g[e[0]].push_back(e[1]); indeg[e[1]]++; }
    queue<int> q;
    for(int i=0;i<n;i++) if(indeg[i]==0) q.push(i);
    vector<int> order;
    while(!q.empty()){
        int u = q.front(); q.pop(); order.push_back(u);
        for(int v : g[u]) if(--indeg[v]==0) q.push(v);
    }
    return (int)order.size()==n ? order : vector<int>{};
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'When must you use Bellman-Ford instead of Dijkstra?',
         'answer_md' => 'When the graph has **negative edge weights**. Dijkstra’s greedy assumption breaks with negatives. Bellman-Ford handles them and also **detects negative cycles**, at O(V·E).',
         'companies' => ['Google', 'Amazon']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Course Schedule: can you finish all courses given prerequisites?',
         'answer_md' => 'Model courses as a directed graph and run a topological sort (Kahn’s). If you can order all nodes, there is no cycle → possible; otherwise a cycle of prerequisites makes it impossible.',
         'companies' => ['Amazon', 'Meta', 'Microsoft']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'Describe Kruskal’s vs Prim’s for Minimum Spanning Tree.',
         'answer_md' => '**Kruskal**: sort edges, add the cheapest that doesn’t form a cycle (union-find). **Prim**: grow the tree from a start vertex, always adding the cheapest edge to a new vertex (priority queue). Both produce an MST; Kruskal suits sparse, Prim suits dense graphs.',
         'companies' => ['Google', 'Uber']],
    ],

    'problems' => [
        ['title' => 'Network Delay Time', 'slug' => 'graph-network-delay', 'difficulty' => 'medium',
         'statement_md' => "You are given `times[i] = (u, v, w)` (a signal from u to v takes w time). Send a signal from node `k`. Return the time for **all** n nodes to receive it, or -1 if impossible.",
         'examples_md' => "```\ntimes = [[2,1,1],[2,3,1],[3,4,1]], n = 4, k = 2 -> 2\n```",
         'constraints_md' => "- 1 ≤ n ≤ 100; weights ≥ 0",
         'solutions' => [
            'python' => ['code' => "import heapq\ndef network_delay(times, n, k):\n    graph = [[] for _ in range(n + 1)]\n    for u, v, w in times:\n        graph[u].append((v, w))\n    dist = {}\n    pq = [(0, k)]\n    while pq:\n        d, u = heapq.heappop(pq)\n        if u in dist: continue\n        dist[u] = d\n        for v, w in graph[u]:\n            if v not in dist:\n                heapq.heappush(pq, (d + w, v))\n    return max(dist.values()) if len(dist) == n else -1", 'explanation_md' => 'Dijkstra from k; the answer is the largest shortest-distance, or -1 if some node is unreachable.'],
            'php' => ['code' => "<?php\nfunction networkDelay(array \$times, int \$n, int \$k): int {\n    \$graph = array_fill(1, \$n, []);\n    foreach (\$times as [\$u, \$v, \$w]) \$graph[\$u][] = [\$v, \$w];\n    \$dist = []; \$heap = new SplMinHeap(); \$heap->insert([0, \$k]);\n    while (!\$heap->isEmpty()) {\n        [\$d, \$u] = \$heap->extract();\n        if (isset(\$dist[\$u])) continue;\n        \$dist[\$u] = \$d;\n        foreach (\$graph[\$u] ?? [] as [\$v, \$w])\n            if (!isset(\$dist[\$v])) \$heap->insert([\$d + \$w, \$v]);\n    }\n    return count(\$dist) === \$n ? max(\$dist) : -1;\n}", 'explanation_md' => ''],
            'java' => ['code' => "int networkDelayTime(int[][] times, int n, int k){\n    List<int[]>[] g = new List[n + 1];\n    for(int i=1;i<=n;i++) g[i] = new ArrayList<>();\n    for(int[] t : times) g[t[0]].add(new int[]{t[1], t[2]});\n    int[] dist = new int[n + 1]; Arrays.fill(dist, Integer.MAX_VALUE);\n    PriorityQueue<int[]> pq = new PriorityQueue<>((a,b)->a[0]-b[0]);\n    pq.offer(new int[]{0, k}); dist[k] = 0;\n    while(!pq.isEmpty()){\n        int[] c = pq.poll(); int d=c[0], u=c[1];\n        if(d > dist[u]) continue;\n        for(int[] e : g[u]) if(d+e[1] < dist[e[0]]){ dist[e[0]]=d+e[1]; pq.offer(new int[]{dist[e[0]], e[0]}); }\n    }\n    int ans = 0;\n    for(int i=1;i<=n;i++){ if(dist[i]==Integer.MAX_VALUE) return -1; ans = Math.max(ans, dist[i]); }\n    return ans;\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "int networkDelayTime(vector<vector<int>>& times, int n, int k){\n    vector<vector<pair<int,int>>> g(n + 1);\n    for(auto& t : times) g[t[0]].push_back({t[1], t[2]});\n    vector<int> dist(n + 1, INT_MAX); dist[k] = 0;\n    priority_queue<pair<int,int>, vector<pair<int,int>>, greater<>> pq;\n    pq.push({0, k});\n    while(!pq.empty()){\n        auto [d, u] = pq.top(); pq.pop();\n        if(d > dist[u]) continue;\n        for(auto [v, w] : g[u]) if(d+w < dist[v]){ dist[v]=d+w; pq.push({dist[v], v}); }\n    }\n    int ans = 0;\n    for(int i=1;i<=n;i++){ if(dist[i]==INT_MAX) return -1; ans = max(ans, dist[i]); }\n    return ans;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Advanced Graphs Quiz',
        'questions' => [
            ['question' => "Dijkstra's algorithm requires edge weights to be:",
             'options' => [['text' => 'Non-negative', 'correct' => true], ['text' => 'All equal', 'correct' => false], ['text' => 'Negative', 'correct' => false], ['text' => 'Integers only', 'correct' => false]]],
            ['question' => 'Topological sort is possible only on a:',
             'options' => [['text' => 'Directed acyclic graph (DAG)', 'correct' => true], ['text' => 'Graph with a cycle', 'correct' => false], ['text' => 'Undirected graph', 'correct' => false], ['text' => 'Complete graph', 'correct' => false]]],
            ['question' => 'Which algorithm detects negative-weight cycles?',
             'options' => [['text' => 'Bellman-Ford', 'correct' => true], ['text' => 'Dijkstra', 'correct' => false], ['text' => 'BFS', 'correct' => false], ['text' => 'Topological sort', 'correct' => false]]],
        ],
    ],
];
