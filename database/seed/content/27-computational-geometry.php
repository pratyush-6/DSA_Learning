<?php
return [
    'title'       => 'Computational Geometry',
    'slug'        => 'computational-geometry',
    'level'       => 'advanced',
    'icon'        => 'bi-bounding-box',
    'sort_order'  => 27,
    'description' => 'Algorithms on points, lines, and polygons: orientation, convex hull, and more.',

    'topics' => [
        [
            'title'   => 'Points, Orientation & Convex Hull',
            'slug'    => 'geometry-basics',
            'summary' => 'The cross product and the building blocks of geometric algorithms.',
            'theory_md' => <<<MD
Computational geometry solves problems about points, segments, and polygons.

**Orientation** of three points (p, q, r) via the **cross product**:
```
val = (q.y - p.y)*(r.x - q.x) - (q.x - p.x)*(r.y - q.y)
val == 0 -> collinear
val  > 0 -> clockwise
val  < 0 -> counter-clockwise
```
This single primitive powers segment intersection, point-in-polygon, and hull algorithms.

**Convex hull:** the smallest convex polygon enclosing a set of points. **Graham scan**
and **Andrew’s monotone chain** compute it in **O(n log n)** (dominated by sorting).

Other staples: **Euclidean distance**, **line/segment intersection**, **polygon area**
(shoelace formula), and **closest pair of points** (divide & conquer, O(n log n)).
MD,
            'complexity_md' => "Orientation/distance: O(1). Convex hull & closest pair: **O(n log n)**. Polygon area (shoelace): O(n).",
            'real_world_md' => <<<MD
- **Computer graphics & games** (collision detection, rendering).
- **GIS / mapping** (region boundaries, nearest facility).
- **Robotics path planning** and **CAD**.
- **Clustering** and outlier detection.
MD,
            'code' => [
                ['language' => 'python', 'label' => 'Orientation + shoelace area', 'code' => <<<'CODE'
def orientation(p, q, r):
    val = (q[1]-p[1])*(r[0]-q[0]) - (q[0]-p[0])*(r[1]-q[1])
    if val == 0: return 0          # collinear
    return 1 if val > 0 else 2     # CW or CCW

def polygon_area(points):          # shoelace formula
    n = len(points); area = 0
    for i in range(n):
        x1, y1 = points[i]
        x2, y2 = points[(i + 1) % n]
        area += x1 * y2 - x2 * y1
    return abs(area) / 2
CODE],
                ['language' => 'php', 'label' => 'Orientation + shoelace area', 'code' => <<<'CODE'
<?php
function orientation(array $p, array $q, array $r): int {
    $val = ($q[1]-$p[1])*($r[0]-$q[0]) - ($q[0]-$p[0])*($r[1]-$q[1]);
    if ($val == 0) return 0;
    return $val > 0 ? 1 : 2;
}
function polygonArea(array $points): float {
    $n = count($points); $area = 0;
    for ($i = 0; $i < $n; $i++) {
        [$x1, $y1] = $points[$i];
        [$x2, $y2] = $points[($i + 1) % $n];
        $area += $x1 * $y2 - $x2 * $y1;
    }
    return abs($area) / 2;
}
CODE],
                ['language' => 'java', 'label' => 'Orientation + shoelace area', 'code' => <<<'CODE'
int orientation(int[] p, int[] q, int[] r){
    long val = (long)(q[1]-p[1])*(r[0]-q[0]) - (long)(q[0]-p[0])*(r[1]-q[1]);
    if(val == 0) return 0;
    return val > 0 ? 1 : 2;
}
double polygonArea(int[][] pts){
    int n = pts.length; long area = 0;
    for(int i=0;i<n;i++){
        int[] a = pts[i], b = pts[(i+1)%n];
        area += (long)a[0]*b[1] - (long)b[0]*a[1];
    }
    return Math.abs(area) / 2.0;
}
CODE],
                ['language' => 'cpp', 'label' => 'Orientation + shoelace area', 'code' => <<<'CODE'
int orientation(array<int,2> p, array<int,2> q, array<int,2> r){
    long long val = 1LL*(q[1]-p[1])*(r[0]-q[0]) - 1LL*(q[0]-p[0])*(r[1]-q[1]);
    if(val == 0) return 0;
    return val > 0 ? 1 : 2;
}
double polygonArea(vector<array<int,2>>& pts){
    int n = pts.size(); long long area = 0;
    for(int i=0;i<n;i++){
        auto a = pts[i], b = pts[(i+1)%n];
        area += 1LL*a[0]*b[1] - 1LL*b[0]*a[1];
    }
    return abs(area) / 2.0;
}
CODE],
            ],
        ],
    ],

    'interview' => [
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'How do you determine the orientation of three points?',
         'answer_md' => 'Use the sign of the **cross product** of vectors PQ and QR. Zero = collinear, positive/negative = clockwise/counter-clockwise. It avoids floating-point slope division.',
         'companies' => ['Google', 'Amazon']],
        ['type' => 'conceptual', 'difficulty' => 'medium',
         'question' => 'What is a convex hull and what is the cost to compute it?',
         'answer_md' => 'The smallest convex polygon containing all given points. Algorithms like Graham scan or Andrew’s monotone chain compute it in **O(n log n)**, dominated by the initial sort.',
         'companies' => ['Google', 'Microsoft']],
        ['type' => 'conceptual', 'difficulty' => 'hard',
         'question' => 'How is the closest pair of points found faster than O(n²)?',
         'answer_md' => 'Divide and conquer: split by x, solve each half, then check only points within the current minimum distance of the dividing line (a strip), sorted by y. O(n log n).',
         'companies' => ['Google']],
    ],

    'problems' => [
        ['title' => 'Area of a Polygon (Shoelace)', 'slug' => 'geometry-polygon-area', 'difficulty' => 'medium',
         'statement_md' => "Given the vertices of a simple polygon in order, compute its area using the shoelace formula.",
         'examples_md' => "```\n[(0,0),(4,0),(4,3),(0,3)] -> 12.0\n```",
         'constraints_md' => "- Vertices are given in order (clockwise or counter-clockwise).",
         'solutions' => [
            'python' => ['code' => "def polygon_area(points):\n    n = len(points); area = 0\n    for i in range(n):\n        x1, y1 = points[i]\n        x2, y2 = points[(i + 1) % n]\n        area += x1 * y2 - x2 * y1\n    return abs(area) / 2", 'explanation_md' => 'Sum the cross products of consecutive vertices; half the absolute value is the area. O(n).'],
            'php' => ['code' => "<?php\nfunction polygonArea(array \$points): float {\n    \$n = count(\$points); \$area = 0;\n    for (\$i = 0; \$i < \$n; \$i++) {\n        [\$x1, \$y1] = \$points[\$i];\n        [\$x2, \$y2] = \$points[(\$i + 1) % \$n];\n        \$area += \$x1 * \$y2 - \$x2 * \$y1;\n    }\n    return abs(\$area) / 2;\n}", 'explanation_md' => ''],
            'java' => ['code' => "double polygonArea(int[][] pts){\n    int n = pts.length; long area = 0;\n    for(int i=0;i<n;i++){\n        int[] a = pts[i], b = pts[(i+1)%n];\n        area += (long)a[0]*b[1] - (long)b[0]*a[1];\n    }\n    return Math.abs(area) / 2.0;\n}", 'explanation_md' => ''],
            'cpp' => ['code' => "double polygonArea(vector<array<int,2>>& pts){\n    int n = pts.size(); long long area = 0;\n    for(int i=0;i<n;i++){\n        auto a = pts[i], b = pts[(i+1)%n];\n        area += 1LL*a[0]*b[1] - 1LL*b[0]*a[1];\n    }\n    return abs(area) / 2.0;\n}", 'explanation_md' => ''],
         ]],
    ],

    'quiz' => [
        'title' => 'Computational Geometry Quiz',
        'questions' => [
            ['question' => 'The orientation of three points is found using the:',
             'options' => [['text' => 'Cross product sign', 'correct' => true], ['text' => 'Dot product only', 'correct' => false], ['text' => 'Sum of coordinates', 'correct' => false], ['text' => 'Slope average', 'correct' => false]]],
            ['question' => 'Convex hull of n points typically costs:',
             'options' => [['text' => 'O(n log n)', 'correct' => true], ['text' => 'O(n)', 'correct' => false], ['text' => 'O(n²)', 'correct' => false], ['text' => 'O(log n)', 'correct' => false]]],
            ['question' => 'The shoelace formula computes a polygon’s:',
             'options' => [['text' => 'Area', 'correct' => true], ['text' => 'Perimeter', 'correct' => false], ['text' => 'Number of vertices', 'correct' => false], ['text' => 'Centroid only', 'correct' => false]]],
        ],
    ],
];
