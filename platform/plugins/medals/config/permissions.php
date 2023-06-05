<?php

return [
    [
        'name' => 'Medals',
        'flag' => 'medals.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'medals.create',
        'parent_flag' => 'medals.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'medals.edit',
        'parent_flag' => 'medals.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'medals.destroy',
        'parent_flag' => 'medals.index',
    ],
];
