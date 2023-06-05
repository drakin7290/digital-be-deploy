<?php

return [
    [
        'name' => 'Customers',
        'flag' => 'customers.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'customers.create',
        'parent_flag' => 'customers.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'customers.edit',
        'parent_flag' => 'customers.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'customers.destroy',
        'parent_flag' => 'customers.index',
    ],
];
