<?php

namespace Botble\Medals\Models;

use Botble\Base\Models\BaseModel;

class MedalsTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'medals_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'medals_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
