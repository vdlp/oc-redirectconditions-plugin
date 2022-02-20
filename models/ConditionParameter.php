<?php

declare(strict_types=1);

namespace Vdlp\RedirectConditions\Models;

use October\Rain\Database\Model;
use Vdlp\Redirect\Models\Redirect;

class ConditionParameter extends Model
{
    public $belongsTo = [
        'redirect' => Redirect::class,
    ];

    protected $table = 'vdlp_redirectconditions_condition_parameters';

    protected $guarded = [];

    protected $jsonable = [
        'parameters',
    ];
}
