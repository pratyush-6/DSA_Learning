<?php
return [
    'title'       => 'Bit Manipulation',
    'slug'        => 'bit-manipulation',
    'level'       => 'advanced',
    'icon'        => 'bi-binary',
    'sort_order'  => 26,
    'description' => 'Operate directly on bits for compact, blazing-fast tricks.',

    'topics' => [
        [
            'title'   => 'Bitwise Operators & Tricks',
            'slug'    => 'bitwise-tricks',
            'summary' => 'AND, OR, XOR, shifts, and the classic bit hacks.',
            'theory_md' => <<<MD
Numbers are stored in binary; **bitwise operators** act on individual bits:

| Op | Meaning |
|----|---------|
| `&` | AND |
| `\|` | OR |
| `^` | XOR (1 if bits differ) |
| `~` | NOT (flip all bits) |
| `<<` | left shift (×2 per shift) |
| `>>` | right shift (÷2 per shift) |

**Essential tricks:**
- Check bit i: `(x >> i) & 1`
- Set bit i: `x | (1 << i)` &nbsp;·&nbsp; Clear bit i: `x & ~(1 << i)` &nbsp;·&nbsp; Toggle: `x ^ (1 << i)`
- Is power of two: `x > 0 && (x & (x - 1)) == 0`
- Remove lowest set bit: `x & (x - 1)` &nbsp;·&nbsp; Lowest set bit: `x & -x`
- `a ^ a = 0` and `a ^ 0 = a` → XOR finds the unique element when others appear in pairs.
MD,
            'complexity_md' => "Bitwise ops are **O(1)**. Counting set bits via `x & (x-1)` runs in O(number of set bits). Bitmasking over n items is O(2ⁿ · n).",
            'real_world_md' => <<<MD
- **Flags / permissions** packed into one integer (read/write/execute bits).
- **Hashing, checksums, and cryptography.**
- **Bitset** data structures and **graphics** (color/pixel masks).
- **Bitmask DP** (e.g., Traveling Salesman over subsets).
MD,
            'code' => [
                ['language' => 'python', 'label' => 'Common tricks', 'code' => <<<'CODE'
x = 0b1010              # 10
print((x >> 1) & 1)    # bit 1 -> 1
x |= (1 << 0)          # set bit 0 -> 1011
x &= ~(1 << 1)         # clear bit 1
print(bin(x))

def is_power_of_two(n):
    return n > 0 and (n & (n - 1)) == 0

def count_set_bits(n):
    c = 0
    while n:
        n &= n - 1     # drop lowest set bit
        c += 1
    return c
CODE],
                ['language' => 'php', 'label' => 'Common tricks', 'code' => <<<'CODE'
<?php
$x = 0b1010;                 // 10
echo ($x >> 1) & 1;         // 1
$x |= (1 << 0);             // set bit 0
$x &= ~(1 << 1);            // clear bit 1

function isPowerOfTwo(int $n): bool {
    return $n > 0 && ($n & ($n - 1)) === 0;
}
function countSetBits(int $n): int {
    $c = 0;
    while ($n) { $n &= $n - 1; $c++; }
    return $c;
}
CODE],
                ['language' => 'java', 'label' => 'Common tricks', 'code' => <<<'CODE'
boolean isPowerOfTwo(int n){ return n > 0 && (n & (n - 1)) == 0; }

int countSetBits(int n){
    int c = 0;
    while(n != 0){ n &= n - 1; c++; }
    return c;
}
// built-in: Integer.bitCount(n)
CODE],
                ['language' => 'cpp', 'label' => 'Common tricks', 'code' => <<<'CODE'
bool isPowerOfTwo(int n){ return n > 0 && (n & (n - 1)) == 0; }

int countSetBits(int n){
    int c = 0;
    while(n){ n &= n - 1; c++; }
    return c;
}
// built-in: __builtin_popcount(n)
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'How do you check if a number is a power of two using bits?',
         'answer_md' => '`n > 0 && (n & (n - 1)) == 0`. A power of two has exactly one set bit; subtracting 1 flips that bit and all below it, so the AND is zero.',
         'companies' => ['Amazon', 'Adobe']],
        ['type' => 'coding', 'difficulty' => 'easy',
         'question' => 'Every element appears twice except one. Find the single number.',
         'answer_md' => 'XOR all elements: pairs cancel (a ^ a = 0), leaving the unique value. O(n) time, O(1) space.',
         'companies' => ['Amazon', 'Google']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Count the number of 1 bits in an integer (Hamming weight).',
         'answer_md' => 'Repeatedly clear the lowest set bit with `n &= n - 1` and count iterations — runs in O(set bits), not O(32).',
         'companies' => ['Microsoft', 'Bloomberg']],
    ],

    'problems' => [
        ['title' => 'Single Number', 'slug' => 'bits-single-number', 'difficulty' => 'easy',
         'statement_md' => "Given a non-empty array where every element appears twice except one, find that single element. Must be O(n) time and O(1) space.",
         'examples_md' => "```\n[4,1,2,1,2] -> 4\n[2,2,1]     -> 1\n```",
         'solutions' => [
            'python' => ['code' => "def single_number(nums):\n    result = 0\n    for n in nums:\n        result ^= n\n    return result", 'explanation_md' => 'XOR cancels equal pairs (a^a=0) and leaves the unique value (a^0=a).'],
            'php' => ['code' => "<?php\nfunction singleNumber(array \$nums): int {\n    \$result = 0;\n    foreach (\$nums as \$n) \$result ^= \$n;\n    return \$result;\n}", 'explanation_md' => ''],
            'java' => ['code' => "int singleNumber(int[] nums){\n    int result = 0;\n    for(int n : nums) result ^= n;\n    return result;\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "int singleNumber(vector<int>& nums){\n    int result = 0;\n    for(int n : nums) result ^= n;\n    return result;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Bit Manipulation Quiz',
        'questions' => [
            ['question' => 'a ^ a equals:',
             'options' => [['text' => '0', 'correct' => true], ['text' => 'a', 'correct' => false], ['text' => '1', 'correct' => false], ['text' => '2a', 'correct' => false]]],
            ['question' => 'x & (x - 1) does what?',
             'options' => [['text' => 'Removes the lowest set bit', 'correct' => true], ['text' => 'Sets the highest bit', 'correct' => false], ['text' => 'Doubles x', 'correct' => false], ['text' => 'Negates x', 'correct' => false]]],
            ['question' => 'Left-shifting a number by 1 (x << 1) is equivalent to:',
             'options' => [['text' => 'Multiplying by 2', 'correct' => true], ['text' => 'Dividing by 2', 'correct' => false], ['text' => 'Adding 1', 'correct' => false], ['text' => 'No change', 'correct' => false]]],
        ],
    ],
];
