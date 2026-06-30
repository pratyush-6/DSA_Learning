<?php
/**
 * Coding exercises for the built-in compiler.
 * Each exercise reads from STDIN and writes to STDOUT so it can be auto-graded
 * in any language. Starters are scaffolds (read input + TODO); tests define the
 * predefined cases (some visible "sample", some hidden).
 */
return [
    [
        'title' => 'Sum of Two Numbers', 'slug' => 'ex-sum-two-numbers', 'difficulty' => 'easy',
        'statement_md' => "Read two integers `a` and `b` on a single line (space-separated) and print their **sum**.",
        'examples_md' => "```\nInput:  2 3\nOutput: 5\n```",
        'constraints_md' => "- −10^9 ≤ a, b ≤ 10^9",
        'starters' => [
            'php' => "<?php\n// Read two integers separated by a space, then print their sum.\n\$parts = explode(' ', trim(fgets(STDIN)));\n\$a = (int) \$parts[0];\n\$b = (int) \$parts[1];\n\n// TODO: print the sum of \$a and \$b\n",
            'python' => "a, b = map(int, input().split())\n\n# TODO: print the sum of a and b\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main() {\n    long long a, b;\n    cin >> a >> b;\n    // TODO: print a + b\n    return 0;\n}\n",
            'java' => "import java.util.*;\npublic class Main {\n    public static void main(String[] args) {\n        Scanner sc = new Scanner(System.in);\n        long a = sc.nextLong(), b = sc.nextLong();\n        // TODO: print a + b\n    }\n}\n",
        ],
        'tests' => [
            ['stdin' => "2 3",           'expected' => "5",          'sample' => true],
            ['stdin' => "100 250",       'expected' => "350",        'sample' => true],
            ['stdin' => "-5 5",          'expected' => "0"],
            ['stdin' => "1000000000 1000000000", 'expected' => "2000000000"],
        ],
    ],

    [
        'title' => 'Reverse a String', 'slug' => 'ex-reverse-string', 'difficulty' => 'easy',
        'statement_md' => "Read a single line of text and print it **reversed**.",
        'examples_md' => "```\nInput:  hello\nOutput: olleh\n```",
        'constraints_md' => "- The line contains 1–1000 visible characters.",
        'starters' => [
            'php' => "<?php\n\$s = rtrim(fgets(STDIN), \"\\r\\n\");\n\n// TODO: print the reverse of \$s\n",
            'python' => "s = input()\n\n# TODO: print the reverse of s\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main() {\n    string s;\n    getline(cin, s);\n    // TODO: print the reverse of s\n    return 0;\n}\n",
            'java' => "import java.util.*;\npublic class Main {\n    public static void main(String[] args) {\n        Scanner sc = new Scanner(System.in);\n        String s = sc.nextLine();\n        // TODO: print the reverse of s\n    }\n}\n",
        ],
        'tests' => [
            ['stdin' => "hello",   'expected' => "olleh",   'sample' => true],
            ['stdin' => "DSA",     'expected' => "ASD",     'sample' => true],
            ['stdin' => "racecar", 'expected' => "racecar"],
            ['stdin' => "ab cd",   'expected' => "dc ba"],
        ],
    ],

    [
        'title' => 'Maximum of an Array', 'slug' => 'ex-array-maximum', 'difficulty' => 'easy',
        'statement_md' => "The first line contains an integer `n`. The second line contains `n` space-separated integers. Print the **maximum** value.",
        'examples_md' => "```\nInput:\n5\n3 7 1 9 2\nOutput:\n9\n```",
        'constraints_md' => "- 1 ≤ n ≤ 10^5",
        'starters' => [
            'php' => "<?php\n\$n = (int) trim(fgets(STDIN));\n\$arr = array_map('intval', preg_split('/\\s+/', trim(fgets(STDIN))));\n\n// TODO: print the maximum value in \$arr\n",
            'python' => "n = int(input())\narr = list(map(int, input().split()))\n\n# TODO: print the maximum value in arr\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main() {\n    int n; cin >> n;\n    vector<long long> a(n);\n    for (auto& x : a) cin >> x;\n    // TODO: print the maximum element of a\n    return 0;\n}\n",
            'java' => "import java.util.*;\npublic class Main {\n    public static void main(String[] args) {\n        Scanner sc = new Scanner(System.in);\n        int n = sc.nextInt();\n        long[] a = new long[n];\n        for (int i = 0; i < n; i++) a[i] = sc.nextLong();\n        // TODO: print the maximum element of a\n    }\n}\n",
        ],
        'tests' => [
            ['stdin' => "5\n3 7 1 9 2", 'expected' => "9",  'sample' => true],
            ['stdin' => "3\n-1 -5 -3",  'expected' => "-1", 'sample' => true],
            ['stdin' => "1\n42",        'expected' => "42"],
            ['stdin' => "6\n5 5 5 5 5 6", 'expected' => "6"],
        ],
    ],

    [
        'title' => 'Factorial', 'slug' => 'ex-factorial', 'difficulty' => 'easy',
        'statement_md' => "Read a non-negative integer `n` and print `n!` (n factorial). Note `0! = 1`.",
        'examples_md' => "```\nInput:  5\nOutput: 120\n```",
        'constraints_md' => "- 0 ≤ n ≤ 20",
        'starters' => [
            'php' => "<?php\n\$n = (int) trim(fgets(STDIN));\n\n// TODO: print n!\n",
            'python' => "n = int(input())\n\n# TODO: print n!\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main() {\n    long long n; cin >> n;\n    // TODO: print n! (use long long)\n    return 0;\n}\n",
            'java' => "import java.util.*;\npublic class Main {\n    public static void main(String[] args) {\n        Scanner sc = new Scanner(System.in);\n        long n = sc.nextLong();\n        // TODO: print n! (use long)\n    }\n}\n",
        ],
        'tests' => [
            ['stdin' => "5",  'expected' => "120",     'sample' => true],
            ['stdin' => "0",  'expected' => "1",       'sample' => true],
            ['stdin' => "6",  'expected' => "720"],
            ['stdin' => "10", 'expected' => "3628800"],
        ],
    ],

    [
        'title' => 'FizzBuzz', 'slug' => 'ex-fizzbuzz', 'difficulty' => 'medium',
        'statement_md' => "Read an integer `n` and print the numbers from 1 to `n`, each on its own line. For multiples of 3 print `Fizz`, for multiples of 5 print `Buzz`, and for multiples of both print `FizzBuzz`.",
        'examples_md' => "```\nInput:\n5\nOutput:\n1\n2\nFizz\n4\nBuzz\n```",
        'constraints_md' => "- 1 ≤ n ≤ 10^4",
        'starters' => [
            'php' => "<?php\n\$n = (int) trim(fgets(STDIN));\nfor (\$i = 1; \$i <= \$n; \$i++) {\n    // TODO: print Fizz / Buzz / FizzBuzz / the number\n}\n",
            'python' => "n = int(input())\nfor i in range(1, n + 1):\n    pass  # TODO: print Fizz / Buzz / FizzBuzz / the number\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main() {\n    int n; cin >> n;\n    for (int i = 1; i <= n; i++) {\n        // TODO: print Fizz / Buzz / FizzBuzz / the number\n    }\n    return 0;\n}\n",
            'java' => "import java.util.*;\npublic class Main {\n    public static void main(String[] args) {\n        Scanner sc = new Scanner(System.in);\n        int n = sc.nextInt();\n        for (int i = 1; i <= n; i++) {\n            // TODO: print Fizz / Buzz / FizzBuzz / the number\n        }\n    }\n}\n",
        ],
        'tests' => [
            ['stdin' => "5",  'expected' => "1\n2\nFizz\n4\nBuzz", 'sample' => true],
            ['stdin' => "3",  'expected' => "1\n2\nFizz"],
            ['stdin' => "15", 'expected' => "1\n2\nFizz\n4\nBuzz\nFizz\n7\n8\nFizz\nBuzz\n11\nFizz\n13\n14\nFizzBuzz"],
        ],
    ],
];
