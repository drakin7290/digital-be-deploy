<?php

namespace Botble\Checkin\Models;

use Botble\Base\Models\BaseModel;

class CheckinTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'checkins_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'checkins_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
