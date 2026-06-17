<?php
return [
    'title'       => 'Backtracking',
    'slug'        => 'backtracking',
    'level'       => 'advanced',
    'icon'        => 'bi-signpost-split',
    'sort_order'  => 20,
    'description' => 'Systematically build candidates and abandon ("backtrack") those that cannot succeed.',

    'topics' => [
        [
            'title'   => 'The Backtracking Template',
            'slug'    => 'backtracking-template',
            'summary' => 'Choose → explore → un-choose.',
            'theory_md' => <<<MD
**Backtracking** is a refined brute force: build a solution incrementally, and as soon
as a partial candidate **cannot** lead to a valid solution, abandon it and **undo** the
last choice.

The universal template:
```
def backtrack(state):
    if is_solution(state):
        record(state); return
    for choice in choices(state):
        if is_valid(choice):
            make(choice)          # choose
            backtrack(state)      # explore
            undo(choice)          # un-choose (backtrack)
```

It explores a **decision tree**, pruning branches early. Used for **permutations,
combinations, subsets, N-Queens, Sudoku, word search**, and constraint problems.
MD,
            'complexity_md' => "Often exponential in the worst case (e.g., subsets O(2ⁿ), permutations O(n·n!)), but **pruning** dramatically cuts real work. Space O(depth) for the recursion + current path.",
            'real_world_md' => "- **Puzzle solvers** (Sudoku, crosswords).\n- **Constraint satisfaction** (scheduling, seating).\n- **Parsing/regex** matching with choices.\n- **Pathfinding** in mazes.",
            'code' => [
                ['language' => 'python', 'label' => 'Subsets', 'code' => <<<'CODE'
def subsets(nums):
    res, path = [], []
    def backtrack(start):
        res.append(path[:])           # record current subset
        for i in range(start, len(nums)):
            path.append(nums[i])      # choose
            backtrack(i + 1)          # explore
            path.pop()                # un-choose
    backtrack(0)
    return res
CODE],
                ['language' => 'php', 'label' => 'Subsets', 'code' => <<<'CODE'
<?php
function subsets(array $nums): array {
    $res = []; $path = [];
    $bt = function(int $start) use (&$bt, &$res, &$path, $nums) {
        $res[] = $path;                       // record
        for ($i = $start; $i < count($nums); $i++) {
            $path[] = $nums[$i];              // choose
            $bt($i + 1);                       // explore
            array_pop($path);                  // un-choose
        }
    };
    $bt(0);
    return $res;
}
CODE],
                ['language' => 'java', 'label' => 'Subsets', 'code' => <<<'CODE'
List<List<Integer>> subsets(int[] nums){
    List<List<Integer>> res = new ArrayList<>();
    backtrack(nums, 0, new ArrayList<>(), res);
    return res;
}
void backtrack(int[] nums, int start, List<Integer> path, List<List<Integer>> res){
    res.add(new ArrayList<>(path));
    for(int i = start; i < nums.length; i++){
        path.add(nums[i]);
        backtrack(nums, i + 1, path, res);
        path.remove(path.size() - 1);
    }
}
CODE],
                ['language' => 'cpp', 'label' => 'Subsets', 'code' => <<<'CODE'
void backtrack(vector<int>& nums, int start, vector<int>& path,
               vector<vector<int>>& res){
    res.push_back(path);
    for(int i = start; i < (int)nums.size(); i++){
        path.push_back(nums[i]);
        backtrack(nums, i + 1, path, res);
        path.pop_back();
    }
}
vector<vector<int>> subsets(vector<int>& nums){
    vector<vector<int>> res; vector<int> path;
    backtrack(nums, 0, path, res);
    return res;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'How does backtracking differ from plain brute force?',
         'answer_md' => 'Both explore candidates, but backtracking **prunes**: it abandons a partial solution the moment it cannot possibly lead to a valid answer, and undoes the last choice. This avoids exploring large dead-end subtrees.',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Generate all permutations of a list.',
         'answer_md' => 'Backtrack: at each position try every unused element, mark it used, recurse, then unmark. O(n·n!).',
         'companies' => ['Google', 'Meta', 'Amazon']],
        ['type' => 'coding', 'difficulty' => 'hard',
         'question' => 'Solve the N-Queens problem.',
         'answer_md' => 'Place one queen per row; for each column check no conflict on column or the two diagonals (use sets for O(1) checks); recurse and backtrack. Prune invalid placements early.',
         'companies' => ['Google', 'Adobe']],
    ],

    'problems' => [
        ['title' => 'Generate Permutations', 'slug' => 'bt-permutations', 'difficulty' => 'medium',
         'statement_md' => "Given an array of distinct integers, return all possible permutations.",
         'examples_md' => "```\n[1,2,3] -> [[1,2,3],[1,3,2],[2,1,3],[2,3,1],[3,1,2],[3,2,1]]\n```",
         'solutions' => [
            'python' => ['code' => "def permute(nums):\n    res, path, used = [], [], [False]*len(nums)\n    def bt():\n        if len(path) == len(nums):\n            res.append(path[:]); return\n        for i in range(len(nums)):\n            if used[i]: continue\n            used[i] = True; path.append(nums[i])\n            bt()\n            path.pop(); used[i] = False\n    bt()\n    return res", 'explanation_md' => 'Try each unused number at each slot; undo after recursing. O(n·n!).'],
            'php' => ['code' => "<?php\nfunction permute(array \$nums): array {\n    \$res = []; \$path = []; \$used = array_fill(0, count(\$nums), false);\n    \$bt = function() use (&\$bt, &\$res, &\$path, &\$used, \$nums) {\n        if (count(\$path) === count(\$nums)) { \$res[] = \$path; return; }\n        for (\$i = 0; \$i < count(\$nums); \$i++) {\n            if (\$used[\$i]) continue;\n            \$used[\$i] = true; \$path[] = \$nums[\$i];\n            \$bt();\n            array_pop(\$path); \$used[\$i] = false;\n        }\n    };\n    \$bt();\n    return \$res;\n}", 'explanation_md' => ''],
            'java' => ['code' => "List<List<Integer>> permute(int[] nums){\n    List<List<Integer>> res = new ArrayList<>();\n    bt(nums, new ArrayList<>(), new boolean[nums.length], res);\n    return res;\n}\nvoid bt(int[] nums, List<Integer> path, boolean[] used, List<List<Integer>> res){\n    if(path.size() == nums.length){ res.add(new ArrayList<>(path)); return; }\n    for(int i=0;i<nums.length;i++){\n        if(used[i]) continue;\n        used[i]=true; path.add(nums[i]);\n        bt(nums, path, used, res);\n        path.remove(path.size()-1); used[i]=false;\n    }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void bt(vector<int>& nums, vector<int>& path, vector<bool>& used,\n        vector<vector<int>>& res){\n    if(path.size()==nums.size()){ res.push_back(path); return; }\n    for(int i=0;i<(int)nums.size();i++){\n        if(used[i]) continue;\n        used[i]=true; path.push_back(nums[i]);\n        bt(nums, path, used, res);\n        path.pop_back(); used[i]=false;\n    }\n}\nvector<vector<int>> permute(vector<int>& nums){\n    vector<vector<int>> res; vector<int> path; vector<bool> used(nums.size(), false);\n    bt(nums, path, used, res);\n    return res;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Backtracking Quiz',
        'questions' => [
            ['question' => 'The core idea of backtracking is:',
             'options' => [['text' => 'Choose, explore, un-choose with pruning', 'correct' => true], ['text' => 'Sort then scan once', 'correct' => false], ['text' => 'Hash every state', 'correct' => false], ['text' => 'Always go greedy', 'correct' => false]]],
            ['question' => 'Number of subsets of n elements:',
             'options' => [['text' => '2ⁿ', 'correct' => true], ['text' => 'n²', 'correct' => false], ['text' => 'n!', 'correct' => false], ['text' => 'n log n', 'correct' => false]]],
            ['question' => 'Which problem is a classic backtracking example?',
             'options' => [['text' => 'N-Queens', 'correct' => true], ['text' => 'Binary search', 'correct' => false], ['text' => 'Merge sort', 'correct' => false], ['text' => 'Hash lookup', 'correct' => false]]],
        ],
    ],
];
