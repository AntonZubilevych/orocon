<?php

function convertToTree(array $flat, $idField = 'id',
                       $parentIdField = 'parentId',
                       $childNodesField = 'childNodes') {
    $indexed = [];
    // first pass - get the array indexed by the primary id
    foreach ($flat as $row) {
        $indexed[$row[$idField]] = $row;
        $indexed[$row[$idField]][$childNodesField] = [];
    }

    //second pass
    $root = null;
    foreach ($indexed as $id => $row) {
        $indexed[$row[$parentIdField]][$childNodesField][$id] =& $indexed[$id];
        if (!$row[$parentIdField]) {
            $root = $id;
        }
    }

    return [$root => $indexed[$root]];
}

// Usage:
$rows = [
    [
        'id' => 1,
        'parentId' => null,
        'name' => 'Menu',
    ],
    [
        'id' => 2,
        'parentId' => 1,
        'name' => 'Item 1-1',
    ],
    [
        'id' => 3,
        'parentId' => 1,
        'name' => 'Item 2-1',
    ],
    [
        'id' => 4,
        'parentId' => 1,
        'name' => 'Item 1-2',
    ],
];

var_dump(convertToTree($rows));
