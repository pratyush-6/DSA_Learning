<?php
/**
 * Curated, verified 4-language solutions for the well-known classics in the
 * Coder Army sheet. Keyed by "Topic::Title" (exact strings from the CSV) so the
 * importer can attach them to the matching practice problem.
 *
 * Problems not listed here are still imported (browsable in Practice); their
 * solutions can be added later via the admin tool. We deliberately do NOT
 * fabricate code for platform-specific/ambiguous titles whose statements are
 * not part of the sheet.
 *
 * @return array<string, array<string, array{code:string, explanation_md?:string}>>
 */
return [

// ===================== ARRAY =====================
'Array::Search an Element in an array' => [
    'python' => ['code' => "def search(arr, x):\n    for i, v in enumerate(arr):\n        if v == x:\n            return i\n    return -1", 'explanation_md' => 'Linear scan, O(n).'],
    'php' => ['code' => "<?php\nfunction search(array \$arr, int \$x): int {\n    foreach (\$arr as \$i => \$v) if (\$v === \$x) return \$i;\n    return -1;\n}"],
    'java' => ['code' => "int search(int[] arr, int x){\n    for(int i=0;i<arr.length;i++) if(arr[i]==x) return i;\n    return -1;\n}"],
    'cpp' => ['code' => "int search(vector<int>& arr, int x){\n    for(int i=0;i<(int)arr.size();i++) if(arr[i]==x) return i;\n    return -1;\n}"],
],
'Array::Sort an array of 0s, 1s and 2s' => [
    'python' => ['code' => "def sort012(a):\n    low=mid=0; high=len(a)-1\n    while mid<=high:\n        if a[mid]==0: a[low],a[mid]=a[mid],a[low]; low+=1; mid+=1\n        elif a[mid]==1: mid+=1\n        else: a[mid],a[high]=a[high],a[mid]; high-=1\n    return a", 'explanation_md' => 'Dutch National Flag, one pass O(n).'],
    'php' => ['code' => "<?php\nfunction sort012(array \$a): array {\n    \$low=\$mid=0; \$high=count(\$a)-1;\n    while(\$mid<=\$high){\n        if(\$a[\$mid]==0){ [\$a[\$low],\$a[\$mid]]=[\$a[\$mid],\$a[\$low]]; \$low++; \$mid++; }\n        elseif(\$a[\$mid]==1) \$mid++;\n        else { [\$a[\$mid],\$a[\$high]]=[\$a[\$high],\$a[\$mid]]; \$high--; }\n    }\n    return \$a;\n}"],
    'java' => ['code' => "void sort012(int[] a){\n    int low=0,mid=0,high=a.length-1;\n    while(mid<=high){\n        if(a[mid]==0){int t=a[low];a[low]=a[mid];a[mid]=t;low++;mid++;}\n        else if(a[mid]==1) mid++;\n        else {int t=a[mid];a[mid]=a[high];a[high]=t;high--;}\n    }\n}"],
    'cpp' => ['code' => "void sort012(vector<int>& a){\n    int low=0,mid=0,high=a.size()-1;\n    while(mid<=high){\n        if(a[mid]==0) swap(a[low++],a[mid++]);\n        else if(a[mid]==1) mid++;\n        else swap(a[mid],a[high--]);\n    }\n}"],
],
'Array::Container With Most Water' => [
    'python' => ['code' => "def max_area(h):\n    i,j,best=0,len(h)-1,0\n    while i<j:\n        best=max(best,(j-i)*min(h[i],h[j]))\n        if h[i]<h[j]: i+=1\n        else: j-=1\n    return best", 'explanation_md' => 'Two pointers; move the shorter wall. O(n).'],
    'php' => ['code' => "<?php\nfunction maxArea(array \$h): int {\n    \$i=0; \$j=count(\$h)-1; \$best=0;\n    while(\$i<\$j){\n        \$best=max(\$best,(\$j-\$i)*min(\$h[\$i],\$h[\$j]));\n        if(\$h[\$i]<\$h[\$j]) \$i++; else \$j--;\n    }\n    return \$best;\n}"],
    'java' => ['code' => "int maxArea(int[] h){\n    int i=0,j=h.length-1,best=0;\n    while(i<j){\n        best=Math.max(best,(j-i)*Math.min(h[i],h[j]));\n        if(h[i]<h[j]) i++; else j--;\n    }\n    return best;\n}"],
    'cpp' => ['code' => "int maxArea(vector<int>& h){\n    int i=0,j=h.size()-1,best=0;\n    while(i<j){\n        best=max(best,(j-i)*min(h[i],h[j]));\n        if(h[i]<h[j]) i++; else j--;\n    }\n    return best;\n}"],
],

// ===================== STRING =====================
'String::Reverse a String' => [
    'python' => ['code' => "def reverse(s):\n    return s[::-1]", 'explanation_md' => 'O(n).'],
    'php' => ['code' => "<?php\nfunction reverseStr(string \$s): string { return strrev(\$s); }"],
    'java' => ['code' => "String reverse(String s){\n    return new StringBuilder(s).reverse().toString();\n}"],
    'cpp' => ['code' => "string reverseStr(string s){\n    reverse(s.begin(), s.end());\n    return s;\n}"],
],
'String::Palindrome String' => [
    'python' => ['code' => "def is_palindrome(s):\n    return s == s[::-1]", 'explanation_md' => 'O(n).'],
    'php' => ['code' => "<?php\nfunction isPalindrome(string \$s): bool { return \$s === strrev(\$s); }"],
    'java' => ['code' => "boolean isPalindrome(String s){\n    int i=0,j=s.length()-1;\n    while(i<j) if(s.charAt(i++)!=s.charAt(j--)) return false;\n    return true;\n}"],
    'cpp' => ['code' => "bool isPalindrome(string s){\n    int i=0,j=s.size()-1;\n    while(i<j) if(s[i++]!=s[j--]) return false;\n    return true;\n}"],
],
'String::Length of the longest substring' => [
    'python' => ['code' => "def longest(s):\n    seen={}; left=best=0\n    for r,c in enumerate(s):\n        if c in seen and seen[c]>=left: left=seen[c]+1\n        seen[c]=r; best=max(best,r-left+1)\n    return best", 'explanation_md' => 'Sliding window of distinct chars. O(n).'],
    'php' => ['code' => "<?php\nfunction longest(string \$s): int {\n    \$seen=[]; \$left=0; \$best=0;\n    for(\$r=0;\$r<strlen(\$s);\$r++){\n        \$c=\$s[\$r];\n        if(isset(\$seen[\$c]) && \$seen[\$c]>=\$left) \$left=\$seen[\$c]+1;\n        \$seen[\$c]=\$r; \$best=max(\$best,\$r-\$left+1);\n    }\n    return \$best;\n}"],
    'java' => ['code' => "int longest(String s){\n    Map<Character,Integer> seen=new HashMap<>();\n    int left=0,best=0;\n    for(int r=0;r<s.length();r++){\n        char c=s.charAt(r);\n        if(seen.containsKey(c)&&seen.get(c)>=left) left=seen.get(c)+1;\n        seen.put(c,r); best=Math.max(best,r-left+1);\n    }\n    return best;\n}"],
    'cpp' => ['code' => "int longest(string s){\n    unordered_map<char,int> seen; int left=0,best=0;\n    for(int r=0;r<(int)s.size();r++){\n        char c=s[r];\n        if(seen.count(c)&&seen[c]>=left) left=seen[c]+1;\n        seen[c]=r; best=max(best,r-left+1);\n    }\n    return best;\n}"],
],

// ===================== SEARCHING AND SORTING =====================
'Searching and Sorting::Searching an element in a sorted array' => [
    'python' => ['code' => "def bsearch(a,x):\n    lo,hi=0,len(a)-1\n    while lo<=hi:\n        m=(lo+hi)//2\n        if a[m]==x: return m\n        if a[m]<x: lo=m+1\n        else: hi=m-1\n    return -1", 'explanation_md' => 'Binary search, O(log n).'],
    'php' => ['code' => "<?php\nfunction bsearch(array \$a,int \$x): int {\n    \$lo=0; \$hi=count(\$a)-1;\n    while(\$lo<=\$hi){ \$m=intdiv(\$lo+\$hi,2);\n        if(\$a[\$m]===\$x) return \$m;\n        if(\$a[\$m]<\$x) \$lo=\$m+1; else \$hi=\$m-1;\n    }\n    return -1;\n}"],
    'java' => ['code' => "int bsearch(int[] a,int x){\n    int lo=0,hi=a.length-1;\n    while(lo<=hi){ int m=lo+(hi-lo)/2;\n        if(a[m]==x) return m;\n        if(a[m]<x) lo=m+1; else hi=m-1;\n    }\n    return -1;\n}"],
    'cpp' => ['code' => "int bsearch(vector<int>& a,int x){\n    int lo=0,hi=a.size()-1;\n    while(lo<=hi){ int m=lo+(hi-lo)/2;\n        if(a[m]==x) return m;\n        if(a[m]<x) lo=m+1; else hi=m-1;\n    }\n    return -1;\n}"],
],
'Searching and Sorting::Bubble Sort' => [
    'python' => ['code' => "def bubble(a):\n    n=len(a)\n    for i in range(n):\n        for j in range(n-1-i):\n            if a[j]>a[j+1]: a[j],a[j+1]=a[j+1],a[j]\n    return a", 'explanation_md' => 'O(n²).'],
    'php' => ['code' => "<?php\nfunction bubble(array \$a): array {\n    \$n=count(\$a);\n    for(\$i=0;\$i<\$n;\$i++) for(\$j=0;\$j<\$n-1-\$i;\$j++)\n        if(\$a[\$j]>\$a[\$j+1]){ [\$a[\$j],\$a[\$j+1]]=[\$a[\$j+1],\$a[\$j]]; }\n    return \$a;\n}"],
    'java' => ['code' => "void bubble(int[] a){\n    int n=a.length;\n    for(int i=0;i<n;i++) for(int j=0;j<n-1-i;j++)\n        if(a[j]>a[j+1]){int t=a[j];a[j]=a[j+1];a[j+1]=t;}\n}"],
    'cpp' => ['code' => "void bubble(vector<int>& a){\n    int n=a.size();\n    for(int i=0;i<n;i++) for(int j=0;j<n-1-i;j++)\n        if(a[j]>a[j+1]) swap(a[j],a[j+1]);\n}"],
],
'Searching and Sorting::Merge Sort' => [
    'python' => ['code' => "def merge_sort(a):\n    if len(a)<=1: return a\n    m=len(a)//2\n    L,R=merge_sort(a[:m]),merge_sort(a[m:])\n    res=[]; i=j=0\n    while i<len(L) and j<len(R):\n        if L[i]<=R[j]: res.append(L[i]); i+=1\n        else: res.append(R[j]); j+=1\n    return res+L[i:]+R[j:]", 'explanation_md' => 'Divide & conquer, O(n log n), stable.'],
    'php' => ['code' => "<?php\nfunction mergeSort(array \$a): array {\n    if(count(\$a)<=1) return \$a;\n    \$m=intdiv(count(\$a),2);\n    \$L=mergeSort(array_slice(\$a,0,\$m)); \$R=mergeSort(array_slice(\$a,\$m));\n    \$res=[]; \$i=\$j=0;\n    while(\$i<count(\$L)&&\$j<count(\$R)) \$res[]= \$L[\$i]<=\$R[\$j] ? \$L[\$i++] : \$R[\$j++];\n    return array_merge(\$res, array_slice(\$L,\$i), array_slice(\$R,\$j));\n}"],
    'java' => ['code' => "int[] mergeSort(int[] a){\n    if(a.length<=1) return a;\n    int m=a.length/2;\n    int[] L=mergeSort(Arrays.copyOfRange(a,0,m));\n    int[] R=mergeSort(Arrays.copyOfRange(a,m,a.length));\n    int[] res=new int[a.length]; int i=0,j=0,k=0;\n    while(i<L.length&&j<R.length) res[k++]= L[i]<=R[j]?L[i++]:R[j++];\n    while(i<L.length) res[k++]=L[i++];\n    while(j<R.length) res[k++]=R[j++];\n    return res;\n}"],
    'cpp' => ['code' => "vector<int> mergeSort(vector<int> a){\n    if(a.size()<=1) return a;\n    int m=a.size()/2;\n    auto L=mergeSort(vector<int>(a.begin(),a.begin()+m));\n    auto R=mergeSort(vector<int>(a.begin()+m,a.end()));\n    vector<int> res; size_t i=0,j=0;\n    while(i<L.size()&&j<R.size()) res.push_back(L[i]<=R[j]?L[i++]:R[j++]);\n    while(i<L.size()) res.push_back(L[i++]);\n    while(j<R.size()) res.push_back(R[j++]);\n    return res;\n}"],
],
'Searching and Sorting::Kth smallest element' => [
    'python' => ['code' => "import heapq\ndef kth_smallest(a,k):\n    return heapq.nsmallest(k,a)[-1]", 'explanation_md' => 'A size-k max-heap (or nsmallest) gives O(n log k).'],
    'php' => ['code' => "<?php\nfunction kthSmallest(array \$a,int \$k): int {\n    \$h=new SplMaxHeap();\n    foreach(\$a as \$v){ \$h->insert(\$v); if(count(\$h)>\$k) \$h->extract(); }\n    return \$h->top();\n}"],
    'java' => ['code' => "int kthSmallest(int[] a,int k){\n    PriorityQueue<Integer> pq=new PriorityQueue<>(Collections.reverseOrder());\n    for(int v:a){ pq.offer(v); if(pq.size()>k) pq.poll(); }\n    return pq.peek();\n}"],
    'cpp' => ['code' => "int kthSmallest(vector<int>& a,int k){\n    priority_queue<int> pq; // max-heap of size k\n    for(int v:a){ pq.push(v); if((int)pq.size()>k) pq.pop(); }\n    return pq.top();\n}"],
],

// ===================== LINKEDLIST =====================
'LinkedList::Reverse a linked list' => [
    'python' => ['code' => "def reverse(head):\n    prev=None\n    while head:\n        head.next, prev, head = prev, head, head.next\n    return prev", 'explanation_md' => 'Flip pointers; O(n), O(1).'],
    'php' => ['code' => "<?php\nfunction reverse(?Node \$head): ?Node {\n    \$prev=null;\n    while(\$head){ \$nx=\$head->next; \$head->next=\$prev; \$prev=\$head; \$head=\$nx; }\n    return \$prev;\n}"],
    'java' => ['code' => "Node reverse(Node head){\n    Node prev=null;\n    while(head!=null){ Node nx=head.next; head.next=prev; prev=head; head=nx; }\n    return prev;\n}"],
    'cpp' => ['code' => "Node* reverse(Node* head){\n    Node* prev=nullptr;\n    while(head){ Node* nx=head->next; head->next=prev; prev=head; head=nx; }\n    return prev;\n}"],
],
'LinkedList::Detect Loop in linked list' => [
    'python' => ['code' => "def has_cycle(head):\n    slow=fast=head\n    while fast and fast.next:\n        slow=slow.next; fast=fast.next.next\n        if slow is fast: return True\n    return False", 'explanation_md' => "Floyd's tortoise & hare. O(n), O(1)."],
    'php' => ['code' => "<?php\nfunction hasCycle(?Node \$head): bool {\n    \$slow=\$fast=\$head;\n    while(\$fast && \$fast->next){ \$slow=\$slow->next; \$fast=\$fast->next->next; if(\$slow===\$fast) return true; }\n    return false;\n}"],
    'java' => ['code' => "boolean hasCycle(Node head){\n    Node slow=head,fast=head;\n    while(fast!=null&&fast.next!=null){ slow=slow.next; fast=fast.next.next; if(slow==fast) return true; }\n    return false;\n}"],
    'cpp' => ['code' => "bool hasCycle(Node* head){\n    Node *slow=head,*fast=head;\n    while(fast&&fast->next){ slow=slow->next; fast=fast->next->next; if(slow==fast) return true; }\n    return false;\n}"],
],
'LinkedList::Merge two sorted linked lists' => [
    'python' => ['code' => "def merge(a,b):\n    dummy=tail=Node(0)\n    while a and b:\n        if a.val<=b.val: tail.next=a; a=a.next\n        else: tail.next=b; b=b.next\n        tail=tail.next\n    tail.next=a or b\n    return dummy.next", 'explanation_md' => 'Splice nodes by value. O(n+m).'],
    'php' => ['code' => "<?php\nfunction merge(?Node \$a, ?Node \$b): ?Node {\n    \$dummy=new Node(0); \$tail=\$dummy;\n    while(\$a && \$b){ if(\$a->val<=\$b->val){ \$tail->next=\$a; \$a=\$a->next; } else { \$tail->next=\$b; \$b=\$b->next; } \$tail=\$tail->next; }\n    \$tail->next = \$a ?? \$b;\n    return \$dummy->next;\n}"],
    'java' => ['code' => "Node merge(Node a, Node b){\n    Node dummy=new Node(0), tail=dummy;\n    while(a!=null&&b!=null){ if(a.val<=b.val){ tail.next=a; a=a.next; } else { tail.next=b; b=b.next; } tail=tail.next; }\n    tail.next = (a!=null)?a:b;\n    return dummy.next;\n}"],
    'cpp' => ['code' => "Node* merge(Node* a, Node* b){\n    Node dummy(0); Node* tail=&dummy;\n    while(a&&b){ if(a->val<=b->val){ tail->next=a; a=a->next; } else { tail->next=b; b=b->next; } tail=tail->next; }\n    tail->next = a?a:b;\n    return dummy.next;\n}"],
],

// ===================== STACK =====================
'Stack::Parenthesis Checker' => [
    'python' => ['code' => "def is_valid(s):\n    pairs={')':'(',']':'[','}':'{'}; st=[]\n    for c in s:\n        if c in '([{': st.append(c)\n        elif not st or st.pop()!=pairs[c]: return False\n    return not st", 'explanation_md' => 'Stack of openers. O(n).'],
    'php' => ['code' => "<?php\nfunction isValid(string \$s): bool {\n    \$p=[')'=>'(',']'=>'[','}'=>'{']; \$st=[];\n    foreach(str_split(\$s) as \$c){\n        if(in_array(\$c,['(','[','{'],true)) \$st[]=\$c;\n        elseif(!\$st || array_pop(\$st)!==\$p[\$c]) return false;\n    }\n    return empty(\$st);\n}"],
    'java' => ['code' => "boolean isValid(String s){\n    Deque<Character> st=new ArrayDeque<>();\n    for(char c:s.toCharArray()){\n        if(c=='(') st.push(')'); else if(c=='[') st.push(']'); else if(c=='{') st.push('}');\n        else if(st.isEmpty()||st.pop()!=c) return false;\n    }\n    return st.isEmpty();\n}"],
    'cpp' => ['code' => "bool isValid(string s){\n    stack<char> st;\n    for(char c:s){\n        if(c=='(') st.push(')'); else if(c=='[') st.push(']'); else if(c=='{') st.push('}');\n        else if(st.empty()||st.top()!=c) return false; else st.pop();\n    }\n    return st.empty();\n}"],
],
'Stack::Next Greater Element' => [
    'python' => ['code' => "def next_greater(a):\n    res=[-1]*len(a); st=[]\n    for i in range(len(a)-1,-1,-1):\n        while st and st[-1]<=a[i]: st.pop()\n        if st: res[i]=st[-1]\n        st.append(a[i])\n    return res", 'explanation_md' => 'Monotonic stack from the right. O(n).'],
    'php' => ['code' => "<?php\nfunction nextGreater(array \$a): array {\n    \$n=count(\$a); \$res=array_fill(0,\$n,-1); \$st=[];\n    for(\$i=\$n-1;\$i>=0;\$i--){\n        while(\$st && end(\$st)<=\$a[\$i]) array_pop(\$st);\n        if(\$st) \$res[\$i]=end(\$st);\n        \$st[]=\$a[\$i];\n    }\n    return \$res;\n}"],
    'java' => ['code' => "int[] nextGreater(int[] a){\n    int n=a.length; int[] res=new int[n]; Arrays.fill(res,-1);\n    Deque<Integer> st=new ArrayDeque<>();\n    for(int i=n-1;i>=0;i--){\n        while(!st.isEmpty()&&st.peek()<=a[i]) st.pop();\n        if(!st.isEmpty()) res[i]=st.peek();\n        st.push(a[i]);\n    }\n    return res;\n}"],
    'cpp' => ['code' => "vector<int> nextGreater(vector<int>& a){\n    int n=a.size(); vector<int> res(n,-1); stack<int> st;\n    for(int i=n-1;i>=0;i--){\n        while(!st.empty()&&st.top()<=a[i]) st.pop();\n        if(!st.empty()) res[i]=st.top();\n        st.push(a[i]);\n    }\n    return res;\n}"],
],

// ===================== QUEUE =====================
'Queue::Sliding Window Maximum' => [
    'python' => ['code' => "from collections import deque\ndef max_window(a,k):\n    dq=deque(); res=[]\n    for i,v in enumerate(a):\n        while dq and a[dq[-1]]<=v: dq.pop()\n        dq.append(i)\n        if dq[0]==i-k: dq.popleft()\n        if i>=k-1: res.append(a[dq[0]])\n    return res", 'explanation_md' => 'Monotonic deque of indices. O(n).'],
    'php' => ['code' => "<?php\nfunction maxWindow(array \$a,int \$k): array {\n    \$dq=[]; \$res=[];\n    foreach(\$a as \$i=>\$v){\n        while(\$dq && \$a[end(\$dq)]<=\$v) array_pop(\$dq);\n        \$dq[]=\$i;\n        if(\$dq[0]==\$i-\$k) array_shift(\$dq);\n        if(\$i>=\$k-1) \$res[]=\$a[\$dq[0]];\n    }\n    return \$res;\n}"],
    'java' => ['code' => "int[] maxWindow(int[] a,int k){\n    Deque<Integer> dq=new ArrayDeque<>(); int n=a.length;\n    int[] res=new int[n-k+1]; int ri=0;\n    for(int i=0;i<n;i++){\n        while(!dq.isEmpty()&&a[dq.peekLast()]<=a[i]) dq.pollLast();\n        dq.addLast(i);\n        if(dq.peekFirst()==i-k) dq.pollFirst();\n        if(i>=k-1) res[ri++]=a[dq.peekFirst()];\n    }\n    return res;\n}"],
    'cpp' => ['code' => "vector<int> maxWindow(vector<int>& a,int k){\n    deque<int> dq; vector<int> res;\n    for(int i=0;i<(int)a.size();i++){\n        while(!dq.empty()&&a[dq.back()]<=a[i]) dq.pop_back();\n        dq.push_back(i);\n        if(dq.front()==i-k) dq.pop_front();\n        if(i>=k-1) res.push_back(a[dq.front()]);\n    }\n    return res;\n}"],
],
'Queue::Queue using two Stacks' => [
    'python' => ['code' => "class MyQueue:\n    def __init__(self): self.a=[]; self.b=[]\n    def push(self,x): self.a.append(x)\n    def _move(self):\n        if not self.b:\n            while self.a: self.b.append(self.a.pop())\n    def pop(self): self._move(); return self.b.pop()\n    def peek(self): self._move(); return self.b[-1]", 'explanation_md' => 'Amortized O(1) per op.'],
    'php' => ['code' => "<?php\nclass MyQueue {\n    private array \$a=[], \$b=[];\n    function push(\$x){ \$this->a[]=\$x; }\n    private function move(){ if(!\$this->b) while(\$this->a) \$this->b[]=array_pop(\$this->a); }\n    function pop(){ \$this->move(); return array_pop(\$this->b); }\n    function peek(){ \$this->move(); return end(\$this->b); }\n}"],
    'java' => ['code' => "class MyQueue {\n    Deque<Integer> a=new ArrayDeque<>(), b=new ArrayDeque<>();\n    void push(int x){ a.push(x); }\n    void move(){ if(b.isEmpty()) while(!a.isEmpty()) b.push(a.pop()); }\n    int pop(){ move(); return b.pop(); }\n    int peek(){ move(); return b.peek(); }\n}"],
    'cpp' => ['code' => "class MyQueue {\n    stack<int> a,b;\n    void move(){ if(b.empty()) while(!a.empty()){ b.push(a.top()); a.pop(); } }\npublic:\n    void push(int x){ a.push(x); }\n    int pop(){ move(); int v=b.top(); b.pop(); return v; }\n    int peek(){ move(); return b.top(); }\n};"],
],

// ===================== TREE =====================
'Tree::Inorder Traversal' => [
    'python' => ['code' => "def inorder(root,out):\n    if not root: return\n    inorder(root.left,out); out.append(root.val); inorder(root.right,out)", 'explanation_md' => 'Left, Root, Right. O(n).'],
    'php' => ['code' => "<?php\nfunction inorder(?TreeNode \$root, array &\$out): void {\n    if(!\$root) return;\n    inorder(\$root->left,\$out); \$out[]=\$root->val; inorder(\$root->right,\$out);\n}"],
    'java' => ['code' => "void inorder(TreeNode root, List<Integer> out){\n    if(root==null) return;\n    inorder(root.left,out); out.add(root.val); inorder(root.right,out);\n}"],
    'cpp' => ['code' => "void inorder(TreeNode* root, vector<int>& out){\n    if(!root) return;\n    inorder(root->left,out); out.push_back(root->val); inorder(root->right,out);\n}"],
],
'Tree::Level order traversal' => [
    'python' => ['code' => "from collections import deque\ndef level_order(root):\n    res=[]; q=deque([root] if root else [])\n    while q:\n        n=q.popleft(); res.append(n.val)\n        if n.left: q.append(n.left)\n        if n.right: q.append(n.right)\n    return res", 'explanation_md' => 'BFS with a queue. O(n).'],
    'php' => ['code' => "<?php\nfunction levelOrder(?TreeNode \$root): array {\n    \$res=[]; \$q=\$root?[\$root]:[];\n    while(\$q){ \$n=array_shift(\$q); \$res[]=\$n->val;\n        if(\$n->left) \$q[]=\$n->left; if(\$n->right) \$q[]=\$n->right; }\n    return \$res;\n}"],
    'java' => ['code' => "List<Integer> levelOrder(TreeNode root){\n    List<Integer> res=new ArrayList<>(); if(root==null) return res;\n    Queue<TreeNode> q=new LinkedList<>(); q.add(root);\n    while(!q.isEmpty()){ TreeNode n=q.poll(); res.add(n.val);\n        if(n.left!=null) q.add(n.left); if(n.right!=null) q.add(n.right); }\n    return res;\n}"],
    'cpp' => ['code' => "vector<int> levelOrder(TreeNode* root){\n    vector<int> res; if(!root) return res;\n    queue<TreeNode*> q; q.push(root);\n    while(!q.empty()){ TreeNode* n=q.front(); q.pop(); res.push_back(n->val);\n        if(n->left) q.push(n->left); if(n->right) q.push(n->right); }\n    return res;\n}"],
],
'Tree::Height of Binary Tree' => [
    'python' => ['code' => "def height(root):\n    if not root: return 0\n    return 1+max(height(root.left),height(root.right))", 'explanation_md' => 'O(n).'],
    'php' => ['code' => "<?php\nfunction height(?TreeNode \$root): int {\n    if(!\$root) return 0;\n    return 1+max(height(\$root->left),height(\$root->right));\n}"],
    'java' => ['code' => "int height(TreeNode root){\n    if(root==null) return 0;\n    return 1+Math.max(height(root.left),height(root.right));\n}"],
    'cpp' => ['code' => "int height(TreeNode* root){\n    if(!root) return 0;\n    return 1+max(height(root->left),height(root->right));\n}"],
],

// ===================== BINARY SEARCH TREE =====================
'Binary Search Tree::Search a node in BST' => [
    'python' => ['code' => "def search(root,key):\n    while root and root.val!=key:\n        root = root.left if key<root.val else root.right\n    return root", 'explanation_md' => 'O(h).'],
    'php' => ['code' => "<?php\nfunction search(?TreeNode \$root,int \$key): ?TreeNode {\n    while(\$root && \$root->val!==\$key) \$root = \$key<\$root->val ? \$root->left : \$root->right;\n    return \$root;\n}"],
    'java' => ['code' => "TreeNode search(TreeNode root,int key){\n    while(root!=null&&root.val!=key) root = key<root.val?root.left:root.right;\n    return root;\n}"],
    'cpp' => ['code' => "TreeNode* search(TreeNode* root,int key){\n    while(root&&root->val!=key) root = key<root->val?root->left:root->right;\n    return root;\n}"],
],
'Binary Search Tree::Check for BST' => [
    'python' => ['code' => "def is_bst(root,lo=float('-inf'),hi=float('inf')):\n    if not root: return True\n    if not (lo<root.val<hi): return False\n    return is_bst(root.left,lo,root.val) and is_bst(root.right,root.val,hi)", 'explanation_md' => 'Range validation. O(n).'],
    'php' => ['code' => "<?php\nfunction isBST(?TreeNode \$root,float \$lo=-INF,float \$hi=INF): bool {\n    if(!\$root) return true;\n    if(!(\$lo<\$root->val && \$root->val<\$hi)) return false;\n    return isBST(\$root->left,\$lo,\$root->val) && isBST(\$root->right,\$root->val,\$hi);\n}"],
    'java' => ['code' => "boolean isBST(TreeNode r,long lo,long hi){\n    if(r==null) return true;\n    if(r.val<=lo||r.val>=hi) return false;\n    return isBST(r.left,lo,r.val) && isBST(r.right,r.val,hi);\n}"],
    'cpp' => ['code' => "bool isBST(TreeNode* r,long lo=LONG_MIN,long hi=LONG_MAX){\n    if(!r) return true;\n    if(r->val<=lo||r->val>=hi) return false;\n    return isBST(r->left,lo,r->val) && isBST(r->right,r->val,hi);\n}"],
],

// ===================== HEAPS =====================
'Heaps::Heap Sort' => [
    'python' => ['code' => "import heapq\ndef heap_sort(a):\n    h=a[:]; heapq.heapify(h)\n    return [heapq.heappop(h) for _ in range(len(h))]", 'explanation_md' => 'O(n log n).'],
    'php' => ['code' => "<?php\nfunction heapSort(array \$a): array {\n    \$h=new SplMinHeap();\n    foreach(\$a as \$v) \$h->insert(\$v);\n    \$res=[]; while(!\$h->isEmpty()) \$res[]=\$h->extract();\n    return \$res;\n}"],
    'java' => ['code' => "int[] heapSort(int[] a){\n    PriorityQueue<Integer> pq=new PriorityQueue<>();\n    for(int v:a) pq.offer(v);\n    int[] res=new int[a.length];\n    for(int i=0;i<res.length;i++) res[i]=pq.poll();\n    return res;\n}"],
    'cpp' => ['code' => "vector<int> heapSort(vector<int> a){\n    make_heap(a.begin(),a.end()); // max-heap\n    sort_heap(a.begin(),a.end()); // ascending\n    return a;\n}"],
],
'Heaps::k largest elements' => [
    'python' => ['code' => "import heapq\ndef k_largest(a,k):\n    return heapq.nlargest(k,a)", 'explanation_md' => 'Size-k min-heap, O(n log k).'],
    'php' => ['code' => "<?php\nfunction kLargest(array \$a,int \$k): array {\n    \$h=new SplMinHeap();\n    foreach(\$a as \$v){ \$h->insert(\$v); if(count(\$h)>\$k) \$h->extract(); }\n    return iterator_to_array(\$h);\n}"],
    'java' => ['code' => "List<Integer> kLargest(int[] a,int k){\n    PriorityQueue<Integer> pq=new PriorityQueue<>();\n    for(int v:a){ pq.offer(v); if(pq.size()>k) pq.poll(); }\n    return new ArrayList<>(pq);\n}"],
    'cpp' => ['code' => "vector<int> kLargest(vector<int>& a,int k){\n    priority_queue<int,vector<int>,greater<>> pq;\n    for(int v:a){ pq.push(v); if((int)pq.size()>k) pq.pop(); }\n    vector<int> r; while(!pq.empty()){ r.push_back(pq.top()); pq.pop(); }\n    return r;\n}"],
],

// ===================== GREEDY =====================
'Greedy::N meetings in one room' => [
    'python' => ['code' => "def max_meetings(start,end):\n    meetings=sorted(zip(end,start))\n    count=0; last=float('-inf')\n    for e,s in meetings:\n        if s>last: count+=1; last=e\n    return count", 'explanation_md' => 'Sort by end time, pick non-overlapping. O(n log n).'],
    'php' => ['code' => "<?php\nfunction maxMeetings(array \$start,array \$end): int {\n    \$m=[]; foreach(\$start as \$i=>\$s) \$m[]=[\$s,\$end[\$i]];\n    usort(\$m, fn(\$a,\$b)=>\$a[1]<=>\$b[1]);\n    \$count=0; \$last=-INF;\n    foreach(\$m as [\$s,\$e]) if(\$s>\$last){ \$count++; \$last=\$e; }\n    return \$count;\n}"],
    'java' => ['code' => "int maxMeetings(int[] s,int[] e){\n    Integer[] idx=new Integer[s.length];\n    for(int i=0;i<s.length;i++) idx[i]=i;\n    Arrays.sort(idx,(a,b)->e[a]-e[b]);\n    int count=0,last=Integer.MIN_VALUE;\n    for(int i:idx) if(s[i]>last){ count++; last=e[i]; }\n    return count;\n}"],
    'cpp' => ['code' => "int maxMeetings(vector<int>& s,vector<int>& e){\n    int n=s.size(); vector<int> idx(n); iota(idx.begin(),idx.end(),0);\n    sort(idx.begin(),idx.end(),[&](int a,int b){ return e[a]<e[b]; });\n    int count=0,last=INT_MIN;\n    for(int i:idx) if(s[i]>last){ count++; last=e[i]; }\n    return count;\n}"],
],
'Greedy::Fractional Knapsack' => [
    'python' => ['code' => "def frac_knapsack(items,W):  # items: (value, weight)\n    items.sort(key=lambda it: it[0]/it[1], reverse=True)\n    total=0.0\n    for v,w in items:\n        if W>=w: total+=v; W-=w\n        else: total+=v*(W/w); break\n    return total", 'explanation_md' => 'Sort by value/weight ratio. O(n log n).'],
    'php' => ['code' => "<?php\nfunction fracKnapsack(array \$items,int \$W): float {\n    usort(\$items, fn(\$a,\$b)=>(\$b[0]/\$b[1])<=>(\$a[0]/\$a[1]));\n    \$total=0.0;\n    foreach(\$items as [\$v,\$w]){\n        if(\$W>=\$w){ \$total+=\$v; \$W-=\$w; }\n        else { \$total+=\$v*(\$W/\$w); break; }\n    }\n    return \$total;\n}"],
    'java' => ['code' => "double fracKnapsack(double[][] items,int W){\n    Arrays.sort(items,(a,b)->Double.compare(b[0]/b[1],a[0]/a[1]));\n    double total=0;\n    for(double[] it:items){\n        if(W>=it[1]){ total+=it[0]; W-=it[1]; }\n        else { total+=it[0]*(W/it[1]); break; }\n    }\n    return total;\n}"],
    'cpp' => ['code' => "double fracKnapsack(vector<pair<double,double>> it,int W){\n    sort(it.begin(),it.end(),[](auto&a,auto&b){ return a.first/a.second>b.first/b.second; });\n    double total=0;\n    for(auto&[v,w]:it){\n        if(W>=w){ total+=v; W-=w; }\n        else { total+=v*(W/w); break; }\n    }\n    return total;\n}"],
],

// ===================== BACKTRACKING =====================
'BackTracking::Permutations' => [
    'python' => ['code' => "def permute(nums):\n    res=[]\n    def bt(path,used):\n        if len(path)==len(nums): res.append(path[:]); return\n        for i,v in enumerate(nums):\n            if used[i]: continue\n            used[i]=True; path.append(v)\n            bt(path,used)\n            path.pop(); used[i]=False\n    bt([],[False]*len(nums))\n    return res", 'explanation_md' => 'O(n·n!).'],
    'php' => ['code' => "<?php\nfunction permute(array \$nums): array {\n    \$res=[]; \$used=array_fill(0,count(\$nums),false);\n    \$bt=function(\$path) use (&\$bt,&\$res,&\$used,\$nums){\n        if(count(\$path)===count(\$nums)){ \$res[]=\$path; return; }\n        foreach(\$nums as \$i=>\$v){ if(\$used[\$i]) continue;\n            \$used[\$i]=true; \$bt(array_merge(\$path,[\$v])); \$used[\$i]=false; }\n    };\n    \$bt([]);\n    return \$res;\n}"],
    'java' => ['code' => "List<List<Integer>> permute(int[] nums){\n    List<List<Integer>> res=new ArrayList<>();\n    bt(nums,new ArrayList<>(),new boolean[nums.length],res);\n    return res;\n}\nvoid bt(int[] nums,List<Integer> path,boolean[] used,List<List<Integer>> res){\n    if(path.size()==nums.length){ res.add(new ArrayList<>(path)); return; }\n    for(int i=0;i<nums.length;i++){ if(used[i]) continue;\n        used[i]=true; path.add(nums[i]); bt(nums,path,used,res);\n        path.remove(path.size()-1); used[i]=false; }\n}"],
    'cpp' => ['code' => "void bt(vector<int>& n,vector<int>& path,vector<bool>& used,vector<vector<int>>& res){\n    if(path.size()==n.size()){ res.push_back(path); return; }\n    for(int i=0;i<(int)n.size();i++){ if(used[i]) continue;\n        used[i]=true; path.push_back(n[i]); bt(n,path,used,res);\n        path.pop_back(); used[i]=false; }\n}\nvector<vector<int>> permute(vector<int>& nums){\n    vector<vector<int>> res; vector<int> path; vector<bool> used(nums.size(),false);\n    bt(nums,path,used,res); return res;\n}"],
],
'BackTracking::Unique Subsets' => [
    'python' => ['code' => "def subsets(nums):\n    nums.sort(); res=[]\n    def bt(start,path):\n        res.append(path[:])\n        for i in range(start,len(nums)):\n            if i>start and nums[i]==nums[i-1]: continue\n            path.append(nums[i]); bt(i+1,path); path.pop()\n    bt(0,[])\n    return res", 'explanation_md' => 'Sort, skip duplicates. O(2ⁿ).'],
    'php' => ['code' => "<?php\nfunction subsetsUnique(array \$nums): array {\n    sort(\$nums); \$res=[];\n    \$bt=function(\$start,\$path) use (&\$bt,&\$res,\$nums){\n        \$res[]=\$path;\n        for(\$i=\$start;\$i<count(\$nums);\$i++){\n            if(\$i>\$start && \$nums[\$i]===\$nums[\$i-1]) continue;\n            \$bt(\$i+1, array_merge(\$path,[\$nums[\$i]]));\n        }\n    };\n    \$bt(0,[]);\n    return \$res;\n}"],
    'java' => ['code' => "List<List<Integer>> subsets(int[] nums){\n    Arrays.sort(nums); List<List<Integer>> res=new ArrayList<>();\n    bt(nums,0,new ArrayList<>(),res); return res;\n}\nvoid bt(int[] n,int start,List<Integer> path,List<List<Integer>> res){\n    res.add(new ArrayList<>(path));\n    for(int i=start;i<n.length;i++){\n        if(i>start&&n[i]==n[i-1]) continue;\n        path.add(n[i]); bt(n,i+1,path,res); path.remove(path.size()-1);\n    }\n}"],
    'cpp' => ['code' => "void bt(vector<int>& n,int start,vector<int>& path,vector<vector<int>>& res){\n    res.push_back(path);\n    for(int i=start;i<(int)n.size();i++){\n        if(i>start&&n[i]==n[i-1]) continue;\n        path.push_back(n[i]); bt(n,i+1,path,res); path.pop_back();\n    }\n}\nvector<vector<int>> subsets(vector<int>& nums){\n    sort(nums.begin(),nums.end()); vector<vector<int>> res; vector<int> path;\n    bt(nums,0,path,res); return res;\n}"],
],
'BackTracking::Generate Parentheses' => [
    'python' => ['code' => "def generate(n):\n    res=[]\n    def bt(s,open_,close):\n        if len(s)==2*n: res.append(s); return\n        if open_<n: bt(s+'(',open_+1,close)\n        if close<open_: bt(s+')',open_,close+1)\n    bt('',0,0)\n    return res", 'explanation_md' => 'Add ( while available, ) while it stays valid.'],
    'php' => ['code' => "<?php\nfunction generate(int \$n): array {\n    \$res=[];\n    \$bt=function(\$s,\$o,\$c) use (&\$bt,&\$res,\$n){\n        if(strlen(\$s)===2*\$n){ \$res[]=\$s; return; }\n        if(\$o<\$n) \$bt(\$s.'(',\$o+1,\$c);\n        if(\$c<\$o) \$bt(\$s.')',\$o,\$c+1);\n    };\n    \$bt('',0,0);\n    return \$res;\n}"],
    'java' => ['code' => "List<String> generate(int n){\n    List<String> res=new ArrayList<>(); bt(res,\"\",0,0,n); return res;\n}\nvoid bt(List<String> res,String s,int o,int c,int n){\n    if(s.length()==2*n){ res.add(s); return; }\n    if(o<n) bt(res,s+\"(\",o+1,c,n);\n    if(c<o) bt(res,s+\")\",o,c+1,n);\n}"],
    'cpp' => ['code' => "void bt(vector<string>& res,string s,int o,int c,int n){\n    if((int)s.size()==2*n){ res.push_back(s); return; }\n    if(o<n) bt(res,s+\"(\",o+1,c,n);\n    if(c<o) bt(res,s+\")\",o,c+1,n);\n}\nvector<string> generate(int n){ vector<string> res; bt(res,\"\",0,0,n); return res; }"],
],

// ===================== HASHING =====================
'Hashing::2 Sum' => [
    'python' => ['code' => "def two_sum(nums,target):\n    seen={}\n    for i,n in enumerate(nums):\n        if target-n in seen: return [seen[target-n],i]\n        seen[n]=i\n    return []", 'explanation_md' => 'Complement lookup. O(n).'],
    'php' => ['code' => "<?php\nfunction twoSum(array \$nums,int \$target): array {\n    \$seen=[];\n    foreach(\$nums as \$i=>\$n){\n        if(isset(\$seen[\$target-\$n])) return [\$seen[\$target-\$n],\$i];\n        \$seen[\$n]=\$i;\n    }\n    return [];\n}"],
    'java' => ['code' => "int[] twoSum(int[] nums,int target){\n    Map<Integer,Integer> seen=new HashMap<>();\n    for(int i=0;i<nums.length;i++){\n        if(seen.containsKey(target-nums[i])) return new int[]{seen.get(target-nums[i]),i};\n        seen.put(nums[i],i);\n    }\n    return new int[0];\n}"],
    'cpp' => ['code' => "vector<int> twoSum(vector<int>& nums,int target){\n    unordered_map<int,int> seen;\n    for(int i=0;i<(int)nums.size();i++){\n        if(seen.count(target-nums[i])) return {seen[target-nums[i]],i};\n        seen[nums[i]]=i;\n    }\n    return {};\n}"],
],
'Hashing::Largest subarray with 0 sum' => [
    'python' => ['code' => "def max_len(a):\n    seen={}; s=0; best=0\n    for i,v in enumerate(a):\n        s+=v\n        if s==0: best=i+1\n        elif s in seen: best=max(best,i-seen[s])\n        else: seen[s]=i\n    return best", 'explanation_md' => 'First index of each prefix sum. O(n).'],
    'php' => ['code' => "<?php\nfunction maxLen(array \$a): int {\n    \$seen=[]; \$s=0; \$best=0;\n    foreach(\$a as \$i=>\$v){ \$s+=\$v;\n        if(\$s===0) \$best=\$i+1;\n        elseif(isset(\$seen[\$s])) \$best=max(\$best,\$i-\$seen[\$s]);\n        else \$seen[\$s]=\$i;\n    }\n    return \$best;\n}"],
    'java' => ['code' => "int maxLen(int[] a){\n    Map<Integer,Integer> seen=new HashMap<>(); int s=0,best=0;\n    for(int i=0;i<a.length;i++){ s+=a[i];\n        if(s==0) best=i+1;\n        else if(seen.containsKey(s)) best=Math.max(best,i-seen.get(s));\n        else seen.put(s,i);\n    }\n    return best;\n}"],
    'cpp' => ['code' => "int maxLen(vector<int>& a){\n    unordered_map<int,int> seen; int s=0,best=0;\n    for(int i=0;i<(int)a.size();i++){ s+=a[i];\n        if(s==0) best=i+1;\n        else if(seen.count(s)) best=max(best,i-seen[s]);\n        else seen[s]=i;\n    }\n    return best;\n}"],
],

// ===================== GRAPHS =====================
'Graphs::BFS of graph' => [
    'python' => ['code' => "from collections import deque\ndef bfs(adj,start):\n    seen={start}; q=deque([start]); order=[]\n    while q:\n        u=q.popleft(); order.append(u)\n        for v in adj[u]:\n            if v not in seen: seen.add(v); q.append(v)\n    return order", 'explanation_md' => 'Queue-based. O(V+E).'],
    'php' => ['code' => "<?php\nfunction bfs(array \$adj,int \$start): array {\n    \$seen=[\$start=>true]; \$q=[\$start]; \$order=[];\n    while(\$q){ \$u=array_shift(\$q); \$order[]=\$u;\n        foreach(\$adj[\$u]??[] as \$v) if(!isset(\$seen[\$v])){ \$seen[\$v]=true; \$q[]=\$v; } }\n    return \$order;\n}"],
    'java' => ['code' => "List<Integer> bfs(List<List<Integer>> adj,int start){\n    List<Integer> order=new ArrayList<>(); boolean[] seen=new boolean[adj.size()];\n    Queue<Integer> q=new LinkedList<>(); q.add(start); seen[start]=true;\n    while(!q.isEmpty()){ int u=q.poll(); order.add(u);\n        for(int v:adj.get(u)) if(!seen[v]){ seen[v]=true; q.add(v); } }\n    return order;\n}"],
    'cpp' => ['code' => "vector<int> bfs(vector<vector<int>>& adj,int start){\n    vector<int> order; vector<bool> seen(adj.size(),false);\n    queue<int> q; q.push(start); seen[start]=true;\n    while(!q.empty()){ int u=q.front(); q.pop(); order.push_back(u);\n        for(int v:adj[u]) if(!seen[v]){ seen[v]=true; q.push(v); } }\n    return order;\n}"],
],
'Graphs::DFS of Graph' => [
    'python' => ['code' => "def dfs(adj,start):\n    seen=set(); order=[]\n    def go(u):\n        seen.add(u); order.append(u)\n        for v in adj[u]:\n            if v not in seen: go(v)\n    go(start)\n    return order", 'explanation_md' => 'Recursion/stack. O(V+E).'],
    'php' => ['code' => "<?php\nfunction dfs(array \$adj,int \$start): array {\n    \$seen=[]; \$order=[];\n    \$go=function(\$u) use (&\$go,&\$seen,&\$order,\$adj){\n        \$seen[\$u]=true; \$order[]=\$u;\n        foreach(\$adj[\$u]??[] as \$v) if(!isset(\$seen[\$v])) \$go(\$v);\n    };\n    \$go(\$start);\n    return \$order;\n}"],
    'java' => ['code' => "void dfs(List<List<Integer>> adj,int u,boolean[] seen,List<Integer> order){\n    seen[u]=true; order.add(u);\n    for(int v:adj.get(u)) if(!seen[v]) dfs(adj,v,seen,order);\n}"],
    'cpp' => ['code' => "void dfs(vector<vector<int>>& adj,int u,vector<bool>& seen,vector<int>& order){\n    seen[u]=true; order.push_back(u);\n    for(int v:adj[u]) if(!seen[v]) dfs(adj,v,seen,order);\n}"],
],
'Graphs::Find the number of islands' => [
    'python' => ['code' => "def num_islands(grid):\n    if not grid: return 0\n    R,C=len(grid),len(grid[0]); count=0\n    def sink(r,c):\n        if r<0 or c<0 or r>=R or c>=C or grid[r][c]!='1': return\n        grid[r][c]='0'\n        sink(r+1,c); sink(r-1,c); sink(r,c+1); sink(r,c-1)\n    for r in range(R):\n        for c in range(C):\n            if grid[r][c]=='1': count+=1; sink(r,c)\n    return count", 'explanation_md' => 'DFS flood fill. O(R·C).'],
    'php' => ['code' => "<?php\nfunction numIslands(array \$grid): int {\n    \$R=count(\$grid); if(!\$R) return 0; \$C=count(\$grid[0]); \$count=0;\n    \$sink=function(\$r,\$c) use (&\$sink,&\$grid,\$R,\$C){\n        if(\$r<0||\$c<0||\$r>=\$R||\$c>=\$C||\$grid[\$r][\$c]!=='1') return;\n        \$grid[\$r][\$c]='0';\n        \$sink(\$r+1,\$c); \$sink(\$r-1,\$c); \$sink(\$r,\$c+1); \$sink(\$r,\$c-1);\n    };\n    for(\$r=0;\$r<\$R;\$r++) for(\$c=0;\$c<\$C;\$c++)\n        if(\$grid[\$r][\$c]==='1'){ \$count++; \$sink(\$r,\$c); }\n    return \$count;\n}"],
    'java' => ['code' => "int numIslands(char[][] g){\n    int R=g.length,C=g[0].length,count=0;\n    for(int r=0;r<R;r++) for(int c=0;c<C;c++)\n        if(g[r][c]=='1'){ count++; sink(g,r,c); }\n    return count;\n}\nvoid sink(char[][] g,int r,int c){\n    if(r<0||c<0||r>=g.length||c>=g[0].length||g[r][c]!='1') return;\n    g[r][c]='0'; sink(g,r+1,c); sink(g,r-1,c); sink(g,r,c+1); sink(g,r,c-1);\n}"],
    'cpp' => ['code' => "void sink(vector<vector<char>>& g,int r,int c){\n    if(r<0||c<0||r>=(int)g.size()||c>=(int)g[0].size()||g[r][c]!='1') return;\n    g[r][c]='0'; sink(g,r+1,c); sink(g,r-1,c); sink(g,r,c+1); sink(g,r,c-1);\n}\nint numIslands(vector<vector<char>>& g){\n    int count=0;\n    for(int r=0;r<(int)g.size();r++) for(int c=0;c<(int)g[0].size();c++)\n        if(g[r][c]=='1'){ count++; sink(g,r,c); }\n    return count;\n}"],
],
'Graphs::Topological sort' => [
    'python' => ['code' => "from collections import deque\ndef topo(n,edges):\n    adj=[[] for _ in range(n)]; indeg=[0]*n\n    for u,v in edges: adj[u].append(v); indeg[v]+=1\n    q=deque(i for i in range(n) if indeg[i]==0); order=[]\n    while q:\n        u=q.popleft(); order.append(u)\n        for v in adj[u]:\n            indeg[v]-=1\n            if indeg[v]==0: q.append(v)\n    return order if len(order)==n else []", 'explanation_md' => "Kahn's algorithm. O(V+E)."],
    'php' => ['code' => "<?php\nfunction topo(int \$n,array \$edges): array {\n    \$adj=array_fill(0,\$n,[]); \$indeg=array_fill(0,\$n,0);\n    foreach(\$edges as [\$u,\$v]){ \$adj[\$u][]=\$v; \$indeg[\$v]++; }\n    \$q=[]; for(\$i=0;\$i<\$n;\$i++) if(\$indeg[\$i]===0) \$q[]=\$i;\n    \$order=[];\n    while(\$q){ \$u=array_shift(\$q); \$order[]=\$u;\n        foreach(\$adj[\$u] as \$v) if(--\$indeg[\$v]===0) \$q[]=\$v; }\n    return count(\$order)===\$n ? \$order : [];\n}"],
    'java' => ['code' => "int[] topo(int n,int[][] edges){\n    List<List<Integer>> adj=new ArrayList<>();\n    for(int i=0;i<n;i++) adj.add(new ArrayList<>());\n    int[] indeg=new int[n];\n    for(int[] e:edges){ adj.get(e[0]).add(e[1]); indeg[e[1]]++; }\n    Queue<Integer> q=new LinkedList<>();\n    for(int i=0;i<n;i++) if(indeg[i]==0) q.add(i);\n    int[] order=new int[n]; int k=0;\n    while(!q.isEmpty()){ int u=q.poll(); order[k++]=u;\n        for(int v:adj.get(u)) if(--indeg[v]==0) q.add(v); }\n    return k==n?order:new int[0];\n}"],
    'cpp' => ['code' => "vector<int> topo(int n,vector<vector<int>>& edges){\n    vector<vector<int>> adj(n); vector<int> indeg(n,0);\n    for(auto&e:edges){ adj[e[0]].push_back(e[1]); indeg[e[1]]++; }\n    queue<int> q; for(int i=0;i<n;i++) if(!indeg[i]) q.push(i);\n    vector<int> order;\n    while(!q.empty()){ int u=q.front(); q.pop(); order.push_back(u);\n        for(int v:adj[u]) if(--indeg[v]==0) q.push(v); }\n    return (int)order.size()==n?order:vector<int>{};\n}"],
],

// ===================== DYNAMIC PROGRAMMING =====================
'Dynamic Programming::Nth Fibonacci Number' => [
    'python' => ['code' => "def fib(n):\n    if n<2: return n\n    a,b=0,1\n    for _ in range(2,n+1): a,b=b,a+b\n    return b", 'explanation_md' => 'Bottom-up, O(n) time, O(1) space.'],
    'php' => ['code' => "<?php\nfunction fib(int \$n): int {\n    if(\$n<2) return \$n;\n    \$a=0; \$b=1;\n    for(\$i=2;\$i<=\$n;\$i++){ [\$a,\$b]=[\$b,\$a+\$b]; }\n    return \$b;\n}"],
    'java' => ['code' => "long fib(int n){\n    if(n<2) return n;\n    long a=0,b=1;\n    for(int i=2;i<=n;i++){ long c=a+b; a=b; b=c; }\n    return b;\n}"],
    'cpp' => ['code' => "long long fib(int n){\n    if(n<2) return n;\n    long long a=0,b=1;\n    for(int i=2;i<=n;i++){ long long c=a+b; a=b; b=c; }\n    return b;\n}"],
],
'Dynamic Programming::0-1 Knapsack' => [
    'python' => ['code' => "def knapsack(wt,val,W):\n    dp=[0]*(W+1)\n    for i in range(len(wt)):\n        for w in range(W,wt[i]-1,-1):\n            dp[w]=max(dp[w],dp[w-wt[i]]+val[i])\n    return dp[W]", 'explanation_md' => '1-D DP, iterate weight downward. O(n·W).'],
    'php' => ['code' => "<?php\nfunction knapsack(array \$wt,array \$val,int \$W): int {\n    \$dp=array_fill(0,\$W+1,0);\n    for(\$i=0;\$i<count(\$wt);\$i++)\n        for(\$w=\$W;\$w>=\$wt[\$i];\$w--)\n            \$dp[\$w]=max(\$dp[\$w],\$dp[\$w-\$wt[\$i]]+\$val[\$i]);\n    return \$dp[\$W];\n}"],
    'java' => ['code' => "int knapsack(int[] wt,int[] val,int W){\n    int[] dp=new int[W+1];\n    for(int i=0;i<wt.length;i++)\n        for(int w=W;w>=wt[i];w--)\n            dp[w]=Math.max(dp[w],dp[w-wt[i]]+val[i]);\n    return dp[W];\n}"],
    'cpp' => ['code' => "int knapsack(vector<int>& wt,vector<int>& val,int W){\n    vector<int> dp(W+1,0);\n    for(int i=0;i<(int)wt.size();i++)\n        for(int w=W;w>=wt[i];w--)\n            dp[w]=max(dp[w],dp[w-wt[i]]+val[i]);\n    return dp[W];\n}"],
],
'Dynamic Programming::Longest Common Subsequence' => [
    'python' => ['code' => "def lcs(a,b):\n    m,n=len(a),len(b)\n    dp=[[0]*(n+1) for _ in range(m+1)]\n    for i in range(1,m+1):\n        for j in range(1,n+1):\n            dp[i][j]=dp[i-1][j-1]+1 if a[i-1]==b[j-1] else max(dp[i-1][j],dp[i][j-1])\n    return dp[m][n]", 'explanation_md' => 'Classic 2-D DP. O(m·n).'],
    'php' => ['code' => "<?php\nfunction lcs(string \$a,string \$b): int {\n    \$m=strlen(\$a); \$n=strlen(\$b);\n    \$dp=array_fill(0,\$m+1,array_fill(0,\$n+1,0));\n    for(\$i=1;\$i<=\$m;\$i++) for(\$j=1;\$j<=\$n;\$j++)\n        \$dp[\$i][\$j]= \$a[\$i-1]===\$b[\$j-1] ? \$dp[\$i-1][\$j-1]+1 : max(\$dp[\$i-1][\$j],\$dp[\$i][\$j-1]);\n    return \$dp[\$m][\$n];\n}"],
    'java' => ['code' => "int lcs(String a,String b){\n    int m=a.length(),n=b.length();\n    int[][] dp=new int[m+1][n+1];\n    for(int i=1;i<=m;i++) for(int j=1;j<=n;j++)\n        dp[i][j]= a.charAt(i-1)==b.charAt(j-1) ? dp[i-1][j-1]+1 : Math.max(dp[i-1][j],dp[i][j-1]);\n    return dp[m][n];\n}"],
    'cpp' => ['code' => "int lcs(string a,string b){\n    int m=a.size(),n=b.size();\n    vector<vector<int>> dp(m+1,vector<int>(n+1,0));\n    for(int i=1;i<=m;i++) for(int j=1;j<=n;j++)\n        dp[i][j]= a[i-1]==b[j-1] ? dp[i-1][j-1]+1 : max(dp[i-1][j],dp[i][j-1]);\n    return dp[m][n];\n}"],
],
"Dynamic Programming::Kadane's Algorithm" => [
    'python' => ['code' => "def kadane(a):\n    best=cur=a[0]\n    for x in a[1:]:\n        cur=max(x,cur+x)\n        best=max(best,cur)\n    return best", 'explanation_md' => 'Max subarray sum. O(n).'],
    'php' => ['code' => "<?php\nfunction kadane(array \$a): int {\n    \$best=\$cur=\$a[0];\n    for(\$i=1;\$i<count(\$a);\$i++){ \$cur=max(\$a[\$i],\$cur+\$a[\$i]); \$best=max(\$best,\$cur); }\n    return \$best;\n}"],
    'java' => ['code' => "int kadane(int[] a){\n    int best=a[0],cur=a[0];\n    for(int i=1;i<a.length;i++){ cur=Math.max(a[i],cur+a[i]); best=Math.max(best,cur); }\n    return best;\n}"],
    'cpp' => ['code' => "int kadane(vector<int>& a){\n    int best=a[0],cur=a[0];\n    for(int i=1;i<(int)a.size();i++){ cur=max(a[i],cur+a[i]); best=max(best,cur); }\n    return best;\n}"],
],
'Dynamic Programming::Coin ChangeProblem' => [
    'python' => ['code' => "def coin_change(coins,amount):\n    INF=amount+1; dp=[0]+[INF]*amount\n    for a in range(1,amount+1):\n        for c in coins:\n            if c<=a: dp[a]=min(dp[a],dp[a-c]+1)\n    return dp[amount] if dp[amount]!=INF else -1", 'explanation_md' => 'Min coins DP. O(amount·coins).'],
    'php' => ['code' => "<?php\nfunction coinChange(array \$coins,int \$amount): int {\n    \$INF=\$amount+1; \$dp=array_fill(0,\$amount+1,\$INF); \$dp[0]=0;\n    for(\$a=1;\$a<=\$amount;\$a++) foreach(\$coins as \$c)\n        if(\$c<=\$a) \$dp[\$a]=min(\$dp[\$a],\$dp[\$a-\$c]+1);\n    return \$dp[\$amount]===\$INF ? -1 : \$dp[\$amount];\n}"],
    'java' => ['code' => "int coinChange(int[] coins,int amount){\n    int INF=amount+1; int[] dp=new int[amount+1];\n    Arrays.fill(dp,INF); dp[0]=0;\n    for(int a=1;a<=amount;a++) for(int c:coins)\n        if(c<=a) dp[a]=Math.min(dp[a],dp[a-c]+1);\n    return dp[amount]==INF?-1:dp[amount];\n}"],
    'cpp' => ['code' => "int coinChange(vector<int>& coins,int amount){\n    int INF=amount+1; vector<int> dp(amount+1,INF); dp[0]=0;\n    for(int a=1;a<=amount;a++) for(int c:coins)\n        if(c<=a) dp[a]=min(dp[a],dp[a-c]+1);\n    return dp[amount]==INF?-1:dp[amount];\n}"],
],

// ===================== SEGMENT TREE =====================
'Segment Tree::Range Minimum Query' => [
    'python' => ['code' => "import math\ndef build(a):\n    n=len(a); t=[0]*(2*n)\n    for i in range(n): t[n+i]=a[i]\n    for i in range(n-1,0,-1): t[i]=min(t[2*i],t[2*i+1])\n    return t,n\ndef query(t,n,l,r):  # [l,r)\n    res=math.inf; l+=n; r+=n\n    while l<r:\n        if l&1: res=min(res,t[l]); l+=1\n        if r&1: r-=1; res=min(res,t[r])\n        l//=2; r//=2\n    return res", 'explanation_md' => 'Iterative segment tree for min. Query O(log n).'],
    'php' => ['code' => "<?php\nfunction build(array \$a): array {\n    \$n=count(\$a); \$t=array_fill(0,2*\$n,PHP_INT_MAX);\n    for(\$i=0;\$i<\$n;\$i++) \$t[\$n+\$i]=\$a[\$i];\n    for(\$i=\$n-1;\$i>0;\$i--) \$t[\$i]=min(\$t[2*\$i],\$t[2*\$i+1]);\n    return [\$t,\$n];\n}\nfunction rmq(array \$t,int \$n,int \$l,int \$r): int {\n    \$res=PHP_INT_MAX; \$l+=\$n; \$r+=\$n;\n    while(\$l<\$r){ if(\$l&1){ \$res=min(\$res,\$t[\$l]); \$l++; } if(\$r&1){ \$r--; \$res=min(\$res,\$t[\$r]); } \$l=intdiv(\$l,2); \$r=intdiv(\$r,2); }\n    return \$res;\n}"],
    'java' => ['code' => "int[] t; int N;\nvoid build(int[] a){\n    N=a.length; t=new int[2*N];\n    for(int i=0;i<N;i++) t[N+i]=a[i];\n    for(int i=N-1;i>0;i--) t[i]=Math.min(t[2*i],t[2*i+1]);\n}\nint rmq(int l,int r){ // [l,r)\n    int res=Integer.MAX_VALUE; l+=N; r+=N;\n    while(l<r){ if((l&1)==1) res=Math.min(res,t[l++]); if((r&1)==1) res=Math.min(res,t[--r]); l/=2; r/=2; }\n    return res;\n}"],
    'cpp' => ['code' => "vector<int> t; int N;\nvoid build(vector<int>& a){\n    N=a.size(); t.assign(2*N,INT_MAX);\n    for(int i=0;i<N;i++) t[N+i]=a[i];\n    for(int i=N-1;i>0;i--) t[i]=min(t[2*i],t[2*i+1]);\n}\nint rmq(int l,int r){ // [l,r)\n    int res=INT_MAX; l+=N; r+=N;\n    while(l<r){ if(l&1) res=min(res,t[l++]); if(r&1) res=min(res,t[--r]); l/=2; r/=2; }\n    return res;\n}"],
],

// ===================== TRIE =====================
'Trie::Trie | (Insert and Search)' => [
    'python' => ['code' => "class Trie:\n    def __init__(self): self.root={}\n    def insert(self,w):\n        n=self.root\n        for c in w: n=n.setdefault(c,{})\n        n['$']=True\n    def search(self,w):\n        n=self.root\n        for c in w:\n            if c not in n: return False\n            n=n[c]\n        return '$' in n", 'explanation_md' => 'Each op O(L).'],
    'php' => ['code' => "<?php\nclass TrieNode { public array \$ch=[]; public bool \$end=false; }\nclass Trie {\n    private TrieNode \$root;\n    function __construct(){ \$this->root=new TrieNode(); }\n    function insert(string \$w): void {\n        \$n=\$this->root;\n        for(\$i=0;\$i<strlen(\$w);\$i++){ \$n->ch[\$w[\$i]] ??= new TrieNode(); \$n=\$n->ch[\$w[\$i]]; }\n        \$n->end=true;\n    }\n    function search(string \$w): bool {\n        \$n=\$this->root;\n        for(\$i=0;\$i<strlen(\$w);\$i++){ if(!isset(\$n->ch[\$w[\$i]])) return false; \$n=\$n->ch[\$w[\$i]]; }\n        return \$n->end;\n    }\n}"],
    'java' => ['code' => "class Trie {\n    static class Node { Node[] ch=new Node[26]; boolean end; }\n    Node root=new Node();\n    void insert(String w){ Node n=root; for(char c:w.toCharArray()){ int i=c-'a'; if(n.ch[i]==null) n.ch[i]=new Node(); n=n.ch[i]; } n.end=true; }\n    boolean search(String w){ Node n=root; for(char c:w.toCharArray()){ int i=c-'a'; if(n.ch[i]==null) return false; n=n.ch[i]; } return n.end; }\n}"],
    'cpp' => ['code' => "struct Node { Node* ch[26]={}; bool end=false; };\nstruct Trie {\n    Node* root=new Node();\n    void insert(string w){ Node* n=root; for(char c:w){ int i=c-'a'; if(!n->ch[i]) n->ch[i]=new Node(); n=n->ch[i]; } n->end=true; }\n    bool search(string w){ Node* n=root; for(char c:w){ int i=c-'a'; if(!n->ch[i]) return false; n=n->ch[i]; } return n->end; }\n};"],
],

// ===================== FENWICK TREE =====================
'Fenwick Tree::Range Sum Query - Mutable' => [
    'python' => ['code' => "class NumArray:\n    def __init__(self,nums):\n        self.n=len(nums); self.tree=[0]*(self.n+1); self.a=[0]*self.n\n        for i,v in enumerate(nums): self.update(i,v)\n    def update(self,i,val):\n        delta=val-self.a[i]; self.a[i]=val; i+=1\n        while i<=self.n: self.tree[i]+=delta; i+=i&(-i)\n    def _q(self,i):\n        s=0\n        while i>0: s+=self.tree[i]; i-=i&(-i)\n        return s\n    def sumRange(self,l,r):\n        return self._q(r+1)-self._q(l)", 'explanation_md' => 'Fenwick tree (BIT); update & query O(log n).'],
    'php' => ['code' => "<?php\nclass NumArray {\n    private int \$n; private array \$tree, \$a;\n    function __construct(array \$nums){\n        \$this->n=count(\$nums); \$this->tree=array_fill(0,\$this->n+1,0); \$this->a=array_fill(0,\$this->n,0);\n        foreach(\$nums as \$i=>\$v) \$this->update(\$i,\$v);\n    }\n    function update(int \$i,int \$val): void {\n        \$delta=\$val-\$this->a[\$i]; \$this->a[\$i]=\$val; \$i++;\n        for(; \$i<=\$this->n; \$i+=\$i&(-\$i)) \$this->tree[\$i]+=\$delta;\n    }\n    private function q(int \$i): int { \$s=0; for(; \$i>0; \$i-=\$i&(-\$i)) \$s+=\$this->tree[\$i]; return \$s; }\n    function sumRange(int \$l,int \$r): int { return \$this->q(\$r+1)-\$this->q(\$l); }\n}"],
    'java' => ['code' => "class NumArray {\n    int n; long[] tree; int[] a;\n    NumArray(int[] nums){\n        n=nums.length; tree=new long[n+1]; a=new int[n];\n        for(int i=0;i<n;i++) update(i,nums[i]);\n    }\n    void update(int i,int val){\n        long delta=val-a[i]; a[i]=val; i++;\n        for(; i<=n; i+=i&(-i)) tree[i]+=delta;\n    }\n    long q(int i){ long s=0; for(; i>0; i-=i&(-i)) s+=tree[i]; return s; }\n    long sumRange(int l,int r){ return q(r+1)-q(l); }\n}"],
    'cpp' => ['code' => "class NumArray {\n    int n; vector<long long> tree; vector<int> a;\npublic:\n    NumArray(vector<int>& nums){\n        n=nums.size(); tree.assign(n+1,0); a.assign(n,0);\n        for(int i=0;i<n;i++) update(i,nums[i]);\n    }\n    void update(int i,int val){\n        long long delta=val-a[i]; a[i]=val; i++;\n        for(; i<=n; i+=i&(-i)) tree[i]+=delta;\n    }\n    long long q(int i){ long long s=0; for(; i>0; i-=i&(-i)) s+=tree[i]; return s; }\n    long long sumRange(int l,int r){ return q(r+1)-q(l); }\n};"],
],

];
