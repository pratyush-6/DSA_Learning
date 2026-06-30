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

    [
        'title' => 'Even or Odd', 'slug' => 'ex-even-odd', 'difficulty' => 'easy',
        'statement_md' => "Read an integer `n`. Print `Even` if it is even, otherwise `Odd`.",
        'examples_md' => "```\nInput:  4\nOutput: Even\n```",
        'constraints_md' => "- −10^9 ≤ n ≤ 10^9",
        'starters' => [
            'php' => "<?php\n\$n = (int) trim(fgets(STDIN));\n\n// TODO: print \"Even\" or \"Odd\"\n",
            'python' => "n = int(input())\n\n# TODO: print 'Even' or 'Odd'\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ long long n; cin>>n; /* TODO */ return 0; }\n",
            'java' => "import java.util.*;\npublic class Main{ public static void main(String[] a){ long n=new Scanner(System.in).nextLong(); /* TODO */ } }\n",
        ],
        'tests' => [
            ['stdin' => "4", 'expected' => "Even", 'sample' => true],
            ['stdin' => "7", 'expected' => "Odd",  'sample' => true],
            ['stdin' => "0", 'expected' => "Even"],
            ['stdin' => "-3", 'expected' => "Odd"],
        ],
    ],

    [
        'title' => 'Count Vowels', 'slug' => 'ex-count-vowels', 'difficulty' => 'easy',
        'statement_md' => "Read a line of text and print the number of vowels (a, e, i, o, u — case-insensitive).",
        'examples_md' => "```\nInput:  hello\nOutput: 2\n```",
        'constraints_md' => "- Line length 1–1000.",
        'starters' => [
            'php' => "<?php\n\$s = rtrim(fgets(STDIN), \"\\r\\n\");\n\n// TODO: print the number of vowels in \$s\n",
            'python' => "s = input()\n\n# TODO: print the number of vowels in s\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ string s; getline(cin,s); /* TODO */ return 0; }\n",
            'java' => "import java.util.*;\npublic class Main{ public static void main(String[] a){ String s=new Scanner(System.in).nextLine(); /* TODO */ } }\n",
        ],
        'tests' => [
            ['stdin' => "hello",       'expected' => "2", 'sample' => true],
            ['stdin' => "AEIOU",       'expected' => "5", 'sample' => true],
            ['stdin' => "xyz",         'expected' => "0"],
            ['stdin' => "Programming", 'expected' => "3"],
        ],
    ],

    [
        'title' => 'GCD of Two Numbers', 'slug' => 'ex-gcd', 'difficulty' => 'easy',
        'statement_md' => "Read two positive integers `a` and `b` and print their greatest common divisor (GCD).",
        'examples_md' => "```\nInput:  12 18\nOutput: 6\n```",
        'constraints_md' => "- 1 ≤ a, b ≤ 10^9",
        'starters' => [
            'php' => "<?php\n[\$a, \$b] = array_map('intval', explode(' ', trim(fgets(STDIN))));\n\n// TODO: print gcd(\$a, \$b)\n",
            'python' => "a, b = map(int, input().split())\n\n# TODO: print gcd(a, b)\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ long long a,b; cin>>a>>b; /* TODO */ return 0; }\n",
            'java' => "import java.util.*;\npublic class Main{ public static void main(String[] x){ Scanner s=new Scanner(System.in); long a=s.nextLong(),b=s.nextLong(); /* TODO */ } }\n",
        ],
        'tests' => [
            ['stdin' => "12 18", 'expected' => "6",  'sample' => true],
            ['stdin' => "7 13",  'expected' => "1",  'sample' => true],
            ['stdin' => "100 10", 'expected' => "10"],
            ['stdin' => "1000000000 8", 'expected' => "8"],
        ],
    ],

    [
        'title' => 'Nth Fibonacci Number', 'slug' => 'ex-nth-fibonacci', 'difficulty' => 'easy',
        'statement_md' => "Read `n` and print the n-th Fibonacci number, where `fib(0) = 0`, `fib(1) = 1`.",
        'examples_md' => "```\nInput:  10\nOutput: 55\n```",
        'constraints_md' => "- 0 ≤ n ≤ 90",
        'starters' => [
            'php' => "<?php\n\$n = (int) trim(fgets(STDIN));\n\n// TODO: print the n-th Fibonacci number\n",
            'python' => "n = int(input())\n\n# TODO: print the n-th Fibonacci number\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ long long n; cin>>n; /* TODO (use long long) */ return 0; }\n",
            'java' => "import java.util.*;\npublic class Main{ public static void main(String[] a){ long n=new Scanner(System.in).nextLong(); /* TODO (use long) */ } }\n",
        ],
        'tests' => [
            ['stdin' => "10", 'expected' => "55", 'sample' => true],
            ['stdin' => "0",  'expected' => "0",  'sample' => true],
            ['stdin' => "1",  'expected' => "1"],
            ['stdin' => "20", 'expected' => "6765"],
        ],
    ],

    [
        'title' => 'Sum of Digits', 'slug' => 'ex-sum-of-digits', 'difficulty' => 'easy',
        'statement_md' => "Read a non-negative integer `n` and print the sum of its digits.",
        'examples_md' => "```\nInput:  12345\nOutput: 15\n```",
        'constraints_md' => "- 0 ≤ n ≤ 10^18",
        'starters' => [
            'php' => "<?php\n\$s = trim(fgets(STDIN));\n\n// TODO: print the sum of the digits of \$s\n",
            'python' => "s = input().strip()\n\n# TODO: print the sum of the digits of s\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ string s; cin>>s; /* TODO */ return 0; }\n",
            'java' => "import java.util.*;\npublic class Main{ public static void main(String[] a){ String s=new Scanner(System.in).next(); /* TODO */ } }\n",
        ],
        'tests' => [
            ['stdin' => "12345", 'expected' => "15", 'sample' => true],
            ['stdin' => "0",     'expected' => "0",  'sample' => true],
            ['stdin' => "999",   'expected' => "27"],
            ['stdin' => "1000000000000000000", 'expected' => "1"],
        ],
    ],

    [
        'title' => 'Check Prime', 'slug' => 'ex-check-prime', 'difficulty' => 'easy',
        'statement_md' => "Read an integer `n` and print `YES` if it is prime, otherwise `NO`.",
        'examples_md' => "```\nInput:  7\nOutput: YES\n```",
        'constraints_md' => "- 1 ≤ n ≤ 10^9",
        'starters' => [
            'php' => "<?php\n\$n = (int) trim(fgets(STDIN));\n\n// TODO: print \"YES\" if prime else \"NO\"\n",
            'python' => "n = int(input())\n\n# TODO: print 'YES' if prime else 'NO'\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ long long n; cin>>n; /* TODO */ return 0; }\n",
            'java' => "import java.util.*;\npublic class Main{ public static void main(String[] a){ long n=new Scanner(System.in).nextLong(); /* TODO */ } }\n",
        ],
        'tests' => [
            ['stdin' => "7",  'expected' => "YES", 'sample' => true],
            ['stdin' => "1",  'expected' => "NO",  'sample' => true],
            ['stdin' => "2",  'expected' => "YES"],
            ['stdin' => "9",  'expected' => "NO"],
            ['stdin' => "97", 'expected' => "YES"],
        ],
    ],

    [
        'title' => 'Second Largest Element', 'slug' => 'ex-second-largest', 'difficulty' => 'medium',
        'statement_md' => "The first line contains `n`. The second line contains `n` integers. Print the **second largest distinct** value, or `-1` if it does not exist.",
        'examples_md' => "```\nInput:\n5\n3 7 1 9 2\nOutput:\n7\n```",
        'constraints_md' => "- 1 ≤ n ≤ 10^5",
        'starters' => [
            'php' => "<?php\n\$n = (int) trim(fgets(STDIN));\n\$a = array_map('intval', preg_split('/\\s+/', trim(fgets(STDIN))));\n\n// TODO: print the second largest distinct value, or -1\n",
            'python' => "n = int(input())\na = list(map(int, input().split()))\n\n# TODO: print the second largest distinct value, or -1\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ int n; cin>>n; vector<long long> a(n); for(auto&x:a)cin>>x; /* TODO */ return 0; }\n",
            'java' => "import java.util.*;\npublic class Main{ public static void main(String[] x){ Scanner s=new Scanner(System.in); int n=s.nextInt(); long[] a=new long[n]; for(int i=0;i<n;i++)a[i]=s.nextLong(); /* TODO */ } }\n",
        ],
        'tests' => [
            ['stdin' => "5\n3 7 1 9 2",  'expected' => "7",  'sample' => true],
            ['stdin' => "4\n10 10 9 8",  'expected' => "9",  'sample' => true],
            ['stdin' => "2\n5 3",        'expected' => "3"],
            ['stdin' => "3\n4 4 4",      'expected' => "-1"],
        ],
    ],
];
