<?php
return [
    'title'       => 'Binary Search Trees',
    'slug'        => 'binary-search-trees',
    'level'       => 'intermediate',
    'icon'        => 'bi-diagram-2',
    'sort_order'  => 11,
    'description' => 'Ordered binary trees giving O(log n) search, insert, and delete when balanced.',

    'topics' => [
        [
            'title'   => 'BST Property & Operations',
            'slug'    => 'bst-property',
            'summary' => 'Left < node < right, enabling binary search on a tree.',
            'theory_md' => <<<MD
A **Binary Search Tree (BST)** is a binary tree with the **ordering invariant**: for
every node, **all keys in the left subtree are smaller** and **all keys in the right
subtree are larger**.

This lets you search like binary search: compare the target with the current node and
go left or right, halving the search space each step.

- **Search/Insert/Delete:** O(h) where h is height.
- Balanced BST: h ≈ log n → **O(log n)**.
- Degenerate (sorted insert) BST: h = n → **O(n)** (a linked list!).

**Self-balancing** trees (AVL, Red-Black) keep h = O(log n) automatically.
MD,
            'complexity_md' => "| Operation | Balanced | Worst (skewed) |\n|---|---|---|\n| Search | O(log n) | O(n) |\n| Insert | O(log n) | O(n) |\n| Delete | O(log n) | O(n) |\n\n**Inorder traversal gives sorted output.**",
            'real_world_md' => "- **Ordered maps/sets** (`std::map`, Java `TreeMap`) are balanced BSTs.\n- **Database range queries** and **interval scheduling**.\n- Anything needing **sorted data with fast inserts/lookups**.",
            'code' => [
                ['language' => 'python', 'label' => 'Search & insert', 'code' => <<<'CODE'
def search(root, key):
    while root and root.val != key:
        root = root.left if key < root.val else root.right
    return root

def insert(root, key):
    if not root:
        return TreeNode(key)
    if key < root.val:
        root.left = insert(root.left, key)
    else:
        root.right = insert(root.right, key)
    return root
CODE],
                ['language' => 'php', 'label' => 'Search & insert', 'code' => <<<'CODE'
<?php
function search(?TreeNode $root, int $key): ?TreeNode {
    while ($root && $root->val !== $key) {
        $root = $key < $root->val ? $root->left : $root->right;
    }
    return $root;
}
function insert(?TreeNode $root, int $key): TreeNode {
    if (!$root) return new TreeNode($key);
    if ($key < $root->val) $root->left = insert($root->left, $key);
    else                   $root->right = insert($root->right, $key);
    return $root;
}
CODE],
                ['language' => 'java', 'label' => 'Search & insert', 'code' => <<<'CODE'
TreeNode search(TreeNode root, int key){
    while(root != null && root.val != key)
        root = key < root.val ? root.left : root.right;
    return root;
}
TreeNode insert(TreeNode root, int key){
    if(root == null) return new TreeNode(key);
    if(key < root.val) root.left = insert(root.left, key);
    else root.right = insert(root.right, key);
    return root;
}
CODE],
                ['language' => 'cpp', 'label' => 'Search & insert', 'code' => <<<'CODE'
TreeNode* search(TreeNode* root, int key){
    while(root && root->val != key)
        root = key < root->val ? root->left : root->right;
    return root;
}
TreeNode* insert(TreeNode* root, int key){
    if(!root) return new TreeNode(key);
    if(key < root->val) root->left = insert(root->left, key);
    else root->right = insert(root->right, key);
    return root;
}
CODE],
            ],
        ],
        [
            'title'   => 'Validating a BST',
            'slug'    => 'validate-bst',
            'summary' => 'A common interview trap: checking node-vs-children is not enough.',
            'theory_md' => <<<MD
A frequent mistake: checking only that `left < node < right` for each node. That fails
for deeper violations. The correct check enforces a **valid range** for every node:
each node must lie strictly within `(low, high)`, and recursion tightens the bounds.

Equivalently, an **inorder traversal of a valid BST is strictly increasing** — verify
that property in one pass.
MD,
            'complexity_md' => "Validation: **O(n)** time, O(h) space.",
            'real_world_md' => "Range validation is the same idea behind verifying **sorted invariants** in indexes and **interval trees**.",
            'code' => [
                ['language' => 'python', 'label' => 'Range validation', 'code' => <<<'CODE'
def is_valid_bst(root, low=float('-inf'), high=float('inf')):
    if not root:
        return True
    if not (low < root.val < high):
        return False
    return (is_valid_bst(root.left, low, root.val) and
            is_valid_bst(root.right, root.val, high))
CODE],
                ['language' => 'php', 'label' => 'Range validation', 'code' => <<<'CODE'
<?php
function isValidBST(?TreeNode $root, float $low = -INF, float $high = INF): bool {
    if (!$root) return true;
    if (!($low < $root->val && $root->val < $high)) return false;
    return isValidBST($root->left, $low, $root->val)
        && isValidBST($root->right, $root->val, $high);
}
CODE],
                ['language' => 'java', 'label' => 'Range validation', 'code' => <<<'CODE'
boolean isValidBST(TreeNode root, long low, long high){
    if(root == null) return true;
    if(root.val <= low || root.val >= high) return false;
    return isValidBST(root.left, low, root.val)
        && isValidBST(root.right, root.val, high);
}
// call: isValidBST(root, Long.MIN_VALUE, Long.MAX_VALUE)
CODE],
                ['language' => 'cpp', 'label' => 'Range validation', 'code' => <<<'CODE'
bool isValidBST(TreeNode* root, long low = LONG_MIN, long high = LONG_MAX){
    if(!root) return true;
    if(root->val <= low || root->val >= high) return false;
    return isValidBST(root->left, low, root->val)
        && isValidBST(root->right, root->val, high);
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What is the BST ordering property?',
         'answer_md' => 'For every node, all keys in its left subtree are smaller and all keys in its right subtree are larger. This enables O(log n) search in a balanced tree.',
         'companies' => ['Amazon', 'Adobe']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'When does a BST degrade to O(n) operations, and how is it prevented?',
         'answer_md' => 'When insertions arrive in sorted order it becomes a skewed chain (height n). **Self-balancing** trees (AVL, Red-Black) rebalance on insert/delete to keep height O(log n).',
         'companies' => ['Google', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Find the k-th smallest element in a BST.',
         'answer_md' => 'Do an inorder traversal (which yields sorted order) and stop at the k-th visited node. O(h + k).',
         'companies' => ['Amazon', 'Meta', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Validate Binary Search Tree', 'slug' => 'bst-validate', 'difficulty' => 'medium',
         'statement_md' => "Given the root of a binary tree, determine if it is a valid BST.",
         'examples_md' => "```\n  2\n / \\\n1   3   -> true\n\n  5\n / \\\n1   4      (4 < 5 but in right subtree) -> false\n```",
         'solutions' => [
            'python' => ['code' => "def is_valid_bst(root, low=float('-inf'), high=float('inf')):\n    if not root:\n        return True\n    if not (low < root.val < high):\n        return False\n    return (is_valid_bst(root.left, low, root.val) and\n            is_valid_bst(root.right, root.val, high))", 'explanation_md' => 'Carry a valid (low, high) range down the tree. O(n).'],
            'php' => ['code' => "<?php\nfunction isValidBST(?TreeNode \$root, float \$low=-INF, float \$high=INF): bool {\n    if (!\$root) return true;\n    if (!(\$low < \$root->val && \$root->val < \$high)) return false;\n    return isValidBST(\$root->left, \$low, \$root->val)\n        && isValidBST(\$root->right, \$root->val, \$high);\n}", 'explanation_md' => ''],
            'java' => ['code' => "boolean isValidBST(TreeNode root){ return helper(root, Long.MIN_VALUE, Long.MAX_VALUE); }\nboolean helper(TreeNode n, long lo, long hi){\n    if(n == null) return true;\n    if(n.val <= lo || n.val >= hi) return false;\n    return helper(n.left, lo, n.val) && helper(n.right, n.val, hi);\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "bool isValidBST(TreeNode* root, long lo=LONG_MIN, long hi=LONG_MAX){\n    if(!root) return true;\n    if(root->val <= lo || root->val >= hi) return false;\n    return isValidBST(root->left, lo, root->val)\n        && isValidBST(root->right, root->val, hi);\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'BST Quiz',
        'questions' => [
            ['question' => 'Search in a balanced BST is:',
             'options' => [['text' => 'O(log n)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(1)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false]]],
            ['question' => 'Inserting already-sorted data into a plain BST produces:',
             'options' => [['text' => 'A skewed tree with O(n) operations', 'correct' => true], ['text' => 'A perfectly balanced tree', 'correct' => false], ['text' => 'A heap', 'correct' => false], ['text' => 'A hash table', 'correct' => false]]],
            ['question' => 'Inorder traversal of a valid BST is:',
             'options' => [['text' => 'Strictly increasing', 'correct' => true], ['text' => 'Strictly decreasing', 'correct' => false], ['text' => 'Random', 'correct' => false], ['text' => 'Level by level', 'correct' => false]]],
        ],
    ],
];
