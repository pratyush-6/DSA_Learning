<?php
/**
 * Turns selected Coder Army sheet problems into auto-graded compiler exercises:
 * adds a precise stdin/stdout statement, starter code, and test cases to the
 * EXISTING problem (matched by topic+title → slug).
 *
 * Each entry carries a `reference` Python solution used ONLY by
 * database/seed/verify_compiler.php to prove the expected outputs are correct.
 * The reference is never stored in the database.
 */

$arrStarters = [
    'php' => "<?php\n\$n = (int) trim(fgets(STDIN));\n\$a = \$n ? array_map('intval', preg_split('/\\s+/', trim(fgets(STDIN)))) : [];\n\n// TODO: solve and print the answer\n",
    'python' => "n = int(input())\na = list(map(int, input().split())) if n else []\n\n# TODO: solve and print the answer\n",
    'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){\n    int n; cin >> n;\n    vector<long long> a(n);\n    for (auto& x : a) cin >> x;\n    // TODO: solve and print the answer\n    return 0;\n}\n",
    'java' => "import java.util.*;\npublic class Main {\n    public static void main(String[] args) {\n        Scanner sc = new Scanner(System.in);\n        int n = sc.nextInt();\n        long[] a = new long[n];\n        for (int i = 0; i < n; i++) a[i] = sc.nextLong();\n        // TODO: solve and print the answer\n    }\n}\n",
];
$intStarter = [
    'php' => "<?php\n\$n = (int) trim(fgets(STDIN));\n\n// TODO: print the answer\n",
    'python' => "n = int(input())\n\n# TODO: print the answer\n",
    'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ long long n; cin >> n; /* TODO */ return 0; }\n",
    'java' => "import java.util.*;\npublic class Main{ public static void main(String[] x){ long n=new Scanner(System.in).nextLong(); /* TODO */ } }\n",
];
$strStarter = [
    'php' => "<?php\n\$s = rtrim(fgets(STDIN), \"\\r\\n\");\n\n// TODO: print the answer\n",
    'python' => "s = input()\n\n# TODO: print the answer\n",
    'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ string s; getline(cin,s); /* TODO */ return 0; }\n",
    'java' => "import java.util.*;\npublic class Main{ public static void main(String[] x){ String s=new Scanner(System.in).nextLine(); /* TODO */ } }\n",
];

return [
    [
        'topic' => 'Array', 'title' => 'Search an Element in an array',
        'statement_md' => "Line 1: `n`. Line 2: `n` integers. Line 3: target `x`. Print the **index (0-based)** of the first occurrence of `x`, or `-1`.",
        'examples_md' => "```\nInput:\n4\n10 20 30 40\n30\nOutput:\n2\n```",
        'starters' => [
            'php' => "<?php\n\$n = (int) trim(fgets(STDIN));\n\$a = array_map('intval', preg_split('/\\s+/', trim(fgets(STDIN))));\n\$x = (int) trim(fgets(STDIN));\n\n// TODO: print the first index of \$x in \$a, or -1\n",
            'python' => "n = int(input())\na = list(map(int, input().split()))\nx = int(input())\n\n# TODO: print the first index of x in a, or -1\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ int n; cin>>n; vector<int> a(n); for(auto&v:a)cin>>v; int x; cin>>x; /* TODO */ return 0; }\n",
            'java' => "import java.util.*;\npublic class Main{ public static void main(String[] z){ Scanner s=new Scanner(System.in); int n=s.nextInt(); int[] a=new int[n]; for(int i=0;i<n;i++)a[i]=s.nextInt(); int x=s.nextInt(); /* TODO */ } }\n",
        ],
        'reference' => "n=int(input())\na=list(map(int,input().split()))\nx=int(input())\nprint(a.index(x) if x in a else -1)",
        'tests' => [
            ['stdin' => "4\n10 20 30 40\n30", 'expected' => "2", 'sample' => true],
            ['stdin' => "4\n10 20 30 40\n99", 'expected' => "-1", 'sample' => true],
            ['stdin' => "5\n5 4 3 2 1\n5", 'expected' => "0"],
            ['stdin' => "1\n7\n7", 'expected' => "0"],
        ],
    ],
    [
        'topic' => 'Array', 'title' => 'Find minimum and maximum element in an array',
        'statement_md' => "Line 1: `n`. Line 2: `n` integers. Print the minimum and maximum separated by a space: `min max`.",
        'examples_md' => "```\nInput:\n5\n3 7 1 9 2\nOutput:\n1 9\n```",
        'starters' => $arrStarters,
        'reference' => "n=int(input())\na=list(map(int,input().split()))\nprint(min(a), max(a))",
        'tests' => [
            ['stdin' => "5\n3 7 1 9 2", 'expected' => "1 9", 'sample' => true],
            ['stdin' => "1\n42", 'expected' => "42 42", 'sample' => true],
            ['stdin' => "4\n-5 -1 -9 -3", 'expected' => "-9 -1"],
        ],
    ],
    [
        'topic' => 'Array', 'title' => 'Cyclically rotate an array by one',
        'statement_md' => "Line 1: `n`. Line 2: `n` integers. Rotate the array right by one (last element moves to front) and print it space-separated.",
        'examples_md' => "```\nInput:\n5\n1 2 3 4 5\nOutput:\n5 1 2 3 4\n```",
        'starters' => $arrStarters,
        'reference' => "n=int(input())\na=list(map(int,input().split()))\nif a:\n    a=[a[-1]]+a[:-1]\nprint(' '.join(map(str,a)))",
        'tests' => [
            ['stdin' => "5\n1 2 3 4 5", 'expected' => "5 1 2 3 4", 'sample' => true],
            ['stdin' => "3\n7 8 9", 'expected' => "9 7 8", 'sample' => true],
            ['stdin' => "1\n4", 'expected' => "4"],
        ],
    ],
    [
        'topic' => 'Array', 'title' => 'Sort an array of 0s, 1s and 2s',
        'statement_md' => "Line 1: `n`. Line 2: `n` integers (each 0, 1, or 2). Print the array sorted in non-decreasing order, space-separated.",
        'examples_md' => "```\nInput:\n6\n2 0 2 1 1 0\nOutput:\n0 0 1 1 2 2\n```",
        'starters' => $arrStarters,
        'reference' => "n=int(input())\na=list(map(int,input().split()))\na.sort()\nprint(' '.join(map(str,a)))",
        'tests' => [
            ['stdin' => "6\n2 0 2 1 1 0", 'expected' => "0 0 1 1 2 2", 'sample' => true],
            ['stdin' => "3\n2 1 0", 'expected' => "0 1 2", 'sample' => true],
            ['stdin' => "4\n1 1 1 1", 'expected' => "1 1 1 1"],
        ],
    ],
    [
        'topic' => 'Array', 'title' => 'Majority Element',
        'statement_md' => "Line 1: `n`. Line 2: `n` integers, where one element appears **more than n/2 times**. Print that element.",
        'examples_md' => "```\nInput:\n7\n2 2 1 1 1 2 2\nOutput:\n2\n```",
        'starters' => $arrStarters,
        'reference' => "n=int(input())\na=list(map(int,input().split()))\ncand=None;c=0\nfor x in a:\n    if c==0: cand=x\n    c += 1 if x==cand else -1\nprint(cand)",
        'tests' => [
            ['stdin' => "7\n2 2 1 1 1 2 2", 'expected' => "2", 'sample' => true],
            ['stdin' => "5\n3 3 3 1 2", 'expected' => "3", 'sample' => true],
            ['stdin' => "1\n9", 'expected' => "9"],
        ],
    ],
    [
        'topic' => 'String', 'title' => 'Reverse a String',
        'statement_md' => "Read a line of text and print it reversed.",
        'examples_md' => "```\nInput:  hello\nOutput: olleh\n```",
        'starters' => $strStarter,
        'reference' => "s=input()\nprint(s[::-1])",
        'tests' => [
            ['stdin' => "hello", 'expected' => "olleh", 'sample' => true],
            ['stdin' => "DSA Learn", 'expected' => "nraeL ASD", 'sample' => true],
            ['stdin' => "x", 'expected' => "x"],
        ],
    ],
    [
        'topic' => 'String', 'title' => 'Palindrome String',
        'statement_md' => "Read a string. Print `YES` if it is a palindrome, otherwise `NO`.",
        'examples_md' => "```\nInput:  racecar\nOutput: YES\n```",
        'starters' => $strStarter,
        'reference' => "s=input()\nprint('YES' if s==s[::-1] else 'NO')",
        'tests' => [
            ['stdin' => "racecar", 'expected' => "YES", 'sample' => true],
            ['stdin' => "hello", 'expected' => "NO", 'sample' => true],
            ['stdin' => "a", 'expected' => "YES"],
            ['stdin' => "abba", 'expected' => "YES"],
        ],
    ],
    [
        'topic' => 'Searching and Sorting', 'title' => 'Bubble Sort',
        'statement_md' => "Line 1: `n`. Line 2: `n` integers. Print them sorted ascending, space-separated.",
        'examples_md' => "```\nInput:\n5\n5 2 8 1 9\nOutput:\n1 2 5 8 9\n```",
        'starters' => $arrStarters,
        'reference' => "n=int(input())\na=list(map(int,input().split()))\nprint(' '.join(map(str,sorted(a))))",
        'tests' => [
            ['stdin' => "5\n5 2 8 1 9", 'expected' => "1 2 5 8 9", 'sample' => true],
            ['stdin' => "3\n3 2 1", 'expected' => "1 2 3", 'sample' => true],
            ['stdin' => "4\n-1 5 -3 0", 'expected' => "-3 -1 0 5"],
        ],
    ],
    [
        'topic' => 'Searching and Sorting', 'title' => 'Quick Sort',
        'statement_md' => "Line 1: `n`. Line 2: `n` integers. Print them sorted ascending, space-separated.",
        'examples_md' => "```\nInput:\n5\n5 2 8 1 9\nOutput:\n1 2 5 8 9\n```",
        'starters' => $arrStarters,
        'reference' => "n=int(input())\na=list(map(int,input().split()))\nprint(' '.join(map(str,sorted(a))))",
        'tests' => [
            ['stdin' => "5\n5 2 8 1 9", 'expected' => "1 2 5 8 9", 'sample' => true],
            ['stdin' => "6\n9 8 7 6 5 4", 'expected' => "4 5 6 7 8 9", 'sample' => true],
            ['stdin' => "1\n0", 'expected' => "0"],
        ],
    ],
    [
        'topic' => 'Searching and Sorting', 'title' => 'Square root of a number',
        'statement_md' => "Read a non-negative integer `n`. Print the floor of its square root (the largest integer `r` with `r*r ≤ n`).",
        'examples_md' => "```\nInput:  16\nOutput: 4\n```",
        'starters' => $intStarter,
        'reference' => "import math\nn=int(input())\nprint(math.isqrt(n))",
        'tests' => [
            ['stdin' => "16", 'expected' => "4", 'sample' => true],
            ['stdin' => "10", 'expected' => "3", 'sample' => true],
            ['stdin' => "1", 'expected' => "1"],
            ['stdin' => "0", 'expected' => "0"],
            ['stdin' => "100", 'expected' => "10"],
        ],
    ],
    [
        'topic' => 'Searching and Sorting', 'title' => 'Searching an element in a sorted array',
        'statement_md' => "Line 1: `n`. Line 2: `n` integers in **non-decreasing** order. Line 3: target `x`. Print the index (0-based) of `x`, or `-1`. Aim for O(log n).",
        'examples_md' => "```\nInput:\n5\n-1 0 3 5 9\n5\nOutput:\n3\n```",
        'starters' => [
            'php' => "<?php\n\$n=(int)trim(fgets(STDIN));\n\$a=array_map('intval',preg_split('/\\s+/',trim(fgets(STDIN))));\n\$x=(int)trim(fgets(STDIN));\n\n// TODO: binary search; print index or -1\n",
            'python' => "n=int(input())\na=list(map(int,input().split()))\nx=int(input())\n\n# TODO: binary search; print index or -1\n",
            'cpp' => "#include <bits/stdc++.h>\nusing namespace std;\nint main(){ int n; cin>>n; vector<int> a(n); for(auto&v:a)cin>>v; int x; cin>>x; /* TODO */ return 0; }\n",
            'java' => "import java.util.*;\npublic class Main{ public static void main(String[] z){ Scanner s=new Scanner(System.in); int n=s.nextInt(); int[] a=new int[n]; for(int i=0;i<n;i++)a[i]=s.nextInt(); int x=s.nextInt(); /* TODO */ } }\n",
        ],
        'reference' => "import bisect\nn=int(input())\na=list(map(int,input().split()))\nx=int(input())\ni=bisect.bisect_left(a,x)\nprint(i if i<len(a) and a[i]==x else -1)",
        'tests' => [
            ['stdin' => "5\n-1 0 3 5 9\n5", 'expected' => "3", 'sample' => true],
            ['stdin' => "5\n-1 0 3 5 9\n2", 'expected' => "-1", 'sample' => true],
            ['stdin' => "1\n4\n4", 'expected' => "0"],
        ],
    ],
    [
        'topic' => 'Dynamic Programming', 'title' => 'Nth Fibonacci Number',
        'statement_md' => "Read `n`. Print the n-th Fibonacci number where `fib(0)=0`, `fib(1)=1`.",
        'examples_md' => "```\nInput:  10\nOutput: 55\n```",
        'starters' => $intStarter,
        'reference' => "n=int(input())\na,b=0,1\nfor _ in range(n): a,b=b,a+b\nprint(a)",
        'tests' => [
            ['stdin' => "10", 'expected' => "55", 'sample' => true],
            ['stdin' => "0", 'expected' => "0", 'sample' => true],
            ['stdin' => "1", 'expected' => "1"],
            ['stdin' => "7", 'expected' => "13"],
        ],
    ],
    [
        'topic' => 'Dynamic Programming', 'title' => "Kadane's Algorithm",
        'statement_md' => "Line 1: `n`. Line 2: `n` integers. Print the maximum sum of any non-empty contiguous subarray.",
        'examples_md' => "```\nInput:\n9\n-2 1 -3 4 -1 2 1 -5 4\nOutput:\n6\n```",
        'starters' => $arrStarters,
        'reference' => "n=int(input())\na=list(map(int,input().split()))\nbest=cur=a[0]\nfor x in a[1:]:\n    cur=max(x,cur+x); best=max(best,cur)\nprint(best)",
        'tests' => [
            ['stdin' => "9\n-2 1 -3 4 -1 2 1 -5 4", 'expected' => "6", 'sample' => true],
            ['stdin' => "3\n1 2 3", 'expected' => "6", 'sample' => true],
            ['stdin' => "3\n-1 -2 -3", 'expected' => "-1"],
            ['stdin' => "1\n5", 'expected' => "5"],
        ],
    ],
    [
        'topic' => 'Dynamic Programming', 'title' => 'House Robber',
        'statement_md' => "Line 1: `n`. Line 2: `n` non-negative integers (house values). Print the maximum total you can rob without robbing two adjacent houses.",
        'examples_md' => "```\nInput:\n5\n2 7 9 3 1\nOutput:\n12\n```",
        'starters' => $arrStarters,
        'reference' => "n=int(input())\na=list(map(int,input().split())) if n else []\nprev=cur=0\nfor x in a:\n    prev,cur=cur,max(cur,prev+x)\nprint(cur)",
        'tests' => [
            ['stdin' => "5\n2 7 9 3 1", 'expected' => "12", 'sample' => true],
            ['stdin' => "4\n1 2 3 1", 'expected' => "4", 'sample' => true],
            ['stdin' => "1\n5", 'expected' => "5"],
            ['stdin' => "4\n2 1 1 2", 'expected' => "4"],
        ],
    ],
    [
        'topic' => 'Greedy', 'title' => 'Minimum number of jumps',
        'statement_md' => "Line 1: `n`. Line 2: `n` non-negative integers; `a[i]` is the max jump length from index `i`. Print the minimum jumps to reach the last index, or `-1` if unreachable.",
        'examples_md' => "```\nInput:\n5\n2 3 1 1 4\nOutput:\n2\n```",
        'starters' => $arrStarters,
        'reference' => "n=int(input())\na=list(map(int,input().split()))\nif n<=1:\n    print(0)\nelif a[0]==0:\n    print(-1)\nelse:\n    jumps=1;far=a[0];end=a[0];ans=-1;ok=True\n    for i in range(1,n):\n        if i==n-1:\n            ans=jumps;ok=False;break\n        far=max(far,i+a[i])\n        if i==end:\n            jumps+=1;end=far\n            if i>=end:\n                ans=-1;ok=False;break\n    print(ans if not ok else -1)",
        'tests' => [
            ['stdin' => "5\n2 3 1 1 4", 'expected' => "2", 'sample' => true],
            ['stdin' => "4\n1 1 1 1", 'expected' => "3", 'sample' => true],
            ['stdin' => "2\n0 1", 'expected' => "-1"],
            ['stdin' => "1\n0", 'expected' => "0"],
        ],
    ],
    [
        'topic' => 'Hashing', 'title' => 'Longest consecutive subsequence',
        'statement_md' => "Line 1: `n`. Line 2: `n` integers. Print the length of the longest run of consecutive integers (order in the array does not matter).",
        'examples_md' => "```\nInput:\n6\n100 4 200 1 3 2\nOutput:\n4\n```",
        'starters' => $arrStarters,
        'reference' => "n=int(input())\na=list(map(int,input().split())) if n else []\ns=set(a);best=0\nfor x in s:\n    if x-1 not in s:\n        y=x\n        while y+1 in s: y+=1\n        best=max(best,y-x+1)\nprint(best)",
        'tests' => [
            ['stdin' => "6\n100 4 200 1 3 2", 'expected' => "4", 'sample' => true],
            ['stdin' => "10\n0 3 7 2 5 8 4 6 0 1", 'expected' => "9", 'sample' => true],
            ['stdin' => "1\n5", 'expected' => "1"],
        ],
    ],
];
