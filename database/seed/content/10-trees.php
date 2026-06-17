<?php
return [
    'title'       => 'Trees',
    'slug'        => 'trees',
    'level'       => 'intermediate',
    'icon'        => 'bi-diagram-3',
    'sort_order'  => 10,
    'description' => 'Hierarchical structures of nodes — the basis of file systems, parsers, and search trees.',

    'topics' => [
        [
            'title'   => 'Tree Terminology & Binary Trees',
            'slug'    => 'tree-basics',
            'summary' => 'Root, children, height, and the binary tree.',
            'theory_md' => <<<MD
A **tree** is a hierarchical structure of **nodes** connected by edges, with one
**root** and no cycles. Each node has child nodes; a node with no children is a **leaf**.

Key terms:
- **Root:** the top node. **Parent/Child:** direct relations.
- **Depth** of a node: edges from the root. **Height** of the tree: longest root-to-leaf path.
- **Subtree:** a node plus all its descendants.

A **binary tree** restricts each node to at most **two** children (left and right). A
**complete** binary tree fills levels left-to-right; a **balanced** tree keeps height
≈ log n.
MD,
            'complexity_md' => "Traversal visits each node once: **O(n)**. Recursion depth = tree height: O(h) space (O(log n) balanced, O(n) skewed).",
            'real_world_md' => <<<MD
- **File systems** (folders/files).
- **HTML/XML DOM** and **abstract syntax trees** in compilers.
- **Database indexes** (B-trees).
- **Decision trees** in ML and **org charts**.
MD,
            'code' => [
                ['language' => 'python', 'label' => 'Node + height', 'code' => <<<'CODE'
class TreeNode:
    def __init__(self, val):
        self.val = val
        self.left = None
        self.right = None

def height(root):
    if not root:
        return 0
    return 1 + max(height(root.left), height(root.right))
CODE],
                ['language' => 'php', 'label' => 'Node + height', 'code' => <<<'CODE'
<?php
class TreeNode {
    public ?TreeNode $left = null, $right = null;
    public function __construct(public int $val) {}
}
function height(?TreeNode $root): int {
    if (!$root) return 0;
    return 1 + max(height($root->left), height($root->right));
}
CODE],
                ['language' => 'java', 'label' => 'Node + height', 'code' => <<<'CODE'
class TreeNode { int val; TreeNode left, right; TreeNode(int v){val=v;} }

int height(TreeNode root){
    if(root == null) return 0;
    return 1 + Math.max(height(root.left), height(root.right));
}
CODE],
                ['language' => 'cpp', 'label' => 'Node + height', 'code' => <<<'CODE'
struct TreeNode { int val; TreeNode *left=nullptr, *right=nullptr; TreeNode(int v):val(v){} };

int height(TreeNode* root){
    if(!root) return 0;
    return 1 + max(height(root->left), height(root->right));
}
CODE],
            ],
        ],
        [
            'title'   => 'Tree Traversals',
            'slug'    => 'tree-traversals',
            'summary' => 'Inorder, preorder, postorder (DFS) and level-order (BFS).',
            'theory_md' => <<<MD
**Depth-First traversals** (recursion/stack):
- **Preorder** (Root, Left, Right) — copy/serialize a tree.
- **Inorder** (Left, Root, Right) — gives **sorted** order in a BST.
- **Postorder** (Left, Right, Root) — delete/free a tree, evaluate expressions.

**Breadth-First / Level-order** (queue): visit level by level, top to bottom.

All four visit every node exactly once → **O(n)**.
MD,
            'complexity_md' => "All traversals: **O(n)** time. Space: O(h) for DFS recursion, O(width) for BFS queue.",
            'real_world_md' => "Level-order powers **shortest-path in unweighted trees** and **printing a tree by levels**; postorder powers **expression evaluation**.",
            'code' => [
                ['language' => 'python', 'label' => 'Inorder + level-order', 'code' => <<<'CODE'
def inorder(root, out):
    if not root: return
    inorder(root.left, out)
    out.append(root.val)
    inorder(root.right, out)

from collections import deque
def level_order(root):
    res, q = [], deque([root] if root else [])
    while q:
        node = q.popleft()
        res.append(node.val)
        if node.left:  q.append(node.left)
        if node.right: q.append(node.right)
    return res
CODE],
                ['language' => 'php', 'label' => 'Inorder + level-order', 'code' => <<<'CODE'
<?php
function inorder(?TreeNode $root, array &$out): void {
    if (!$root) return;
    inorder($root->left, $out);
    $out[] = $root->val;
    inorder($root->right, $out);
}
function levelOrder(?TreeNode $root): array {
    $res = []; $q = $root ? [$root] : [];
    while ($q) {
        $node = array_shift($q);
        $res[] = $node->val;
        if ($node->left)  $q[] = $node->left;
        if ($node->right) $q[] = $node->right;
    }
    return $res;
}
CODE],
                ['language' => 'java', 'label' => 'Level-order (BFS)', 'code' => <<<'CODE'
List<Integer> levelOrder(TreeNode root){
    List<Integer> res = new ArrayList<>();
    if(root == null) return res;
    Queue<TreeNode> q = new LinkedList<>(); q.add(root);
    while(!q.isEmpty()){
        TreeNode n = q.poll();
        res.add(n.val);
        if(n.left != null) q.add(n.left);
        if(n.right != null) q.add(n.right);
    }
    return res;
}
CODE],
                ['language' => 'cpp', 'label' => 'Level-order (BFS)', 'code' => <<<'CODE'
vector<int> levelOrder(TreeNode* root){
    vector<int> res; if(!root) return res;
    queue<TreeNode*> q; q.push(root);
    while(!q.empty()){
        TreeNode* n = q.front(); q.pop();
        res.push_back(n->val);
        if(n->left) q.push(n->left);
        if(n->right) q.push(n->right);
    }
    return res;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What is the difference between tree height and depth?',
         'answer_md' => '**Depth** of a node is the number of edges from the root to that node. **Height** of a node is the longest path from it down to a leaf; tree height is the root’s height.',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'easy',
         'question' => 'How do you compute the height of a binary tree?',
         'answer_md' => 'Recursively: `height = 1 + max(height(left), height(right))`, with empty subtree height 0. O(n).',
         'companies' => ['Adobe']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Print a binary tree level by level.',
         'answer_md' => 'BFS with a queue. To group by level, process the queue in batches of its current size each iteration.',
         'companies' => ['Google', 'Amazon', 'Meta']],
    ],

    'problems' => [
        ['title' => 'Maximum Depth of Binary Tree', 'slug' => 'trees-max-depth', 'difficulty' => 'easy',
         'statement_md' => "Given the root of a binary tree, return its maximum depth (number of nodes on the longest root-to-leaf path).",
         'examples_md' => "```\n    3\n   / \\\n  9  20\n     / \\\n    15  7   ->  depth = 3\n```",
         'solutions' => [
            'python' => ['code' => "def max_depth(root):\n    if not root:\n        return 0\n    return 1 + max(max_depth(root.left), max_depth(root.right))", 'explanation_md' => 'Post-order recursion: a node’s depth is 1 plus the deeper child. O(n).'],
            'php' => ['code' => "<?php\nfunction maxDepth(?TreeNode \$root): int {\n    if (!\$root) return 0;\n    return 1 + max(maxDepth(\$root->left), maxDepth(\$root->right));\n}", 'explanation_md' => ''],
            'java' => ['code' => "int maxDepth(TreeNode root){\n    if(root == null) return 0;\n    return 1 + Math.max(maxDepth(root.left), maxDepth(root.right));\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "int maxDepth(TreeNode* root){\n    if(!root) return 0;\n    return 1 + max(maxDepth(root->left), maxDepth(root->right));\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Trees Quiz',
        'questions' => [
            ['question' => 'Which traversal of a BST yields sorted order?',
             'options' => [['text' => 'Inorder', 'correct' => true], ['text' => 'Preorder', 'correct' => false], ['text' => 'Postorder', 'correct' => false], ['text' => 'Level-order', 'correct' => false]]],
            ['question' => 'Level-order traversal uses which structure?',
             'options' => [['text' => 'Queue', 'correct' => true], ['text' => 'Stack', 'correct' => false], ['text' => 'Hash map', 'correct' => false], ['text' => 'Heap', 'correct' => false]]],
            ['question' => 'Time complexity to traverse all nodes of a tree:',
             'options' => [['text' => 'O(n)', 'correct' => true], ['text' => 'O(log n)', 'correct' => false], ['text' => 'O(n²)', 'correct' => false], ['text' => 'O(1)', 'correct' => false]]],
        ],
    ],
];
