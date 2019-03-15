<?php

declare(strict_types=1);

namespace Vdlp\RedirectConditions\Classes;

use Vdlp\Redirect\Classes\Contracts\RedirectConditionInterface;
use Vdlp\RedirectConditions\Models\ConditionParameter;

/**
 * Class Condition
 *
 * @package Vdlp\RedirectConditions\Conditions
 */
abstract class Condition implements RedirectConditionInterface
{
    /**
     * Get the parameters from database.
     *
     * @param int $redirectId
     * @return array
     */
    protected function getParameters(int $redirectId): array
    {
        $conditionParameter = ConditionParameter::query()
            ->where('redirect_id', '=', $redirectId)
            ->where('condition_code', '=', $this->getCode())
            ->first();

        if ($conditionParameter) {
            if ($conditionParameter->getAttribute('is_enabled') === null) {
                return [];
            }

            return (array) $conditionParameter->getAttribute('parameters');
        }

        return [];
    }
}
