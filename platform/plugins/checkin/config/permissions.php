<?php

return [
    [
        'name' => 'Checkins',
        'flag' => 'checkin.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'checkin.create',
        'parent_flag' => 'checkin.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'checkin.edit',
        'parent_flag' => 'checkin.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'checkin.destroy',
        'parent_flag' => 'checkin.index',
    ],
];
