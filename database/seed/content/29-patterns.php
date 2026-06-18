<?php
/**
 * Miscellaneous — Pattern Printing problems.
 * Self-contained pattern problems with full solutions in all 4 languages.
 */
return [
    'title'       => 'Pattern Problems (Miscellaneous)',
    'slug'        => 'pattern-problems',
    'level'       => 'beginner',
    'icon'        => 'bi-asterisk',
    'sort_order'  => 29,
    'description' => 'Nested-loop star and number patterns — the best way to master loops.',

    'topics' => [
        [
            'title'   => 'Pattern Printing with Nested Loops',
            'slug'    => 'pattern-printing-basics',
            'summary' => 'How to reason about rows, columns, spaces, and symbols.',
            'theory_md' => <<<MD
**Pattern problems** train your control over **nested loops** — the single most common
beginner interview warm-up. The recipe is always the same:

1. The **outer loop** controls the **rows**.
2. One or more **inner loops** control what is printed in each row: **spaces** first
   (for alignment), then **symbols** (`*` or numbers).
3. Find the relationship between the **row index** and the **count** of spaces/symbols.

For example, a left-aligned triangle of height `n`:
```
*
* *
* * *
* * * *
```
Row `i` (1-based) prints `i` stars. So: outer loop `i = 1..n`, inner loop `j = 1..i`
prints a star.

A centered pyramid adds leading spaces: row `i` prints `n - i` spaces then
`2*i - 1` stars.

> Tip: dry-run the first 3 rows on paper to derive the counts before coding.
MD,
            'complexity_md' => "Printing an n-row pattern is **O(n²)** (each of n rows prints up to n characters). Space O(1).",
            'real_world_md' => "Beyond interviews, the same row/column reasoning underlies **text-based UIs**, **ASCII charts**, **report formatting**, and **grid rendering**.",
            'code' => [
                ['language' => 'python', 'label' => 'Pyramid', 'code' => <<<'CODE'
n = 5
for i in range(1, n + 1):
    print(" " * (n - i) + "* " * i)
CODE],
                ['language' => 'php', 'label' => 'Pyramid', 'code' => <<<'CODE'
<?php
$n = 5;
for ($i = 1; $i <= $n; $i++) {
    echo str_repeat(" ", $n - $i) . str_repeat("* ", $i) . "\n";
}
CODE],
                ['language' => 'java', 'label' => 'Pyramid', 'code' => <<<'CODE'
int n = 5;
for (int i = 1; i <= n; i++) {
    for (int s = 0; s < n - i; s++) System.out.print(" ");
    for (int j = 0; j < i; j++) System.out.print("* ");
    System.out.println();
}
CODE],
                ['language' => 'cpp', 'label' => 'Pyramid', 'code' => <<<'CODE'
int n = 5;
for (int i = 1; i <= n; i++) {
    for (int s = 0; s < n - i; s++) cout << " ";
    for (int j = 0; j < i; j++) cout << "* ";
    cout << "\n";
}
CODE],
            ],
        ],
    ],

    'problems' => [
        ['title' => 'Right-Angled Star Triangle', 'slug' => 'pattern-right-triangle', 'difficulty' => 'easy',
         'statement_md' => "Print a left-aligned right-angled triangle of `*` with `n` rows.",
         'examples_md' => "```\nn = 4\n*\n* *\n* * *\n* * * *\n```",
         'solutions' => [
            'python' => ['code' => "def pattern(n):\n    for i in range(1, n + 1):\n        print(\"* \" * i)", 'explanation_md' => 'Row i prints i stars.'],
            'php' => ['code' => "<?php\nfunction pattern(int \$n): void {\n    for (\$i = 1; \$i <= \$n; \$i++) echo str_repeat(\"* \", \$i) . \"\\n\";\n}", 'explanation_md' => ''],
            'java' => ['code' => "void pattern(int n){\n    for(int i=1;i<=n;i++){\n        for(int j=0;j<i;j++) System.out.print(\"* \");\n        System.out.println();\n    }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void pattern(int n){\n    for(int i=1;i<=n;i++){\n        for(int j=0;j<i;j++) cout << \"* \";\n        cout << \"\\n\";\n    }\n}", 'explanation_md' => ''],
         ]],
        ['title' => 'Inverted Star Triangle', 'slug' => 'pattern-inverted-triangle', 'difficulty' => 'easy',
         'statement_md' => "Print an inverted left-aligned triangle of `*` with `n` rows.",
         'examples_md' => "```\nn = 4\n* * * *\n* * *\n* *\n*\n```",
         'solutions' => [
            'python' => ['code' => "def pattern(n):\n    for i in range(n, 0, -1):\n        print(\"* \" * i)", 'explanation_md' => 'Count down: row i prints i stars.'],
            'php' => ['code' => "<?php\nfunction pattern(int \$n): void {\n    for (\$i = \$n; \$i >= 1; \$i--) echo str_repeat(\"* \", \$i) . \"\\n\";\n}", 'explanation_md' => ''],
            'java' => ['code' => "void pattern(int n){\n    for(int i=n;i>=1;i--){\n        for(int j=0;j<i;j++) System.out.print(\"* \");\n        System.out.println();\n    }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void pattern(int n){\n    for(int i=n;i>=1;i--){\n        for(int j=0;j<i;j++) cout << \"* \";\n        cout << \"\\n\";\n    }\n}", 'explanation_md' => ''],
         ]],
        ['title' => 'Star Pyramid', 'slug' => 'pattern-pyramid', 'difficulty' => 'easy',
         'statement_md' => "Print a centered pyramid of `*` with `n` rows. Row i has `n-i` leading spaces and `2*i-1` stars.",
         'examples_md' => "```\nn = 4\n   *\n  ***\n *****\n*******\n```",
         'solutions' => [
            'python' => ['code' => "def pattern(n):\n    for i in range(1, n + 1):\n        print(\" \" * (n - i) + \"*\" * (2 * i - 1))", 'explanation_md' => 'Leading spaces shrink while stars grow by 2 each row.'],
            'php' => ['code' => "<?php\nfunction pattern(int \$n): void {\n    for (\$i = 1; \$i <= \$n; \$i++)\n        echo str_repeat(\" \", \$n - \$i) . str_repeat(\"*\", 2*\$i - 1) . \"\\n\";\n}", 'explanation_md' => ''],
            'java' => ['code' => "void pattern(int n){\n    for(int i=1;i<=n;i++){\n        for(int s=0;s<n-i;s++) System.out.print(\" \");\n        for(int j=0;j<2*i-1;j++) System.out.print(\"*\");\n        System.out.println();\n    }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void pattern(int n){\n    for(int i=1;i<=n;i++){\n        for(int s=0;s<n-i;s++) cout << \" \";\n        for(int j=0;j<2*i-1;j++) cout << \"*\";\n        cout << \"\\n\";\n    }\n}", 'explanation_md' => ''],
         ]],
        ['title' => 'Number Triangle (Floyd\'s Triangle)', 'slug' => 'pattern-floyd-triangle', 'difficulty' => 'easy',
         'statement_md' => "Print Floyd's triangle: consecutive natural numbers, `i` numbers on row `i`.",
         'examples_md' => "```\nn = 4\n1\n2 3\n4 5 6\n7 8 9 10\n```",
         'solutions' => [
            'python' => ['code' => "def pattern(n):\n    num = 1\n    for i in range(1, n + 1):\n        row = []\n        for j in range(i):\n            row.append(str(num)); num += 1\n        print(\" \".join(row))", 'explanation_md' => 'A running counter increments across all cells.'],
            'php' => ['code' => "<?php\nfunction pattern(int \$n): void {\n    \$num = 1;\n    for (\$i = 1; \$i <= \$n; \$i++) {\n        \$row = [];\n        for (\$j = 0; \$j < \$i; \$j++) { \$row[] = \$num++; }\n        echo implode(\" \", \$row) . \"\\n\";\n    }\n}", 'explanation_md' => ''],
            'java' => ['code' => "void pattern(int n){\n    int num = 1;\n    for(int i=1;i<=n;i++){\n        for(int j=0;j<i;j++) System.out.print((num++) + \" \");\n        System.out.println();\n    }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void pattern(int n){\n    int num = 1;\n    for(int i=1;i<=n;i++){\n        for(int j=0;j<i;j++) cout << num++ << \" \";\n        cout << \"\\n\";\n    }\n}", 'explanation_md' => ''],
         ]],
        ['title' => "Pascal's Triangle", 'slug' => 'pattern-pascals-triangle', 'difficulty' => 'medium',
         'statement_md' => "Print the first `n` rows of Pascal's triangle, where each value is the sum of the two values above it. Row i, col j = C(i, j).",
         'examples_md' => "```\nn = 5\n1\n1 1\n1 2 1\n1 3 3 1\n1 4 6 4 1\n```",
         'solutions' => [
            'python' => ['code' => "def pattern(n):\n    for i in range(n):\n        val = 1\n        row = []\n        for j in range(i + 1):\n            row.append(str(val))\n            val = val * (i - j) // (j + 1)\n        print(\" \".join(row))", 'explanation_md' => 'Each entry is derived from the previous one: C(i,j+1) = C(i,j)*(i-j)/(j+1).'],
            'php' => ['code' => "<?php\nfunction pattern(int \$n): void {\n    for (\$i = 0; \$i < \$n; \$i++) {\n        \$val = 1; \$row = [];\n        for (\$j = 0; \$j <= \$i; \$j++) {\n            \$row[] = \$val;\n            \$val = intdiv(\$val * (\$i - \$j), \$j + 1);\n        }\n        echo implode(\" \", \$row) . \"\\n\";\n    }\n}", 'explanation_md' => ''],
            'java' => ['code' => "void pattern(int n){\n    for(int i=0;i<n;i++){\n        long val = 1;\n        for(int j=0;j<=i;j++){\n            System.out.print(val + \" \");\n            val = val * (i - j) / (j + 1);\n        }\n        System.out.println();\n    }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void pattern(int n){\n    for(int i=0;i<n;i++){\n        long long val = 1;\n        for(int j=0;j<=i;j++){\n            cout << val << \" \";\n            val = val * (i - j) / (j + 1);\n        }\n        cout << \"\\n\";\n    }\n}", 'explanation_md' => ''],
         ]],
        ['title' => 'Diamond Pattern', 'slug' => 'pattern-diamond', 'difficulty' => 'medium',
         'statement_md' => "Print a diamond of `*` for a given height `n` (the upper pyramid of n rows plus an inverted pyramid of n-1 rows).",
         'examples_md' => "```\nn = 3\n  *\n ***\n*****\n ***\n  *\n```",
         'solutions' => [
            'python' => ['code' => "def pattern(n):\n    for i in range(1, n + 1):\n        print(\" \" * (n - i) + \"*\" * (2*i - 1))\n    for i in range(n - 1, 0, -1):\n        print(\" \" * (n - i) + \"*\" * (2*i - 1))", 'explanation_md' => 'Reuse the pyramid logic upward then downward.'],
            'php' => ['code' => "<?php\nfunction pattern(int \$n): void {\n    for (\$i = 1; \$i <= \$n; \$i++) echo str_repeat(\" \", \$n-\$i) . str_repeat(\"*\", 2*\$i-1) . \"\\n\";\n    for (\$i = \$n-1; \$i >= 1; \$i--) echo str_repeat(\" \", \$n-\$i) . str_repeat(\"*\", 2*\$i-1) . \"\\n\";\n}", 'explanation_md' => ''],
            'java' => ['code' => "void pattern(int n){\n    for(int i=1;i<=n;i++){ sp(n-i); st(2*i-1); System.out.println(); }\n    for(int i=n-1;i>=1;i--){ sp(n-i); st(2*i-1); System.out.println(); }\n}\nvoid sp(int k){ for(int i=0;i<k;i++) System.out.print(\" \"); }\nvoid st(int k){ for(int i=0;i<k;i++) System.out.print(\"*\"); }", 'explanation_md' => ''],
            'cpp' => ['code' => "void pattern(int n){\n    for(int i=1;i<=n;i++){ for(int s=0;s<n-i;s++)cout<<\" \"; for(int j=0;j<2*i-1;j++)cout<<\"*\"; cout<<\"\\n\"; }\n    for(int i=n-1;i>=1;i--){ for(int s=0;s<n-i;s++)cout<<\" \"; for(int j=0;j<2*i-1;j++)cout<<\"*\"; cout<<\"\\n\"; }\n}", 'explanation_md' => ''],
         ]],
        ['title' => 'Hollow Square', 'slug' => 'pattern-hollow-square', 'difficulty' => 'easy',
         'statement_md' => "Print an `n x n` square of `*` that is hollow inside (only the border is printed).",
         'examples_md' => "```\nn = 4\n* * * *\n*     *\n*     *\n* * * *\n```",
         'solutions' => [
            'python' => ['code' => "def pattern(n):\n    for i in range(n):\n        row = []\n        for j in range(n):\n            if i == 0 or i == n-1 or j == 0 or j == n-1:\n                row.append('*')\n            else:\n                row.append(' ')\n        print(' '.join(row))", 'explanation_md' => 'Print a star only on the first/last row or column.'],
            'php' => ['code' => "<?php\nfunction pattern(int \$n): void {\n    for (\$i = 0; \$i < \$n; \$i++) {\n        \$row = [];\n        for (\$j = 0; \$j < \$n; \$j++)\n            \$row[] = (\$i==0||\$i==\$n-1||\$j==0||\$j==\$n-1) ? '*' : ' ';\n        echo implode(' ', \$row) . \"\\n\";\n    }\n}", 'explanation_md' => ''],
            'java' => ['code' => "void pattern(int n){\n    for(int i=0;i<n;i++){\n        for(int j=0;j<n;j++)\n            System.out.print((i==0||i==n-1||j==0||j==n-1?\"*\":\" \") + \" \");\n        System.out.println();\n    }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void pattern(int n){\n    for(int i=0;i<n;i++){\n        for(int j=0;j<n;j++)\n            cout << (i==0||i==n-1||j==0||j==n-1?'*':' ') << \" \";\n        cout << \"\\n\";\n    }\n}", 'explanation_md' => ''],
         ]],
        ['title' => 'Butterfly Pattern', 'slug' => 'pattern-butterfly', 'difficulty' => 'medium',
         'statement_md' => "Print a butterfly pattern of `*` of size `n`: two mirrored triangles separated by spaces, top half then bottom half.",
         'examples_md' => "```\nn = 3\n*     *\n**   **\n*******\n**   **\n*     *\n```",
         'solutions' => [
            'python' => ['code' => "def pattern(n):\n    for i in range(1, n + 1):\n        print('*' * i + ' ' * (2 * (n - i)) + '*' * i)\n    for i in range(n, 0, -1):\n        print('*' * i + ' ' * (2 * (n - i)) + '*' * i)", 'explanation_md' => 'Left stars i, middle spaces 2*(n-i), right stars i; mirror for bottom.'],
            'php' => ['code' => "<?php\nfunction pattern(int \$n): void {\n    for (\$i = 1; \$i <= \$n; \$i++)\n        echo str_repeat('*', \$i) . str_repeat(' ', 2*(\$n-\$i)) . str_repeat('*', \$i) . \"\\n\";\n    for (\$i = \$n; \$i >= 1; \$i--)\n        echo str_repeat('*', \$i) . str_repeat(' ', 2*(\$n-\$i)) . str_repeat('*', \$i) . \"\\n\";\n}", 'explanation_md' => ''],
            'java' => ['code' => "void pattern(int n){\n    for(int i=1;i<=n;i++) row(i, n);\n    for(int i=n;i>=1;i--) row(i, n);\n}\nvoid row(int i, int n){\n    for(int j=0;j<i;j++) System.out.print(\"*\");\n    for(int j=0;j<2*(n-i);j++) System.out.print(\" \");\n    for(int j=0;j<i;j++) System.out.print(\"*\");\n    System.out.println();\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void row(int i, int n){\n    for(int j=0;j<i;j++) cout<<\"*\";\n    for(int j=0;j<2*(n-i);j++) cout<<\" \";\n    for(int j=0;j<i;j++) cout<<\"*\";\n    cout<<\"\\n\";\n}\nvoid pattern(int n){\n    for(int i=1;i<=n;i++) row(i,n);\n    for(int i=n;i>=1;i--) row(i,n);\n}", 'explanation_md' => ''],
         ]],
        ['title' => 'Number Pyramid', 'slug' => 'pattern-number-pyramid', 'difficulty' => 'easy',
         'statement_md' => "Print a centered pyramid where row `i` prints numbers `1..i`.",
         'examples_md' => "```\nn = 4\n   1\n  1 2\n 1 2 3\n1 2 3 4\n```",
         'solutions' => [
            'python' => ['code' => "def pattern(n):\n    for i in range(1, n + 1):\n        print(' ' * (n - i) + ' '.join(str(j) for j in range(1, i + 1)))", 'explanation_md' => 'Leading spaces for centering, then numbers 1..i.'],
            'php' => ['code' => "<?php\nfunction pattern(int \$n): void {\n    for (\$i = 1; \$i <= \$n; \$i++) {\n        echo str_repeat(' ', \$n - \$i);\n        for (\$j = 1; \$j <= \$i; \$j++) echo \$j . (\$j < \$i ? ' ' : '');\n        echo \"\\n\";\n    }\n}", 'explanation_md' => ''],
            'java' => ['code' => "void pattern(int n){\n    for(int i=1;i<=n;i++){\n        for(int s=0;s<n-i;s++) System.out.print(\" \");\n        for(int j=1;j<=i;j++) System.out.print(j + \" \");\n        System.out.println();\n    }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void pattern(int n){\n    for(int i=1;i<=n;i++){\n        for(int s=0;s<n-i;s++) cout<<\" \";\n        for(int j=1;j<=i;j++) cout<<j<<\" \";\n        cout<<\"\\n\";\n    }\n}", 'explanation_md' => ''],
         ]],
        ['title' => 'Character Triangle', 'slug' => 'pattern-character-triangle', 'difficulty' => 'easy',
         'statement_md' => "Print a triangle where row `i` repeats the i-th uppercase letter `i` times (A, then BB, then CCC...).",
         'examples_md' => "```\nn = 4\nA\nB B\nC C C\nD D D D\n```",
         'solutions' => [
            'python' => ['code' => "def pattern(n):\n    for i in range(n):\n        ch = chr(ord('A') + i)\n        print(' '.join([ch] * (i + 1)))", 'explanation_md' => 'The character advances per row; print it i+1 times.'],
            'php' => ['code' => "<?php\nfunction pattern(int \$n): void {\n    for (\$i = 0; \$i < \$n; \$i++) {\n        \$ch = chr(ord('A') + \$i);\n        echo implode(' ', array_fill(0, \$i + 1, \$ch)) . \"\\n\";\n    }\n}", 'explanation_md' => ''],
            'java' => ['code' => "void pattern(int n){\n    for(int i=0;i<n;i++){\n        char ch = (char)('A' + i);\n        for(int j=0;j<=i;j++) System.out.print(ch + \" \");\n        System.out.println();\n    }\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "void pattern(int n){\n    for(int i=0;i<n;i++){\n        char ch = 'A' + i;\n        for(int j=0;j<=i;j++) cout << ch << \" \";\n        cout << \"\\n\";\n    }\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Pattern Problems Quiz',
        'questions' => [
            ['question' => 'In most pattern problems, the outer loop controls the:',
             'options' => [['text' => 'Rows', 'correct' => true], ['text' => 'Columns only', 'correct' => false], ['text' => 'Spaces only', 'correct' => false], ['text' => 'Nothing', 'correct' => false]]],
            ['question' => 'A centered pyramid of height n has how many stars on row i?',
             'options' => [['text' => '2*i - 1', 'correct' => true], ['text' => 'i', 'correct' => false], ['text' => 'n - i', 'correct' => false], ['text' => 'n', 'correct' => false]]],
            ['question' => 'Printing an n-row pattern is typically:',
             'options' => [['text' => 'O(n²)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false], ['text' => 'O(1)', 'correct' => false]]],
        ],
    ],
];
