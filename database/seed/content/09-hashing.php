<?php
return [
    'title'       => 'Hashing',
    'slug'        => 'hashing',
    'level'       => 'beginner',
    'icon'        => 'bi-hash',
    'sort_order'  => 9,
    'description' => 'Hash tables / maps for average O(1) insert, delete, and lookup.',

    'topics' => [
        [
            'title'   => 'Hash Tables & Hash Maps',
            'slug'    => 'hash-tables',
            'summary' => 'How a hash function turns keys into array indices.',
            'theory_md' => <<<MD
A **hash table** stores key–value pairs in an array. A **hash function** maps each key
to an index: `index = hash(key) % capacity`. This gives **average O(1)** insert,
lookup, and delete.

When two keys map to the same index, that is a **collision**, resolved by:
- **Chaining:** each bucket holds a linked list/array of entries.
- **Open addressing:** probe for the next free slot.

A good hash function distributes keys uniformly. The **load factor** (entries / buckets)
is kept low (e.g., < 0.75) by **resizing**, which keeps operations near O(1).

In practice you use the built-in map: PHP associative arrays, Python `dict`/`set`,
Java `HashMap`/`HashSet`, C++ `unordered_map`/`unordered_set`.
MD,
            'complexity_md' => "| Operation | Average | Worst |\n|---|---|---|\n| Insert | O(1) | O(n) |\n| Lookup | O(1) | O(n) |\n| Delete | O(1) | O(n) |\n\nWorst case (all collisions) is O(n); rare with a good hash function. Space: O(n).",
            'real_world_md' => <<<MD
- **Database indexes** and **caches** (key → row/value).
- **Deduplication** and **counting frequencies**.
- **Symbol tables** in compilers/interpreters.
- **Sets** for fast membership tests (`is this seen before?`).
MD,
            'code' => [
                ['language' => 'python', 'label' => 'dict & set', 'code' => <<<'CODE'
counts = {}
for ch in "banana":
    counts[ch] = counts.get(ch, 0) + 1
print(counts)          # {'b':1, 'a':3, 'n':2}

seen = set()
seen.add(5)
print(5 in seen)       # True (O(1))
CODE],
                ['language' => 'php', 'label' => 'Associative array', 'code' => <<<'CODE'
<?php
$counts = [];
foreach (str_split("banana") as $ch) {
    $counts[$ch] = ($counts[$ch] ?? 0) + 1;
}
print_r($counts);      // [b=>1, a=>3, n=>2]

$seen = [];
$seen[5] = true;
var_dump(isset($seen[5]));  // O(1) membership
CODE],
                ['language' => 'java', 'label' => 'HashMap & HashSet', 'code' => <<<'CODE'
import java.util.*;
Map<Character,Integer> counts = new HashMap<>();
for (char c : "banana".toCharArray())
    counts.merge(c, 1, Integer::sum);

Set<Integer> seen = new HashSet<>();
seen.add(5);
System.out.println(seen.contains(5));  // O(1)
CODE],
                ['language' => 'cpp', 'label' => 'unordered_map / set', 'code' => <<<'CODE'
#include <unordered_map>
#include <unordered_set>
using namespace std;
unordered_map<char,int> counts;
for (char c : string("banana")) counts[c]++;

unordered_set<int> seen;
seen.insert(5);
bool has = seen.count(5);   // O(1) average
CODE],
            ],
        ],
        [
            'title'   => 'Hashing Patterns for Problems',
            'slug'    => 'hashing-patterns',
            'summary' => 'Frequency maps, seen-sets, and complement lookups.',
            'theory_md' => <<<MD
Three patterns solve a huge share of array/string problems in O(n):

1. **Frequency map** — count occurrences (anagrams, majority element, top-k).
2. **Seen set** — detect duplicates / first repeat in one pass.
3. **Complement lookup** — store what you have seen; check if the needed partner exists
   (Two Sum: store `value → index`, look for `target - value`).

These trade O(n) extra **space** for big **time** savings (O(n²) → O(n)).
MD,
            'complexity_md' => "Each pattern is **O(n)** time and **O(n)** space — a classic time–space trade-off.",
            'real_world_md' => "Complement/seen-set logic powers **fraud detection** (seen this transaction?), **rate limiting**, and **caching**.",
            'code' => [
                ['language' => 'python', 'label' => 'First duplicate', 'code' => <<<'CODE'
def first_duplicate(nums):
    seen = set()
    for n in nums:
        if n in seen:
            return n
        seen.add(n)
    return None
CODE],
                ['language' => 'php', 'label' => 'First duplicate', 'code' => <<<'CODE'
<?php
function firstDuplicate(array $nums) {
    $seen = [];
    foreach ($nums as $n) {
        if (isset($seen[$n])) return $n;
        $seen[$n] = true;
    }
    return null;
}
CODE],
                ['language' => 'java', 'label' => 'First duplicate', 'code' => <<<'CODE'
Integer firstDuplicate(int[] nums) {
    Set<Integer> seen = new HashSet<>();
    for (int n : nums) {
        if (!seen.add(n)) return n;   // add returns false if present
    }
    return null;
}
CODE],
                ['language' => 'cpp', 'label' => 'First duplicate', 'code' => <<<'CODE'
int firstDuplicate(vector<int>& nums){
    unordered_set<int> seen;
    for(int n : nums){
        if(seen.count(n)) return n;
        seen.insert(n);
    }
    return -1;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What is the average and worst-case lookup time of a hash table?',
         'answer_md' => 'Average **O(1)**; worst case **O(n)** when many keys collide into one bucket. A good hash function and resizing keep it near O(1).',
         'companies' => ['Amazon', 'Microsoft', 'Adobe']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'How are collisions handled in a hash table?',
         'answer_md' => 'Two main strategies: **chaining** (each bucket stores a list of entries) and **open addressing** (probe for the next empty slot, e.g., linear/quadratic probing or double hashing).',
         'companies' => ['Google', 'Meta']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Given an array, find if any value appears at least twice.',
         'answer_md' => 'Insert into a hash set as you scan; if a value is already present, return true. O(n) time, O(n) space.',
         'companies' => ['Amazon', 'Flipkart']],
    ],

    'problems' => [
        ['title' => 'Group Anagrams', 'slug' => 'hashing-group-anagrams', 'difficulty' => 'medium',
         'statement_md' => "Given an array of strings, group the anagrams together. Anagrams share the same multiset of characters.",
         'examples_md' => "```\n[\"eat\",\"tea\",\"tan\",\"ate\",\"nat\",\"bat\"]\n-> [[\"eat\",\"tea\",\"ate\"], [\"tan\",\"nat\"], [\"bat\"]]\n```",
         'constraints_md' => "- 1 ≤ words ≤ 10^4",
         'solutions' => [
            'python' => ['code' => "def group_anagrams(strs):\n    groups = {}\n    for s in strs:\n        key = ''.join(sorted(s))\n        groups.setdefault(key, []).append(s)\n    return list(groups.values())", 'explanation_md' => 'The sorted string is the same for all anagrams → use it as a hash-map key. O(n·k log k).'],
            'php' => ['code' => "<?php\nfunction groupAnagrams(array \$strs): array {\n    \$groups = [];\n    foreach (\$strs as \$s) {\n        \$k = str_split(\$s); sort(\$k); \$k = implode('', \$k);\n        \$groups[\$k][] = \$s;\n    }\n    return array_values(\$groups);\n}", 'explanation_md' => ''],
            'java' => ['code' => "List<List<String>> groupAnagrams(String[] strs){\n    Map<String,List<String>> m = new HashMap<>();\n    for(String s: strs){\n        char[] c = s.toCharArray(); Arrays.sort(c);\n        m.computeIfAbsent(new String(c), k -> new ArrayList<>()).add(s);\n    }\n    return new ArrayList<>(m.values());\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "vector<vector<string>> groupAnagrams(vector<string>& strs){\n    unordered_map<string, vector<string>> m;\n    for(auto& s: strs){ string k=s; sort(k.begin(),k.end()); m[k].push_back(s); }\n    vector<vector<string>> res;\n    for(auto& p: m) res.push_back(p.second);\n    return res;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Hashing Quiz',
        'questions' => [
            ['question' => 'Average time complexity of hash table lookup is:',
             'options' => [['text' => 'O(1)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false]]],
            ['question' => 'A collision occurs when:',
             'options' => [['text' => 'Two keys hash to the same index', 'correct' => true], ['text' => 'The table is empty', 'correct' => false], ['text' => 'A key is deleted', 'correct' => false], ['text' => 'The hash function is fast', 'correct' => false]]],
            ['question' => 'Which technique turns Two Sum from O(n²) to O(n)?',
             'options' => [['text' => 'Complement lookup with a hash map', 'correct' => true], ['text' => 'Sorting the array', 'correct' => false], ['text' => 'Using recursion', 'correct' => false], ['text' => 'Binary search', 'correct' => false]]],
        ],
    ],
];
