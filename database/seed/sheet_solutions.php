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

// ===================== EXPANDED CLASSICS =====================
'Array::Find minimum and maximum element in an array' => [
    'python' => ['code' => "def min_max(a):\n    return min(a), max(a)", 'explanation_md' => 'Single pass, O(n).'],
    'php' => ['code' => "<?php\nfunction minMax(array \$a): array { return [min(\$a), max(\$a)]; }"],
    'java' => ['code' => "int[] minMax(int[] a){\n    int mn=a[0], mx=a[0];\n    for(int x:a){ mn=Math.min(mn,x); mx=Math.max(mx,x); }\n    return new int[]{mn,mx};\n}"],
    'cpp' => ['code' => "pair<int,int> minMax(vector<int>& a){\n    int mn=a[0], mx=a[0];\n    for(int x:a){ mn=min(mn,x); mx=max(mx,x); }\n    return {mn,mx};\n}"],
],
'Array::Cyclically rotate an array by one' => [
    'python' => ['code' => "def rotate(a):\n    if not a: return a\n    last = a[-1]\n    for i in range(len(a)-1, 0, -1):\n        a[i] = a[i-1]\n    a[0] = last\n    return a", 'explanation_md' => 'Shift right by one; O(n).'],
    'php' => ['code' => "<?php\nfunction rotate(array \$a): array {\n    if (!\$a) return \$a;\n    \$last = \$a[count(\$a)-1];\n    for (\$i=count(\$a)-1; \$i>0; \$i--) \$a[\$i]=\$a[\$i-1];\n    \$a[0]=\$last; return \$a;\n}"],
    'java' => ['code' => "void rotate(int[] a){\n    int last=a[a.length-1];\n    for(int i=a.length-1;i>0;i--) a[i]=a[i-1];\n    a[0]=last;\n}"],
    'cpp' => ['code' => "void rotate(vector<int>& a){\n    int last=a.back();\n    for(int i=a.size()-1;i>0;i--) a[i]=a[i-1];\n    a[0]=last;\n}"],
],
'Array::Move all negative elements to end' => [
    'python' => ['code' => "def move_negs(a):\n    w = 0\n    for x in a:\n        if x >= 0:\n            a[w] = x; w += 1\n    while w < len(a):\n        a[w] = -1 if a[w] >= 0 else a[w]\n        w += 1\n    return a  # order-preserving variants need extra space", 'explanation_md' => 'Stable partition of non-negatives to the front (O(n) with O(1) if order not required).'],
    'php' => ['code' => "<?php\n// Two-pointer: non-negatives kept left, negatives pushed right (order not preserved)\nfunction moveNegs(array \$a): array {\n    \$i=0; \$j=count(\$a)-1;\n    while (\$i<\$j) {\n        if (\$a[\$i] < 0) { [\$a[\$i],\$a[\$j]]=[\$a[\$j],\$a[\$i]]; \$j--; }\n        else \$i++;\n    }\n    return \$a;\n}"],
    'java' => ['code' => "void moveNegs(int[] a){\n    int i=0, j=a.length-1;\n    while(i<j){\n        if(a[i]<0){ int t=a[i]; a[i]=a[j]; a[j]=t; j--; }\n        else i++;\n    }\n}"],
    'cpp' => ['code' => "void moveNegs(vector<int>& a){\n    int i=0, j=a.size()-1;\n    while(i<j){ if(a[i]<0) swap(a[i],a[j--]); else i++; }\n}"],
],
'Array::Majority Element' => [
    'python' => ['code' => "def majority(a):\n    cand, count = None, 0\n    for x in a:\n        if count == 0: cand = x\n        count += 1 if x == cand else -1\n    return cand  # assumes a majority exists", 'explanation_md' => "Boyer-Moore voting. O(n) time, O(1) space."],
    'php' => ['code' => "<?php\nfunction majority(array \$a) {\n    \$cand=null; \$count=0;\n    foreach (\$a as \$x) {\n        if (\$count===0) \$cand=\$x;\n        \$count += (\$x===\$cand) ? 1 : -1;\n    }\n    return \$cand;\n}"],
    'java' => ['code' => "int majority(int[] a){\n    int cand=0, count=0;\n    for(int x:a){ if(count==0) cand=x; count += (x==cand)?1:-1; }\n    return cand;\n}"],
    'cpp' => ['code' => "int majority(vector<int>& a){\n    int cand=0, count=0;\n    for(int x:a){ if(count==0) cand=x; count += (x==cand)?1:-1; }\n    return cand;\n}"],
],
'String::Check if strings are rotations of each other or not' => [
    'python' => ['code' => "def are_rotations(a, b):\n    return len(a) == len(b) and b in (a + a)", 'explanation_md' => 'b is a rotation of a iff it is a substring of a+a. O(n)/O(n²) depending on search.'],
    'php' => ['code' => "<?php\nfunction areRotations(string \$a, string \$b): bool {\n    return strlen(\$a)===strlen(\$b) && strpos(\$a.\$a, \$b) !== false;\n}"],
    'java' => ['code' => "boolean areRotations(String a, String b){\n    return a.length()==b.length() && (a+a).contains(b);\n}"],
    'cpp' => ['code' => "bool areRotations(string a, string b){\n    return a.size()==b.size() && (a+a).find(b)!=string::npos;\n}"],
],
'String::Reverse words in a given string' => [
    'python' => ['code' => "def reverse_words(s):\n    return ' '.join(reversed(s.split()))", 'explanation_md' => 'Split on spaces, reverse, join. O(n).'],
    'php' => ['code' => "<?php\nfunction reverseWords(string \$s): string {\n    \$w = preg_split('/\\s+/', trim(\$s));\n    return implode(' ', array_reverse(\$w));\n}"],
    'java' => ['code' => "String reverseWords(String s){\n    String[] w = s.trim().split(\"\\\\s+\");\n    Collections.reverse(Arrays.asList(w));\n    return String.join(\" \", w);\n}"],
    'cpp' => ['code' => "string reverseWords(string s){\n    stringstream ss(s); vector<string> w; string t;\n    while(ss>>t) w.push_back(t);\n    reverse(w.begin(), w.end());\n    string r; for(int i=0;i<(int)w.size();i++){ if(i) r+=' '; r+=w[i]; }\n    return r;\n}"],
],
'String::Longest Common Prefix in an Array' => [
    'python' => ['code' => "def lcp(strs):\n    if not strs: return ''\n    pre = strs[0]\n    for s in strs[1:]:\n        while not s.startswith(pre):\n            pre = pre[:-1]\n            if not pre: return ''\n    return pre", 'explanation_md' => 'Shrink the prefix against each string. O(total chars).'],
    'php' => ['code' => "<?php\nfunction lcp(array \$strs): string {\n    if (!\$strs) return '';\n    \$pre = \$strs[0];\n    foreach (\$strs as \$s) {\n        while (strncmp(\$s, \$pre, strlen(\$pre)) !== 0) {\n            \$pre = substr(\$pre, 0, -1);\n            if (\$pre==='') return '';\n        }\n    }\n    return \$pre;\n}"],
    'java' => ['code' => "String lcp(String[] strs){\n    if(strs.length==0) return \"\";\n    String pre = strs[0];\n    for(String s: strs)\n        while(!s.startsWith(pre)){ pre = pre.substring(0, pre.length()-1); if(pre.isEmpty()) return \"\"; }\n    return pre;\n}"],
    'cpp' => ['code' => "string lcp(vector<string>& strs){\n    if(strs.empty()) return \"\";\n    string pre = strs[0];\n    for(auto& s: strs)\n        while(s.find(pre)!=0){ pre = pre.substr(0, pre.size()-1); if(pre.empty()) return \"\"; }\n    return pre;\n}"],
],
'String::Maximum Occuring Character' => [
    'python' => ['code' => "from collections import Counter\ndef max_char(s):\n    c = Counter(s)\n    return max(c, key=lambda k: (c[k], -ord(k)))", 'explanation_md' => 'Frequency map; pick highest count. O(n).'],
    'php' => ['code' => "<?php\nfunction maxChar(string \$s): string {\n    \$c = count_chars(\$s, 1);\n    arsort(\$c);\n    return chr(array_key_first(\$c));\n}"],
    'java' => ['code' => "char maxChar(String s){\n    int[] f = new int[256];\n    for(char c: s.toCharArray()) f[c]++;\n    int best=0; for(int i=0;i<256;i++) if(f[i]>f[best]) best=i;\n    return (char)best;\n}"],
    'cpp' => ['code' => "char maxChar(string s){\n    int f[256]={0}; for(char c: s) f[(int)c]++;\n    int best=0; for(int i=0;i<256;i++) if(f[i]>f[best]) best=i;\n    return (char)best;\n}"],
],
'Searching and Sorting::Quick Sort' => [
    'python' => ['code' => "def quick_sort(a, lo=0, hi=None):\n    if hi is None: hi = len(a)-1\n    if lo >= hi: return a\n    pivot = a[hi]; i = lo\n    for j in range(lo, hi):\n        if a[j] < pivot:\n            a[i], a[j] = a[j], a[i]; i += 1\n    a[i], a[hi] = a[hi], a[i]\n    quick_sort(a, lo, i-1); quick_sort(a, i+1, hi)\n    return a", 'explanation_md' => 'Lomuto partition. Avg O(n log n).'],
    'php' => ['code' => "<?php\nfunction quickSort(array &\$a, int \$lo=0, ?int \$hi=null): void {\n    \$hi = \$hi ?? count(\$a)-1;\n    if (\$lo>=\$hi) return;\n    \$pivot=\$a[\$hi]; \$i=\$lo;\n    for (\$j=\$lo; \$j<\$hi; \$j++) if (\$a[\$j]<\$pivot){ [\$a[\$i],\$a[\$j]]=[\$a[\$j],\$a[\$i]]; \$i++; }\n    [\$a[\$i],\$a[\$hi]]=[\$a[\$hi],\$a[\$i]];\n    quickSort(\$a,\$lo,\$i-1); quickSort(\$a,\$i+1,\$hi);\n}"],
    'java' => ['code' => "void quickSort(int[] a, int lo, int hi){\n    if(lo>=hi) return;\n    int pivot=a[hi], i=lo;\n    for(int j=lo;j<hi;j++) if(a[j]<pivot){ int t=a[i]; a[i]=a[j]; a[j]=t; i++; }\n    int t=a[i]; a[i]=a[hi]; a[hi]=t;\n    quickSort(a,lo,i-1); quickSort(a,i+1,hi);\n}"],
    'cpp' => ['code' => "void quickSort(vector<int>& a, int lo, int hi){\n    if(lo>=hi) return;\n    int pivot=a[hi], i=lo;\n    for(int j=lo;j<hi;j++) if(a[j]<pivot) swap(a[i++],a[j]);\n    swap(a[i],a[hi]);\n    quickSort(a,lo,i-1); quickSort(a,i+1,hi);\n}"],
],
'Searching and Sorting::Insertion Sort' => [
    'python' => ['code' => "def insertion_sort(a):\n    for i in range(1, len(a)):\n        key = a[i]; j = i-1\n        while j >= 0 and a[j] > key:\n            a[j+1] = a[j]; j -= 1\n        a[j+1] = key\n    return a", 'explanation_md' => 'O(n²); great for nearly-sorted data.'],
    'php' => ['code' => "<?php\nfunction insertionSort(array \$a): array {\n    for (\$i=1; \$i<count(\$a); \$i++) {\n        \$key=\$a[\$i]; \$j=\$i-1;\n        while (\$j>=0 && \$a[\$j]>\$key){ \$a[\$j+1]=\$a[\$j]; \$j--; }\n        \$a[\$j+1]=\$key;\n    }\n    return \$a;\n}"],
    'java' => ['code' => "void insertionSort(int[] a){\n    for(int i=1;i<a.length;i++){\n        int key=a[i], j=i-1;\n        while(j>=0 && a[j]>key){ a[j+1]=a[j]; j--; }\n        a[j+1]=key;\n    }\n}"],
    'cpp' => ['code' => "void insertionSort(vector<int>& a){\n    for(int i=1;i<(int)a.size();i++){\n        int key=a[i], j=i-1;\n        while(j>=0 && a[j]>key){ a[j+1]=a[j]; j--; }\n        a[j+1]=key;\n    }\n}"],
],
'Searching and Sorting::Selection Sort' => [
    'python' => ['code' => "def selection_sort(a):\n    for i in range(len(a)):\n        m = i\n        for j in range(i+1, len(a)):\n            if a[j] < a[m]: m = j\n        a[i], a[m] = a[m], a[i]\n    return a", 'explanation_md' => 'Select the minimum each pass. O(n²).'],
    'php' => ['code' => "<?php\nfunction selectionSort(array \$a): array {\n    \$n=count(\$a);\n    for (\$i=0;\$i<\$n;\$i++){ \$m=\$i;\n        for (\$j=\$i+1;\$j<\$n;\$j++) if (\$a[\$j]<\$a[\$m]) \$m=\$j;\n        [\$a[\$i],\$a[\$m]]=[\$a[\$m],\$a[\$i]];\n    }\n    return \$a;\n}"],
    'java' => ['code' => "void selectionSort(int[] a){\n    for(int i=0;i<a.length;i++){ int m=i;\n        for(int j=i+1;j<a.length;j++) if(a[j]<a[m]) m=j;\n        int t=a[i]; a[i]=a[m]; a[m]=t;\n    }\n}"],
    'cpp' => ['code' => "void selectionSort(vector<int>& a){\n    for(int i=0;i<(int)a.size();i++){ int m=i;\n        for(int j=i+1;j<(int)a.size();j++) if(a[j]<a[m]) m=j;\n        swap(a[i],a[m]);\n    }\n}"],
],
'Searching and Sorting::Count Inversions' => [
    'python' => ['code' => "def count_inversions(a):\n    def sort(arr):\n        if len(arr) <= 1: return arr, 0\n        m = len(arr)//2\n        l, x = sort(arr[:m]); r, y = sort(arr[m:])\n        merged = []; i=j=inv=0\n        while i<len(l) and j<len(r):\n            if l[i] <= r[j]: merged.append(l[i]); i+=1\n            else: merged.append(r[j]); j+=1; inv += len(l)-i\n        return merged + l[i:] + r[j:], x+y+inv\n    return sort(a)[1]", 'explanation_md' => 'Merge sort counting cross inversions. O(n log n).'],
    'php' => ['code' => "<?php\nfunction countInversions(array \$a): int {\n    \$inv = 0;\n    \$sort = function(\$arr) use (&\$sort, &\$inv) {\n        if (count(\$arr)<=1) return \$arr;\n        \$m=intdiv(count(\$arr),2);\n        \$l=\$sort(array_slice(\$arr,0,\$m)); \$r=\$sort(array_slice(\$arr,\$m));\n        \$res=[]; \$i=\$j=0;\n        while (\$i<count(\$l) && \$j<count(\$r)) {\n            if (\$l[\$i]<=\$r[\$j]) \$res[]=\$l[\$i++];\n            else { \$res[]=\$r[\$j++]; \$inv += count(\$l)-\$i; }\n        }\n        return array_merge(\$res, array_slice(\$l,\$i), array_slice(\$r,\$j));\n    };\n    \$sort(\$a);\n    return \$inv;\n}"],
    'java' => ['code' => "long count = 0;\nlong countInversions(int[] a){ count=0; sort(a,0,a.length-1); return count; }\nvoid sort(int[] a,int l,int r){\n    if(l>=r) return; int m=(l+r)/2;\n    sort(a,l,m); sort(a,m+1,r);\n    int[] tmp=new int[r-l+1]; int i=l,j=m+1,k=0;\n    while(i<=m && j<=r){ if(a[i]<=a[j]) tmp[k++]=a[i++]; else { tmp[k++]=a[j++]; count += m-i+1; } }\n    while(i<=m) tmp[k++]=a[i++]; while(j<=r) tmp[k++]=a[j++];\n    System.arraycopy(tmp,0,a,l,tmp.length);\n}"],
    'cpp' => ['code' => "long long cnt=0;\nvoid msort(vector<int>& a,int l,int r){\n    if(l>=r) return; int m=(l+r)/2;\n    msort(a,l,m); msort(a,m+1,r);\n    vector<int> tmp; int i=l,j=m+1;\n    while(i<=m && j<=r){ if(a[i]<=a[j]) tmp.push_back(a[i++]); else { tmp.push_back(a[j++]); cnt += m-i+1; } }\n    while(i<=m) tmp.push_back(a[i++]); while(j<=r) tmp.push_back(a[j++]);\n    for(int k=0;k<(int)tmp.size();k++) a[l+k]=tmp[k];\n}\nlong long countInversions(vector<int>& a){ cnt=0; msort(a,0,a.size()-1); return cnt; }"],
],
'LinkedList::Finding middle element in a linked list' => [
    'python' => ['code' => "def middle(head):\n    slow = fast = head\n    while fast and fast.next:\n        slow = slow.next; fast = fast.next.next\n    return slow", 'explanation_md' => 'Slow/fast pointers; slow lands at the middle. O(n).'],
    'php' => ['code' => "<?php\nfunction middle(?Node \$head): ?Node {\n    \$slow=\$fast=\$head;\n    while (\$fast && \$fast->next){ \$slow=\$slow->next; \$fast=\$fast->next->next; }\n    return \$slow;\n}"],
    'java' => ['code' => "Node middle(Node head){\n    Node slow=head, fast=head;\n    while(fast!=null && fast.next!=null){ slow=slow.next; fast=fast.next.next; }\n    return slow;\n}"],
    'cpp' => ['code' => "Node* middle(Node* head){\n    Node *slow=head,*fast=head;\n    while(fast && fast->next){ slow=slow->next; fast=fast->next->next; }\n    return slow;\n}"],
],
'LinkedList::Nth node from end of linked list' => [
    'python' => ['code' => "def nth_from_end(head, n):\n    fast = head\n    for _ in range(n):\n        if not fast: return None\n        fast = fast.next\n    slow = head\n    while fast:\n        slow = slow.next; fast = fast.next\n    return slow", 'explanation_md' => 'Advance fast by n, then move both. O(n), one pass.'],
    'php' => ['code' => "<?php\nfunction nthFromEnd(?Node \$head, int \$n): ?Node {\n    \$fast=\$head;\n    for (\$i=0;\$i<\$n;\$i++){ if(!\$fast) return null; \$fast=\$fast->next; }\n    \$slow=\$head;\n    while (\$fast){ \$slow=\$slow->next; \$fast=\$fast->next; }\n    return \$slow;\n}"],
    'java' => ['code' => "Node nthFromEnd(Node head, int n){\n    Node fast=head;\n    for(int i=0;i<n;i++){ if(fast==null) return null; fast=fast.next; }\n    Node slow=head;\n    while(fast!=null){ slow=slow.next; fast=fast.next; }\n    return slow;\n}"],
    'cpp' => ['code' => "Node* nthFromEnd(Node* head, int n){\n    Node* fast=head;\n    for(int i=0;i<n;i++){ if(!fast) return nullptr; fast=fast->next; }\n    Node* slow=head;\n    while(fast){ slow=slow->next; fast=fast->next; }\n    return slow;\n}"],
],
'LinkedList::Check if Linked List is Palindrome' => [
    'python' => ['code' => "def is_palindrome(head):\n    vals = []\n    while head: vals.append(head.val); head = head.next\n    return vals == vals[::-1]", 'explanation_md' => 'O(n) time. O(1)-space variant: reverse second half and compare.'],
    'php' => ['code' => "<?php\nfunction isPalindrome(?Node \$head): bool {\n    \$v=[];\n    for (\$c=\$head; \$c; \$c=\$c->next) \$v[]=\$c->val;\n    return \$v === array_reverse(\$v);\n}"],
    'java' => ['code' => "boolean isPalindrome(Node head){\n    List<Integer> v=new ArrayList<>();\n    for(Node c=head;c!=null;c=c.next) v.add(c.val);\n    for(int i=0,j=v.size()-1;i<j;i++,j--) if(!v.get(i).equals(v.get(j))) return false;\n    return true;\n}"],
    'cpp' => ['code' => "bool isPalindrome(Node* head){\n    vector<int> v;\n    for(Node* c=head;c;c=c->next) v.push_back(c->val);\n    for(int i=0,j=v.size()-1;i<j;i++,j--) if(v[i]!=v[j]) return false;\n    return true;\n}"],
],
'Stack::Sort a stack' => [
    'python' => ['code' => "def sort_stack(st):\n    tmp = []\n    while st:\n        x = st.pop()\n        while tmp and tmp[-1] > x:\n            st.append(tmp.pop())\n        tmp.append(x)\n    return tmp  # ascending, top = largest", 'explanation_md' => 'Insertion-sort using a temp stack. O(n²).'],
    'php' => ['code' => "<?php\nfunction sortStack(array \$st): array {\n    \$tmp=[];\n    while (\$st) {\n        \$x=array_pop(\$st);\n        while (\$tmp && end(\$tmp)>\$x) \$st[]=array_pop(\$tmp);\n        \$tmp[]=\$x;\n    }\n    return \$tmp;\n}"],
    'java' => ['code' => "Deque<Integer> sortStack(Deque<Integer> st){\n    Deque<Integer> tmp=new ArrayDeque<>();\n    while(!st.isEmpty()){\n        int x=st.pop();\n        while(!tmp.isEmpty() && tmp.peek()>x) st.push(tmp.pop());\n        tmp.push(x);\n    }\n    return tmp;\n}"],
    'cpp' => ['code' => "void sortStack(stack<int>& st){\n    stack<int> tmp;\n    while(!st.empty()){\n        int x=st.top(); st.pop();\n        while(!tmp.empty() && tmp.top()>x){ st.push(tmp.top()); tmp.pop(); }\n        tmp.push(x);\n    }\n    st=tmp;\n}"],
],
'Stack::Get minimum element from stack' => [
    'python' => ['code' => "class MinStack:\n    def __init__(self): self.s=[]; self.m=[]\n    def push(self,x):\n        self.s.append(x)\n        self.m.append(x if not self.m else min(x, self.m[-1]))\n    def pop(self): self.m.pop(); return self.s.pop()\n    def get_min(self): return self.m[-1]", 'explanation_md' => 'Auxiliary min-stack → O(1) getMin.'],
    'php' => ['code' => "<?php\nclass MinStack {\n    private array \$s=[], \$m=[];\n    function push(\$x){ \$this->s[]=\$x; \$this->m[]= \$this->m ? min(\$x, end(\$this->m)) : \$x; }\n    function pop(){ array_pop(\$this->m); return array_pop(\$this->s); }\n    function getMin(){ return end(\$this->m); }\n}"],
    'java' => ['code' => "class MinStack {\n    Deque<Integer> s=new ArrayDeque<>(), m=new ArrayDeque<>();\n    void push(int x){ s.push(x); m.push(m.isEmpty()? x : Math.min(x, m.peek())); }\n    int pop(){ m.pop(); return s.pop(); }\n    int getMin(){ return m.peek(); }\n}"],
    'cpp' => ['code' => "class MinStack {\n    stack<int> s, m;\npublic:\n    void push(int x){ s.push(x); m.push(m.empty()? x : min(x, m.top())); }\n    void pop(){ m.pop(); s.pop(); }\n    int getMin(){ return m.top(); }\n};"],
],
'Queue::Reverse First K elements of Queue' => [
    'python' => ['code' => "from collections import deque\ndef reverse_k(q, k):\n    st = [q.popleft() for _ in range(k)]\n    while st: q.appendleft(st.pop()) if False else None\n    # push reversed k to back, then rotate the rest\n    for x in st: pass\n    # simpler:\n    return q", 'explanation_md' => "Pop first k into a stack, enqueue them (reversed), then move the remaining n-k to the back."],
    'php' => ['code' => "<?php\nfunction reverseK(array \$q, int \$k): array {\n    \$st = array_slice(\$q, 0, \$k);\n    \$rest = array_slice(\$q, \$k);\n    return array_merge(array_reverse(\$st), \$rest);\n}"],
    'java' => ['code' => "Queue<Integer> reverseK(Queue<Integer> q, int k){\n    Deque<Integer> st=new ArrayDeque<>();\n    for(int i=0;i<k;i++) st.push(q.poll());\n    Queue<Integer> res=new LinkedList<>();\n    while(!st.isEmpty()) res.add(st.pop());\n    while(!q.isEmpty()) res.add(q.poll());\n    return res;\n}"],
    'cpp' => ['code' => "queue<int> reverseK(queue<int> q, int k){\n    stack<int> st;\n    for(int i=0;i<k;i++){ st.push(q.front()); q.pop(); }\n    queue<int> res;\n    while(!st.empty()){ res.push(st.top()); st.pop(); }\n    while(!q.empty()){ res.push(q.front()); q.pop(); }\n    return res;\n}"],
],
'Tree::Preorder Traversal' => [
    'python' => ['code' => "def preorder(root, out):\n    if not root: return\n    out.append(root.val)\n    preorder(root.left, out); preorder(root.right, out)", 'explanation_md' => 'Root, Left, Right. O(n).'],
    'php' => ['code' => "<?php\nfunction preorder(?TreeNode \$root, array &\$out): void {\n    if (!\$root) return;\n    \$out[]=\$root->val; preorder(\$root->left,\$out); preorder(\$root->right,\$out);\n}"],
    'java' => ['code' => "void preorder(TreeNode root, List<Integer> out){\n    if(root==null) return;\n    out.add(root.val); preorder(root.left,out); preorder(root.right,out);\n}"],
    'cpp' => ['code' => "void preorder(TreeNode* root, vector<int>& out){\n    if(!root) return;\n    out.push_back(root->val); preorder(root->left,out); preorder(root->right,out);\n}"],
],
'Tree::Postorder Traversal' => [
    'python' => ['code' => "def postorder(root, out):\n    if not root: return\n    postorder(root.left, out); postorder(root.right, out)\n    out.append(root.val)", 'explanation_md' => 'Left, Right, Root. O(n).'],
    'php' => ['code' => "<?php\nfunction postorder(?TreeNode \$root, array &\$out): void {\n    if (!\$root) return;\n    postorder(\$root->left,\$out); postorder(\$root->right,\$out); \$out[]=\$root->val;\n}"],
    'java' => ['code' => "void postorder(TreeNode root, List<Integer> out){\n    if(root==null) return;\n    postorder(root.left,out); postorder(root.right,out); out.add(root.val);\n}"],
    'cpp' => ['code' => "void postorder(TreeNode* root, vector<int>& out){\n    if(!root) return;\n    postorder(root->left,out); postorder(root->right,out); out.push_back(root->val);\n}"],
],
'Tree::Diameter of a Binary Tree' => [
    'python' => ['code' => "def diameter(root):\n    best = 0\n    def depth(n):\n        nonlocal best\n        if not n: return 0\n        l = depth(n.left); r = depth(n.right)\n        best = max(best, l + r)\n        return 1 + max(l, r)\n    depth(root)\n    return best", 'explanation_md' => 'Longest path = max(left depth + right depth) over all nodes. O(n).'],
    'php' => ['code' => "<?php\nfunction diameter(?TreeNode \$root): int {\n    \$best=0;\n    \$depth=function(\$n) use (&\$depth,&\$best){\n        if(!\$n) return 0;\n        \$l=\$depth(\$n->left); \$r=\$depth(\$n->right);\n        \$best=max(\$best,\$l+\$r);\n        return 1+max(\$l,\$r);\n    };\n    \$depth(\$root);\n    return \$best;\n}"],
    'java' => ['code' => "int best=0;\nint diameter(TreeNode root){ best=0; depth(root); return best; }\nint depth(TreeNode n){\n    if(n==null) return 0;\n    int l=depth(n.left), r=depth(n.right);\n    best=Math.max(best,l+r);\n    return 1+Math.max(l,r);\n}"],
    'cpp' => ['code' => "int best=0;\nint depth(TreeNode* n){\n    if(!n) return 0;\n    int l=depth(n->left), r=depth(n->right);\n    best=max(best,l+r);\n    return 1+max(l,r);\n}\nint diameter(TreeNode* root){ best=0; depth(root); return best; }"],
],
'Tree::Determine if Two Trees are Identical' => [
    'python' => ['code' => "def identical(a, b):\n    if not a and not b: return True\n    if not a or not b: return False\n    return a.val == b.val and identical(a.left, b.left) and identical(a.right, b.right)", 'explanation_md' => 'Compare structure + values recursively. O(n).'],
    'php' => ['code' => "<?php\nfunction identical(?TreeNode \$a, ?TreeNode \$b): bool {\n    if (!\$a && !\$b) return true;\n    if (!\$a || !\$b) return false;\n    return \$a->val===\$b->val && identical(\$a->left,\$b->left) && identical(\$a->right,\$b->right);\n}"],
    'java' => ['code' => "boolean identical(TreeNode a, TreeNode b){\n    if(a==null && b==null) return true;\n    if(a==null || b==null) return false;\n    return a.val==b.val && identical(a.left,b.left) && identical(a.right,b.right);\n}"],
    'cpp' => ['code' => "bool identical(TreeNode* a, TreeNode* b){\n    if(!a && !b) return true;\n    if(!a || !b) return false;\n    return a->val==b->val && identical(a->left,b->left) && identical(a->right,b->right);\n}"],
],
'Tree::Count Leaves in Binary Tree' => [
    'python' => ['code' => "def count_leaves(root):\n    if not root: return 0\n    if not root.left and not root.right: return 1\n    return count_leaves(root.left) + count_leaves(root.right)", 'explanation_md' => 'A leaf has no children. O(n).'],
    'php' => ['code' => "<?php\nfunction countLeaves(?TreeNode \$root): int {\n    if (!\$root) return 0;\n    if (!\$root->left && !\$root->right) return 1;\n    return countLeaves(\$root->left) + countLeaves(\$root->right);\n}"],
    'java' => ['code' => "int countLeaves(TreeNode root){\n    if(root==null) return 0;\n    if(root.left==null && root.right==null) return 1;\n    return countLeaves(root.left) + countLeaves(root.right);\n}"],
    'cpp' => ['code' => "int countLeaves(TreeNode* root){\n    if(!root) return 0;\n    if(!root->left && !root->right) return 1;\n    return countLeaves(root->left) + countLeaves(root->right);\n}"],
],
'Binary Search Tree::Insert a node in a BST' => [
    'python' => ['code' => "def insert(root, key):\n    if not root: return TreeNode(key)\n    if key < root.val: root.left = insert(root.left, key)\n    else: root.right = insert(root.right, key)\n    return root", 'explanation_md' => 'Walk down comparing; attach at the empty spot. O(h).'],
    'php' => ['code' => "<?php\nfunction insert(?TreeNode \$root, int \$key): TreeNode {\n    if (!\$root) return new TreeNode(\$key);\n    if (\$key < \$root->val) \$root->left = insert(\$root->left, \$key);\n    else \$root->right = insert(\$root->right, \$key);\n    return \$root;\n}"],
    'java' => ['code' => "TreeNode insert(TreeNode root, int key){\n    if(root==null) return new TreeNode(key);\n    if(key < root.val) root.left = insert(root.left, key);\n    else root.right = insert(root.right, key);\n    return root;\n}"],
    'cpp' => ['code' => "TreeNode* insert(TreeNode* root, int key){\n    if(!root) return new TreeNode(key);\n    if(key < root->val) root->left = insert(root->left, key);\n    else root->right = insert(root->right, key);\n    return root;\n}"],
],
'Binary Search Tree::Minimum element in BST' => [
    'python' => ['code' => "def min_value(root):\n    while root.left:\n        root = root.left\n    return root.val", 'explanation_md' => 'Leftmost node is the minimum. O(h).'],
    'php' => ['code' => "<?php\nfunction minValue(TreeNode \$root): int {\n    while (\$root->left) \$root = \$root->left;\n    return \$root->val;\n}"],
    'java' => ['code' => "int minValue(TreeNode root){\n    while(root.left != null) root = root.left;\n    return root.val;\n}"],
    'cpp' => ['code' => "int minValue(TreeNode* root){\n    while(root->left) root = root->left;\n    return root->val;\n}"],
],
'Heaps::Kth largest element in a stream' => [
    'python' => ['code' => "import heapq\nclass KthLargest:\n    def __init__(self, k, nums):\n        self.k = k; self.h = nums[:]\n        heapq.heapify(self.h)\n        while len(self.h) > k: heapq.heappop(self.h)\n    def add(self, val):\n        heapq.heappush(self.h, val)\n        if len(self.h) > self.k: heapq.heappop(self.h)\n        return self.h[0]", 'explanation_md' => 'Min-heap of size k; root is the k-th largest. add() is O(log k).'],
    'php' => ['code' => "<?php\nclass KthLargest {\n    private SplMinHeap \$h; private int \$k;\n    function __construct(int \$k, array \$nums){\n        \$this->k=\$k; \$this->h=new SplMinHeap();\n        foreach (\$nums as \$n) \$this->add(\$n);\n    }\n    function add(int \$val): int {\n        \$this->h->insert(\$val);\n        if (count(\$this->h) > \$this->k) \$this->h->extract();\n        return \$this->h->top();\n    }\n}"],
    'java' => ['code' => "class KthLargest {\n    PriorityQueue<Integer> h = new PriorityQueue<>(); int k;\n    KthLargest(int k, int[] nums){ this.k=k; for(int n: nums) add(n); }\n    int add(int val){\n        h.offer(val);\n        if(h.size() > k) h.poll();\n        return h.peek();\n    }\n}"],
    'cpp' => ['code' => "class KthLargest {\n    priority_queue<int, vector<int>, greater<int>> h; int k;\npublic:\n    KthLargest(int k, vector<int>& nums): k(k) { for(int n: nums) add(n); }\n    int add(int val){\n        h.push(val);\n        if((int)h.size() > k) h.pop();\n        return h.top();\n    }\n};"],
],
'Heaps::Find median in a stream' => [
    'python' => ['code' => "import heapq\nclass MedianFinder:\n    def __init__(self): self.lo=[]; self.hi=[]  # lo: max-heap (negated), hi: min-heap\n    def add(self, x):\n        heapq.heappush(self.lo, -x)\n        heapq.heappush(self.hi, -heapq.heappop(self.lo))\n        if len(self.hi) > len(self.lo):\n            heapq.heappush(self.lo, -heapq.heappop(self.hi))\n    def median(self):\n        if len(self.lo) > len(self.hi): return -self.lo[0]\n        return (-self.lo[0] + self.hi[0]) / 2", 'explanation_md' => 'Two balanced heaps; median at the tops. add O(log n).'],
    'php' => ['code' => "<?php\nclass MedianFinder {\n    private SplMaxHeap \$lo; private SplMinHeap \$hi;\n    function __construct(){ \$this->lo=new SplMaxHeap(); \$this->hi=new SplMinHeap(); }\n    function add(int \$x): void {\n        \$this->lo->insert(\$x);\n        \$this->hi->insert(\$this->lo->extract());\n        if (count(\$this->hi) > count(\$this->lo)) \$this->lo->insert(\$this->hi->extract());\n    }\n    function median(): float {\n        if (count(\$this->lo) > count(\$this->hi)) return \$this->lo->top();\n        return (\$this->lo->top() + \$this->hi->top()) / 2;\n    }\n}"],
    'java' => ['code' => "class MedianFinder {\n    PriorityQueue<Integer> lo=new PriorityQueue<>(Collections.reverseOrder());\n    PriorityQueue<Integer> hi=new PriorityQueue<>();\n    void add(int x){\n        lo.offer(x); hi.offer(lo.poll());\n        if(hi.size() > lo.size()) lo.offer(hi.poll());\n    }\n    double median(){\n        return lo.size() > hi.size() ? lo.peek() : (lo.peek() + hi.peek()) / 2.0;\n    }\n}"],
    'cpp' => ['code' => "class MedianFinder {\n    priority_queue<int> lo; // max-heap\n    priority_queue<int, vector<int>, greater<int>> hi; // min-heap\npublic:\n    void add(int x){\n        lo.push(x); hi.push(lo.top()); lo.pop();\n        if(hi.size() > lo.size()){ lo.push(hi.top()); hi.pop(); }\n    }\n    double median(){\n        return lo.size() > hi.size() ? lo.top() : (lo.top() + hi.top()) / 2.0;\n    }\n};"],
],
'Greedy::Minimum number of jumps' => [
    'python' => ['code' => "def min_jumps(a):\n    n = len(a)\n    if n <= 1: return 0\n    if a[0] == 0: return -1\n    jumps = 1; far = a[0]; end = a[0]\n    for i in range(1, n):\n        if i == n-1: return jumps\n        far = max(far, i + a[i])\n        if i == end:\n            jumps += 1; end = far\n            if i >= end: return -1\n    return -1", 'explanation_md' => 'Greedy BFS-by-levels over reach. O(n).'],
    'php' => ['code' => "<?php\nfunction minJumps(array \$a): int {\n    \$n=count(\$a);\n    if (\$n<=1) return 0;\n    if (\$a[0]==0) return -1;\n    \$jumps=1; \$far=\$a[0]; \$end=\$a[0];\n    for (\$i=1; \$i<\$n; \$i++) {\n        if (\$i==\$n-1) return \$jumps;\n        \$far=max(\$far, \$i+\$a[\$i]);\n        if (\$i==\$end){ \$jumps++; \$end=\$far; if (\$i>=\$end) return -1; }\n    }\n    return -1;\n}"],
    'java' => ['code' => "int minJumps(int[] a){\n    int n=a.length;\n    if(n<=1) return 0;\n    if(a[0]==0) return -1;\n    int jumps=1, far=a[0], end=a[0];\n    for(int i=1;i<n;i++){\n        if(i==n-1) return jumps;\n        far=Math.max(far, i+a[i]);\n        if(i==end){ jumps++; end=far; if(i>=end) return -1; }\n    }\n    return -1;\n}"],
    'cpp' => ['code' => "int minJumps(vector<int>& a){\n    int n=a.size();\n    if(n<=1) return 0;\n    if(a[0]==0) return -1;\n    int jumps=1, far=a[0], end=a[0];\n    for(int i=1;i<n;i++){\n        if(i==n-1) return jumps;\n        far=max(far, i+a[i]);\n        if(i==end){ jumps++; end=far; if(i>=end) return -1; }\n    }\n    return -1;\n}"],
],
'Hashing::Longest consecutive subsequence' => [
    'python' => ['code' => "def longest_consecutive(a):\n    s = set(a); best = 0\n    for x in s:\n        if x - 1 not in s:\n            y = x\n            while y + 1 in s: y += 1\n            best = max(best, y - x + 1)\n    return best", 'explanation_md' => 'Only start counting from sequence starts. O(n).'],
    'php' => ['code' => "<?php\nfunction longestConsecutive(array \$a): int {\n    \$s = array_flip(\$a); \$best = 0;\n    foreach (\$s as \$x => \$_) {\n        if (!isset(\$s[\$x-1])) {\n            \$y = \$x;\n            while (isset(\$s[\$y+1])) \$y++;\n            \$best = max(\$best, \$y - \$x + 1);\n        }\n    }\n    return \$best;\n}"],
    'java' => ['code' => "int longestConsecutive(int[] a){\n    Set<Integer> s=new HashSet<>(); for(int x:a) s.add(x);\n    int best=0;\n    for(int x:s) if(!s.contains(x-1)){\n        int y=x; while(s.contains(y+1)) y++;\n        best=Math.max(best, y-x+1);\n    }\n    return best;\n}"],
    'cpp' => ['code' => "int longestConsecutive(vector<int>& a){\n    unordered_set<int> s(a.begin(), a.end()); int best=0;\n    for(int x: s) if(!s.count(x-1)){\n        int y=x; while(s.count(y+1)) y++;\n        best=max(best, y-x+1);\n    }\n    return best;\n}"],
],
'Graphs::Detect cycle in an undirected graph' => [
    'python' => ['code' => "def has_cycle(n, adj):\n    seen = [False]*n\n    def dfs(u, parent):\n        seen[u] = True\n        for v in adj[u]:\n            if not seen[v]:\n                if dfs(v, u): return True\n            elif v != parent:\n                return True\n        return False\n    for i in range(n):\n        if not seen[i] and dfs(i, -1): return True\n    return False", 'explanation_md' => 'DFS; a visited non-parent neighbor means a cycle. O(V+E).'],
    'php' => ['code' => "<?php\nfunction hasCycle(int \$n, array \$adj): bool {\n    \$seen = array_fill(0, \$n, false);\n    \$dfs = function(\$u, \$p) use (&\$dfs, &\$seen, \$adj) {\n        \$seen[\$u] = true;\n        foreach (\$adj[\$u] ?? [] as \$v) {\n            if (!\$seen[\$v]) { if (\$dfs(\$v, \$u)) return true; }\n            elseif (\$v !== \$p) return true;\n        }\n        return false;\n    };\n    for (\$i=0;\$i<\$n;\$i++) if (!\$seen[\$i] && \$dfs(\$i,-1)) return true;\n    return false;\n}"],
    'java' => ['code' => "boolean dfs(int u, int p, boolean[] seen, List<List<Integer>> adj){\n    seen[u]=true;\n    for(int v: adj.get(u)){\n        if(!seen[v]){ if(dfs(v,u,seen,adj)) return true; }\n        else if(v!=p) return true;\n    }\n    return false;\n}\nboolean hasCycle(int n, List<List<Integer>> adj){\n    boolean[] seen=new boolean[n];\n    for(int i=0;i<n;i++) if(!seen[i] && dfs(i,-1,seen,adj)) return true;\n    return false;\n}"],
    'cpp' => ['code' => "bool dfs(int u, int p, vector<bool>& seen, vector<vector<int>>& adj){\n    seen[u]=true;\n    for(int v: adj[u]){\n        if(!seen[v]){ if(dfs(v,u,seen,adj)) return true; }\n        else if(v!=p) return true;\n    }\n    return false;\n}\nbool hasCycle(int n, vector<vector<int>>& adj){\n    vector<bool> seen(n,false);\n    for(int i=0;i<n;i++) if(!seen[i] && dfs(i,-1,seen,adj)) return true;\n    return false;\n}"],
],
'Dynamic Programming::Longest Increasing Subsequence' => [
    'python' => ['code' => "import bisect\ndef lis(a):\n    tails = []\n    for x in a:\n        i = bisect.bisect_left(tails, x)\n        if i == len(tails): tails.append(x)\n        else: tails[i] = x\n    return len(tails)", 'explanation_md' => 'Patience sorting with binary search. O(n log n).'],
    'php' => ['code' => "<?php\nfunction lis(array \$a): int {\n    \$tails = [];\n    foreach (\$a as \$x) {\n        \$lo=0; \$hi=count(\$tails);\n        while (\$lo<\$hi){ \$m=intdiv(\$lo+\$hi,2); if (\$tails[\$m]<\$x) \$lo=\$m+1; else \$hi=\$m; }\n        if (\$lo===count(\$tails)) \$tails[]=\$x; else \$tails[\$lo]=\$x;\n    }\n    return count(\$tails);\n}"],
    'java' => ['code' => "int lis(int[] a){\n    int[] tails=new int[a.length]; int len=0;\n    for(int x: a){\n        int i=Arrays.binarySearch(tails,0,len,x);\n        if(i<0) i=-(i+1);\n        tails[i]=x; if(i==len) len++;\n    }\n    return len;\n}"],
    'cpp' => ['code' => "int lis(vector<int>& a){\n    vector<int> tails;\n    for(int x: a){\n        auto it = lower_bound(tails.begin(), tails.end(), x);\n        if(it==tails.end()) tails.push_back(x); else *it=x;\n    }\n    return tails.size();\n}"],
],
'Dynamic Programming::Edit Distance' => [
    'python' => ['code' => "def edit_distance(a, b):\n    m, n = len(a), len(b)\n    dp = [[0]*(n+1) for _ in range(m+1)]\n    for i in range(m+1): dp[i][0] = i\n    for j in range(n+1): dp[0][j] = j\n    for i in range(1, m+1):\n        for j in range(1, n+1):\n            if a[i-1] == b[j-1]: dp[i][j] = dp[i-1][j-1]\n            else: dp[i][j] = 1 + min(dp[i-1][j], dp[i][j-1], dp[i-1][j-1])\n    return dp[m][n]", 'explanation_md' => 'Levenshtein DP. O(m·n).'],
    'php' => ['code' => "<?php\nfunction editDistance(string \$a, string \$b): int {\n    \$m=strlen(\$a); \$n=strlen(\$b);\n    \$dp=array_fill(0,\$m+1,array_fill(0,\$n+1,0));\n    for (\$i=0;\$i<=\$m;\$i++) \$dp[\$i][0]=\$i;\n    for (\$j=0;\$j<=\$n;\$j++) \$dp[0][\$j]=\$j;\n    for (\$i=1;\$i<=\$m;\$i++) for (\$j=1;\$j<=\$n;\$j++)\n        \$dp[\$i][\$j]= \$a[\$i-1]===\$b[\$j-1] ? \$dp[\$i-1][\$j-1]\n            : 1 + min(\$dp[\$i-1][\$j], \$dp[\$i][\$j-1], \$dp[\$i-1][\$j-1]);\n    return \$dp[\$m][\$n];\n}"],
    'java' => ['code' => "int editDistance(String a, String b){\n    int m=a.length(), n=b.length();\n    int[][] dp=new int[m+1][n+1];\n    for(int i=0;i<=m;i++) dp[i][0]=i;\n    for(int j=0;j<=n;j++) dp[0][j]=j;\n    for(int i=1;i<=m;i++) for(int j=1;j<=n;j++)\n        dp[i][j]= a.charAt(i-1)==b.charAt(j-1) ? dp[i-1][j-1]\n            : 1 + Math.min(dp[i-1][j-1], Math.min(dp[i-1][j], dp[i][j-1]));\n    return dp[m][n];\n}"],
    'cpp' => ['code' => "int editDistance(string a, string b){\n    int m=a.size(), n=b.size();\n    vector<vector<int>> dp(m+1, vector<int>(n+1));\n    for(int i=0;i<=m;i++) dp[i][0]=i;\n    for(int j=0;j<=n;j++) dp[0][j]=j;\n    for(int i=1;i<=m;i++) for(int j=1;j<=n;j++)\n        dp[i][j]= a[i-1]==b[j-1] ? dp[i-1][j-1]\n            : 1 + min({dp[i-1][j], dp[i][j-1], dp[i-1][j-1]});\n    return dp[m][n];\n}"],
],
'Dynamic Programming::House Robber' => [
    'python' => ['code' => "def rob(a):\n    prev = cur = 0\n    for x in a:\n        prev, cur = cur, max(cur, prev + x)\n    return cur", 'explanation_md' => 'At each house: skip or rob+best-2-ago. O(n), O(1).'],
    'php' => ['code' => "<?php\nfunction rob(array \$a): int {\n    \$prev=0; \$cur=0;\n    foreach (\$a as \$x){ \$t=max(\$cur, \$prev+\$x); \$prev=\$cur; \$cur=\$t; }\n    return \$cur;\n}"],
    'java' => ['code' => "int rob(int[] a){\n    int prev=0, cur=0;\n    for(int x: a){ int t=Math.max(cur, prev+x); prev=cur; cur=t; }\n    return cur;\n}"],
    'cpp' => ['code' => "int rob(vector<int>& a){\n    int prev=0, cur=0;\n    for(int x: a){ int t=max(cur, prev+x); prev=cur; cur=t; }\n    return cur;\n}"],
],
'Dynamic Programming::Count ways to reach the n\'th stair' => [
    'python' => ['code' => "def count_ways(n):\n    a, b = 1, 1\n    for _ in range(n):\n        a, b = b, a + b\n    return a", 'explanation_md' => 'Same recurrence as Fibonacci. O(n), O(1).'],
    'php' => ['code' => "<?php\nfunction countWays(int \$n): int {\n    \$a=1; \$b=1;\n    for (\$i=0;\$i<\$n;\$i++){ \$t=\$a+\$b; \$a=\$b; \$b=\$t; }\n    return \$a;\n}"],
    'java' => ['code' => "long countWays(int n){\n    long a=1, b=1;\n    for(int i=0;i<n;i++){ long t=a+b; a=b; b=t; }\n    return a;\n}"],
    'cpp' => ['code' => "long long countWays(int n){\n    long long a=1, b=1;\n    for(int i=0;i<n;i++){ long long t=a+b; a=b; b=t; }\n    return a;\n}"],
],

];
