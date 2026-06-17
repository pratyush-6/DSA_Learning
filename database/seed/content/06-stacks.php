<?php
return [
    'title'       => 'Stacks',
    'slug'        => 'stacks',
    'level'       => 'beginner',
    'icon'        => 'bi-stack',
    'sort_order'  => 6,
    'description' => 'Last-In-First-Out (LIFO) structure powering undo, recursion, and expression parsing.',

    'topics' => [
        [
            'title'   => 'The Stack (LIFO)',
            'slug'    => 'stack-basics',
            'summary' => 'Push, pop, and peek — all O(1).',
            'theory_md' => <<<MD
A **stack** follows **LIFO** — *Last In, First Out*. The last element added is the
first one removed, like a stack of plates.

Core operations (all **O(1)**):
- **push(x)** — add to the top
- **pop()** — remove and return the top
- **peek/top()** — look at the top without removing
- **isEmpty()**

A stack can be backed by a dynamic array or a linked list.
MD,
            'complexity_md' => "push / pop / peek: **O(1)**. Space: O(n).",
            'real_world_md' => <<<MD
- **Undo/redo** in editors.
- **Function call stack** (how recursion is executed).
- **Browser back button** (a stack of visited pages).
- **Expression evaluation** and **balanced-bracket** checking.
- **Backtracking** (DFS) uses a stack, explicitly or via recursion.
MD,
            'code' => [
                ['language' => 'python', 'label' => 'List as stack', 'code' => <<<'CODE'
stack = []
stack.append(10)   # push
stack.append(20)
print(stack[-1])   # peek -> 20
print(stack.pop()) # pop  -> 20
print(len(stack) == 0)  # isEmpty
CODE],
                ['language' => 'php', 'label' => 'Array as stack', 'code' => <<<'CODE'
<?php
$stack = [];
array_push($stack, 10);   // push
array_push($stack, 20);
echo end($stack);         // peek -> 20
echo array_pop($stack);   // pop  -> 20
var_dump(empty($stack));  // isEmpty
CODE],
                ['language' => 'java', 'label' => 'Deque as stack', 'code' => <<<'CODE'
import java.util.*;
Deque<Integer> stack = new ArrayDeque<>();
stack.push(10);          // push
stack.push(20);
System.out.println(stack.peek()); // 20
System.out.println(stack.pop());  // 20
System.out.println(stack.isEmpty());
CODE],
                ['language' => 'cpp', 'label' => 'std::stack', 'code' => <<<'CODE'
#include <stack>
using namespace std;
stack<int> st;
st.push(10);            // push
st.push(20);
cout << st.top();       // peek -> 20
st.pop();               // remove top
cout << st.empty();     // isEmpty
CODE],
            ],
        ],
        [
            'title'   => 'Balanced Parentheses',
            'slug'    => 'balanced-parentheses',
            'summary' => 'The classic stack application.',
            'theory_md' => <<<MD
To check whether brackets `()[]{}` are balanced: scan the string, **push** every
opening bracket, and on a **closing** bracket check that the top of the stack is the
matching opener (then pop). The string is balanced if every closer matched and the
stack is empty at the end.
MD,
            'complexity_md' => "O(n) time, O(n) space (stack can hold up to n openers).",
            'real_world_md' => "Compilers and editors use this to validate code syntax and highlight mismatched brackets.",
            'code' => [
                ['language' => 'python', 'label' => 'Valid parentheses', 'code' => <<<'CODE'
def is_valid(s):
    pairs = {')': '(', ']': '[', '}': '{'}
    stack = []
    for c in s:
        if c in '([{':
            stack.append(c)
        elif not stack or stack.pop() != pairs[c]:
            return False
    return not stack
CODE],
                ['language' => 'php', 'label' => 'Valid parentheses', 'code' => <<<'CODE'
<?php
function isValid(string $s): bool {
    $pairs = [')' => '(', ']' => '[', '}' => '{'];
    $stack = [];
    foreach (str_split($s) as $c) {
        if (in_array($c, ['(', '[', '{'], true)) {
            $stack[] = $c;
        } elseif (!$stack || array_pop($stack) !== $pairs[$c]) {
            return false;
        }
    }
    return empty($stack);
}
CODE],
                ['language' => 'java', 'label' => 'Valid parentheses', 'code' => <<<'CODE'
boolean isValid(String s) {
    Deque<Character> st = new ArrayDeque<>();
    for (char c : s.toCharArray()) {
        if (c == '(') st.push(')');
        else if (c == '[') st.push(']');
        else if (c == '{') st.push('}');
        else if (st.isEmpty() || st.pop() != c) return false;
    }
    return st.isEmpty();
}
CODE],
                ['language' => 'cpp', 'label' => 'Valid parentheses', 'code' => <<<'CODE'
bool isValid(string s){
    stack<char> st;
    for(char c : s){
        if(c=='(') st.push(')');
        else if(c=='[') st.push(']');
        else if(c=='{') st.push('}');
        else if(st.empty() || st.top()!=c) return false;
        else st.pop();
    }
    return st.empty();
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'easy',
         'question' => 'What does LIFO mean and where is it used?',
         'answer_md' => 'LIFO = Last In, First Out. The most recently pushed element is popped first. Used in function call stacks, undo features, DFS, and expression parsing.',
         'companies' => ['Amazon']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Implement a stack that returns the minimum element in O(1).',
         'answer_md' => 'Keep an auxiliary "min stack" that stores the minimum so far at each level; push/pop it alongside the main stack. All operations stay O(1).',
         'companies' => ['Google', 'Amazon', 'Bloomberg']],
        ['type' => 'coding', 'difficulty' => 'medium',
         'question' => 'Evaluate a postfix (Reverse Polish) expression.',
         'answer_md' => 'Push operands; on an operator, pop two operands, apply it, push the result. The final stack value is the answer. O(n).',
         'companies' => ['Microsoft']],
    ],

    'problems' => [
        ['title' => 'Valid Parentheses', 'slug' => 'stacks-valid-parentheses', 'difficulty' => 'easy',
         'statement_md' => "Given a string of brackets `()[]{}`, determine if the input is valid (every bracket is closed by the correct type in the correct order).",
         'examples_md' => "```\n\"()[]{}\" -> true\n\"(]\"     -> false\n```",
         'solutions' => [
            'python' => ['code' => "def is_valid(s):\n    pairs = {')':'(', ']':'[', '}':'{'}\n    st = []\n    for c in s:\n        if c in '([{': st.append(c)\n        elif not st or st.pop() != pairs[c]: return False\n    return not st", 'explanation_md' => 'Stack tracks unmatched openers; each closer must match the latest opener. O(n).'],
            'php' => ['code' => "<?php\nfunction isValid(string \$s): bool {\n    \$p = [')'=>'(', ']'=>'[', '}'=>'{'];\n    \$st = [];\n    foreach (str_split(\$s) as \$c) {\n        if (in_array(\$c, ['(','[','{'], true)) \$st[] = \$c;\n        elseif (!\$st || array_pop(\$st) !== \$p[\$c]) return false;\n    }\n    return empty(\$st);\n}", 'explanation_md' => ''],
            'java' => ['code' => "boolean isValid(String s){\n    Deque<Character> st = new ArrayDeque<>();\n    for(char c: s.toCharArray()){\n        if(c=='(') st.push(')');\n        else if(c=='[') st.push(']');\n        else if(c=='{') st.push('}');\n        else if(st.isEmpty() || st.pop()!=c) return false;\n    }\n    return st.isEmpty();\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "bool isValid(string s){\n    stack<char> st;\n    for(char c: s){\n        if(c=='(') st.push(')');\n        else if(c=='[') st.push(']');\n        else if(c=='{') st.push('}');\n        else if(st.empty()||st.top()!=c) return false;\n        else st.pop();\n    }\n    return st.empty();\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Stacks Quiz',
        'questions' => [
            ['question' => 'A stack follows which principle?',
             'options' => [['text' => 'LIFO', 'correct' => true], ['text' => 'FIFO', 'correct' => false], ['text' => 'Random access', 'correct' => false], ['text' => 'Sorted order', 'correct' => false]]],
            ['question' => 'Time complexity of push and pop on a stack?',
             'options' => [['text' => 'O(1)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false], ['text' => 'O(n log n)', 'correct' => false]]],
            ['question' => 'Which problem is naturally solved with a stack?',
             'options' => [['text' => 'Balanced parentheses checking', 'correct' => true], ['text' => 'Finding shortest path', 'correct' => false], ['text' => 'Sorting by counting', 'correct' => false], ['text' => 'Computing averages', 'correct' => false]]],
        ],
    ],
];
