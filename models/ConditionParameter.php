<?php

declare(strict_types=1);

namespace Vdlp\RedirectConditions\Models;

use October\Rain\Database\Model;
use Vdlp\Redirect\Models\Redirect;

/**
 * Class Parameter
 *
 * @package Vdlp\RedirectConditions\Models
 */
class ConditionParameter extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'vdlp_redirectconditions_condition_parameters';

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    public $belongsTo = [
        'redirect' => Redirect::class
    ];

    /**
     * {@inheritdoc}
     */
    protected $jsonable = [
        'parameters'
    ];
}
