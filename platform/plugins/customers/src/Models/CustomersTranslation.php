<?php

namespace Botble\Customers\Models;

use Botble\Base\Models\BaseModel;

class CustomersTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'customers_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
