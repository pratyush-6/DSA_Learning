<?php
return [
    'title'       => 'Strings',
    'slug'        => 'strings',
    'level'       => 'beginner',
    'icon'        => 'bi-fonts',
    'sort_order'  => 4,
    'description' => 'Sequences of characters and the classic patterns used to manipulate them.',

    'topics' => [
        [
            'title'   => 'String Basics & Immutability',
            'slug'    => 'string-basics',
            'summary' => 'How strings are stored and why mutability matters for performance.',
            'theory_md' => <<<MD
A **string** is a sequence of characters, essentially an array of characters.

A key concept is **immutability**. In Java and Python, strings are **immutable** —
every "modification" creates a **new** string. Building a string by repeated
concatenation in a loop is therefore O(n²); use a **builder** (StringBuilder / list +
join) for O(n).

In PHP and C++ (`std::string`), strings are **mutable**, so in-place edits are cheap.

Common operations: length, indexing, substring, concatenation, search, split/join,
case conversion, and comparison.
MD,
            'complexity_md' => "- Index a character: O(1)\n- Concatenate (immutable): O(n) per concat → O(n²) in a loop\n- Substring: O(k) for length k\n- Compare two strings: O(min length)",
            'real_world_md' => "- **Text editors & search** (find/replace).\n- **URLs, parsing, and tokenizing** input.\n- **DNA sequences** are strings over {A, C, G, T}.\n- **Validation** (emails, passwords) via pattern matching.",
            'code' => [
                ['language' => 'python', 'label' => 'Efficient building', 'code' => <<<'CODE'
# BAD: O(n^2) because each += creates a new string
s = ""
for ch in "hello":
    s += ch

# GOOD: O(n) using a list + join
parts = []
for ch in "hello":
    parts.append(ch)
s = "".join(parts)
CODE],
                ['language' => 'java', 'label' => 'Efficient building', 'code' => <<<'CODE'
// Use StringBuilder for O(n) building
StringBuilder sb = new StringBuilder();
for (char c : "hello".toCharArray()) sb.append(c);
String s = sb.toString();
CODE],
                ['language' => 'php', 'label' => 'Mutable, cheap concat', 'code' => <<<'CODE'
<?php
$s = "hello";
echo strlen($s);          // 5
echo $s[0];               // h
echo strtoupper($s);      // HELLO
echo substr($s, 1, 3);    // ell
$s .= " world";           // concatenation
CODE],
                ['language' => 'cpp', 'label' => 'Mutable std::string', 'code' => <<<'CODE'
#include <string>
using namespace std;
string s = "hello";
s += " world";           // in-place append
char c = s[0];           // 'h'
string sub = s.substr(1, 3); // "ell"
CODE],
            ],
        ],
        [
            'title'   => 'Common String Patterns',
            'slug'    => 'string-patterns',
            'summary' => 'Reversal, palindrome check, and frequency counting.',
            'theory_md' => <<<MD
A few patterns solve a huge fraction of string problems:

- **Two pointers** from both ends → reversal, palindrome check.
- **Frequency map** (hash map of char → count) → anagrams, first unique char.
- **Sliding window** → longest substring problems (covered later).

A **palindrome** reads the same forwards and backwards (e.g., `level`). Check it with
two pointers moving inward.
MD,
            'complexity_md' => "Reversal and palindrome check: **O(n)** time, O(1) extra space (two pointers). Frequency counting: O(n) time, O(k) space for k distinct characters.",
            'real_world_md' => "Anagram and frequency techniques power **spell-checkers**, **plagiarism detection**, and **search ranking**.",
            'code' => [
                ['language' => 'python', 'label' => 'Palindrome + anagram', 'code' => <<<'CODE'
def is_palindrome(s):
    i, j = 0, len(s) - 1
    while i < j:
        if s[i] != s[j]:
            return False
        i += 1; j -= 1
    return True

def is_anagram(a, b):
    from collections import Counter
    return Counter(a) == Counter(b)
CODE],
                ['language' => 'php', 'label' => 'Palindrome + anagram', 'code' => <<<'CODE'
<?php
function isPalindrome(string $s): bool {
    $i = 0; $j = strlen($s) - 1;
    while ($i < $j) {
        if ($s[$i] !== $s[$j]) return false;
        $i++; $j--;
    }
    return true;
}
function isAnagram(string $a, string $b): bool {
    $x = str_split($a); $y = str_split($b);
    sort($x); sort($y);
    return $x === $y;
}
CODE],
                ['language' => 'java', 'label' => 'Palindrome', 'code' => <<<'CODE'
boolean isPalindrome(String s) {
    int i = 0, j = s.length() - 1;
    while (i < j) {
        if (s.charAt(i) != s.charAt(j)) return false;
        i++; j--;
    }
    return true;
}
CODE],
                ['language' => 'cpp', 'label' => 'Palindrome', 'code' => <<<'CODE'
bool isPalindrome(const string& s){
    int i=0, j=s.size()-1;
    while(i<j){ if(s[i]!=s[j]) return false; i++; j--; }
    return true;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'Why is building a string by repeated concatenation in a loop slow in Java/Python?',
         'answer_md' => 'Strings are **immutable**, so each concatenation copies the whole string to a new one → O(n) per step, O(n²) overall. Use `StringBuilder` (Java) or a list + `"".join()` (Python) for O(n).',
         'companies' => ['Amazon', 'Microsoft']],
        ['type' => 'coding', 'difficulty' => 'easy',
         'question' => 'Check whether two strings are anagrams of each other.',
         'answer_md' => 'Compare character frequency counts (or sort both). O(n) with a hash map.',
         'companies' => ['Google', 'Uber']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Find the first non-repeating character in a string.',
         'answer_md' => 'Count frequencies in one pass, then scan again returning the first character with count 1. O(n) time.',
         'companies' => ['Amazon', 'Flipkart']],
    ],

    'problems' => [
        ['title' => 'Valid Palindrome', 'slug' => 'strings-valid-palindrome', 'difficulty' => 'easy',
         'statement_md' => "Given a string, determine if it is a palindrome considering only alphanumeric characters and ignoring case.",
         'examples_md' => "```\n\"A man, a plan, a canal: Panama\" -> true\n\"race a car\" -> false\n```",
         'constraints_md' => "- 1 ≤ length ≤ 2·10^5",
         'solutions' => [
            'python' => ['code' => "def is_palindrome(s):\n    f = [c.lower() for c in s if c.isalnum()]\n    return f == f[::-1]", 'explanation_md' => 'Filter to alphanumerics, lowercase, then compare to its reverse. O(n).'],
            'php' => ['code' => "<?php\nfunction isPalindrome(string \$s): bool {\n    \$f = preg_replace('/[^a-z0-9]/', '', strtolower(\$s));\n    return \$f === strrev(\$f);\n}", 'explanation_md' => 'Normalize then compare with strrev.'],
            'java' => ['code' => "boolean isPalindrome(String s){\n    String f = s.toLowerCase().replaceAll(\"[^a-z0-9]\", \"\");\n    return f.equals(new StringBuilder(f).reverse().toString());\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "bool isPalindrome(string s){\n    string f;\n    for(char c: s) if(isalnum(c)) f += tolower(c);\n    int i=0, j=f.size()-1;\n    while(i<j) if(f[i++]!=f[j--]) return false;\n    return true;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Strings Quiz',
        'questions' => [
            ['question' => 'In Java and Python, strings are:',
             'options' => [['text' => 'Immutable', 'correct' => true], ['text' => 'Always mutable', 'correct' => false], ['text' => 'Stored as linked lists', 'correct' => false], ['text' => 'Limited to 255 characters', 'correct' => false]]],
            ['question' => 'The two-pointer palindrome check runs in:',
             'options' => [['text' => 'O(n) time, O(1) space', 'correct' => true], ['text' => 'O(n²) time', 'correct' => false], ['text' => 'O(log n) time', 'correct' => false], ['text' => 'O(n) space always', 'correct' => false]]],
            ['question' => 'Best way to detect anagrams of two strings?',
             'options' => [['text' => 'Compare character frequency counts', 'correct' => true], ['text' => 'Compare their lengths only', 'correct' => false], ['text' => 'Reverse one and compare', 'correct' => false], ['text' => 'Hash the whole string once', 'correct' => false]]],
        ],
    ],
];
