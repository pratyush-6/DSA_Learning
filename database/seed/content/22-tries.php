<?php
return [
    'title'       => 'Tries',
    'slug'        => 'tries',
    'level'       => 'advanced',
    'icon'        => 'bi-diagram-3-fill',
    'sort_order'  => 22,
    'description' => 'Prefix trees for fast string search, autocomplete, and dictionary lookups.',

    'topics' => [
        [
            'title'   => 'Trie (Prefix Tree)',
            'slug'    => 'trie-basics',
            'summary' => 'Each node represents a character; paths spell words.',
            'theory_md' => <<<MD
A **trie** (prefix tree) stores strings as paths from the root. Each node holds links
to child nodes keyed by character, plus a flag marking the **end of a word**.

Why it’s powerful:
- **Search / insert** a word of length L: **O(L)** — independent of how many words are stored.
- **Prefix queries** ("all words starting with 'pre'") are natural and fast.
- Shared prefixes are stored once, saving space for large dictionaries.

```
insert("cat"), insert("car")
        (root)
          c
          a
        /   \
       t*    r*      (* = end of word)
```
MD,
            'complexity_md' => "insert / search / startsWith: **O(L)** where L = word length. Space: O(total characters × alphabet) worst case; less with shared prefixes.",
            'real_world_md' => <<<MD
- **Autocomplete / typeahead** suggestions.
- **Spell checkers** and **dictionary** lookups.
- **IP routing** (longest-prefix match) and **T9** text entry.
- **Search engines** for prefix matching.
MD,
            'code' => [
                ['language' => 'python', 'label' => 'Trie insert/search', 'code' => <<<'CODE'
class Trie:
    def __init__(self):
        self.root = {}
    def insert(self, word):
        node = self.root
        for ch in word:
            node = node.setdefault(ch, {})
        node['$'] = True            # end marker
    def search(self, word):
        node = self.root
        for ch in word:
            if ch not in node: return False
            node = node[ch]
        return '$' in node
    def starts_with(self, prefix):
        node = self.root
        for ch in prefix:
            if ch not in node: return False
            node = node[ch]
        return True
CODE],
                ['language' => 'php', 'label' => 'Trie insert/search', 'code' => <<<'CODE'
<?php
class TrieNode {
    public array $children = [];
    public bool $isEnd = false;
}
class Trie {
    private TrieNode $root;
    public function __construct() { $this->root = new TrieNode(); }
    public function insert(string $word): void {
        $node = $this->root;
        for ($i = 0; $i < strlen($word); $i++) {
            $c = $word[$i];
            $node->children[$c] ??= new TrieNode();
            $node = $node->children[$c];
        }
        $node->isEnd = true;
    }
    public function search(string $word): bool {
        $node = $this->root;
        for ($i = 0; $i < strlen($word); $i++) {
            if (!isset($node->children[$word[$i]])) return false;
            $node = $node->children[$word[$i]];
        }
        return $node->isEnd;
    }
}
CODE],
                ['language' => 'java', 'label' => 'Trie insert/search', 'code' => <<<'CODE'
class Trie {
    static class Node { Node[] ch = new Node[26]; boolean end; }
    Node root = new Node();
    void insert(String w){
        Node n = root;
        for(char c : w.toCharArray()){
            int i = c - 'a';
            if(n.ch[i] == null) n.ch[i] = new Node();
            n = n.ch[i];
        }
        n.end = true;
    }
    boolean search(String w){
        Node n = root;
        for(char c : w.toCharArray()){
            int i = c - 'a';
            if(n.ch[i] == null) return false;
            n = n.ch[i];
        }
        return n.end;
    }
}
CODE],
                ['language' => 'cpp', 'label' => 'Trie insert/search', 'code' => <<<'CODE'
struct Node { Node* ch[26] = {}; bool end = false; };
struct Trie {
    Node* root = new Node();
    void insert(const string& w){
        Node* n = root;
        for(char c : w){
            int i = c - 'a';
            if(!n->ch[i]) n->ch[i] = new Node();
            n = n->ch[i];
        }
        n->end = true;
    }
    bool search(const string& w){
        Node* n = root;
        for(char c : w){
            int i = c - 'a';
            if(!n->ch[i]) return false;
            n = n->ch[i];
        }
        return n->end;
    }
};
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'Why use a trie instead of a hash set for words?',
         'answer_md' => 'A trie supports **prefix queries** (autocomplete, longest-prefix match) in O(prefix length) and shares common prefixes to save space. A hash set gives O(1) exact lookups but cannot efficiently answer "all words with prefix X".',
         'companies' => ['Amazon', 'Google', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Implement an autocomplete that returns words for a given prefix.',
         'answer_md' => 'Walk the trie to the prefix node, then DFS the subtree collecting all words that end below it.',
         'companies' => ['Amazon', 'Flipkart']],
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What is the time complexity to insert a word of length L into a trie?',
         'answer_md' => 'O(L) — one node step per character, independent of the number of stored words.',
         'companies' => ['Adobe']],
    ],

    'problems' => [
        ['title' => 'Implement Trie (Prefix Tree)', 'slug' => 'tries-implement', 'difficulty' => 'medium',
         'statement_md' => "Implement a trie with `insert(word)`, `search(word)` (exact), and `startsWith(prefix)`.",
         'examples_md' => "```\ninsert(\"apple\"); search(\"apple\") -> true; search(\"app\") -> false; startsWith(\"app\") -> true\n```",
         'solutions' => [
            'python' => ['code' => "class Trie:\n    def __init__(self):\n        self.root = {}\n    def insert(self, word):\n        node = self.root\n        for ch in word:\n            node = node.setdefault(ch, {})\n        node['$'] = True\n    def search(self, word):\n        node = self._walk(word)\n        return node is not None and '$' in node\n    def startsWith(self, prefix):\n        return self._walk(prefix) is not None\n    def _walk(self, s):\n        node = self.root\n        for ch in s:\n            if ch not in node: return None\n            node = node[ch]\n        return node", 'explanation_md' => 'Nested dicts as nodes; `$` marks word ends. Each op is O(L).'],
            'php' => ['code' => "<?php\nclass TrieNode { public array \$ch = []; public bool \$end = false; }\nclass Trie {\n    private TrieNode \$root;\n    function __construct(){ \$this->root = new TrieNode(); }\n    function insert(string \$w): void {\n        \$n = \$this->root;\n        for (\$i=0;\$i<strlen(\$w);\$i++){ \$n->ch[\$w[\$i]] ??= new TrieNode(); \$n = \$n->ch[\$w[\$i]]; }\n        \$n->end = true;\n    }\n    private function walk(string \$s): ?TrieNode {\n        \$n = \$this->root;\n        for (\$i=0;\$i<strlen(\$s);\$i++){ if(!isset(\$n->ch[\$s[\$i]])) return null; \$n = \$n->ch[\$s[\$i]]; }\n        return \$n;\n    }\n    function search(string \$w): bool { \$n = \$this->walk(\$w); return \$n && \$n->end; }\n    function startsWith(string \$p): bool { return \$this->walk(\$p) !== null; }\n}", 'explanation_md' => ''],
            'java' => ['code' => "class Trie {\n    static class Node { Node[] ch = new Node[26]; boolean end; }\n    Node root = new Node();\n    public void insert(String w){\n        Node n = root;\n        for(char c: w.toCharArray()){ int i=c-'a'; if(n.ch[i]==null) n.ch[i]=new Node(); n=n.ch[i]; }\n        n.end = true;\n    }\n    Node walk(String s){\n        Node n = root;\n        for(char c: s.toCharArray()){ int i=c-'a'; if(n.ch[i]==null) return null; n=n.ch[i]; }\n        return n;\n    }\n    public boolean search(String w){ Node n=walk(w); return n!=null && n.end; }\n    public boolean startsWith(String p){ return walk(p)!=null; }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "struct Node { Node* ch[26]={}; bool end=false; };\nclass Trie {\n    Node* root = new Node();\n    Node* walk(const string& s){\n        Node* n=root;\n        for(char c: s){ int i=c-'a'; if(!n->ch[i]) return nullptr; n=n->ch[i]; }\n        return n;\n    }\npublic:\n    void insert(string w){\n        Node* n=root;\n        for(char c: w){ int i=c-'a'; if(!n->ch[i]) n->ch[i]=new Node(); n=n->ch[i]; }\n        n->end=true;\n    }\n    bool search(string w){ Node* n=walk(w); return n && n->end; }\n    bool startsWith(string p){ return walk(p)!=nullptr; }\n};", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Tries Quiz',
        'questions' => [
            ['question' => 'Searching a word of length L in a trie costs:',
             'options' => [['text' => 'O(L)', 'correct' => true], ['text' => 'O(number of words)', 'correct' => false], ['text' => 'O(L²)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false]]],
            ['question' => 'Tries are especially good for:',
             'options' => [['text' => 'Prefix / autocomplete queries', 'correct' => true], ['text' => 'Sorting numbers', 'correct' => false], ['text' => 'Shortest paths', 'correct' => false], ['text' => 'Matrix multiplication', 'correct' => false]]],
            ['question' => 'A node flag in a trie typically marks:',
             'options' => [['text' => 'The end of a valid word', 'correct' => true], ['text' => 'The root only', 'correct' => false], ['text' => 'A deleted node', 'correct' => false], ['text' => 'A duplicate', 'correct' => false]]],
        ],
    ],
];
