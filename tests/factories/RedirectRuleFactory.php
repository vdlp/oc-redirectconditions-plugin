<?php

declare(strict_types=1);

namespace Vdlp\RedirectConditions\Tests\Factories;

use Carbon\Carbon;
use Vdlp\Redirect\Classes\RedirectRule;
use Vdlp\Redirect\Models\Redirect;

class RedirectRuleFactory
{
    public static function createRedirectRule(): RedirectRule
    {
        return new RedirectRule([
            'id' => 1,
            'match_type' => Redirect::TYPE_EXACT,
            'target_type' => Redirect::TARGET_TYPE_PATH_URL,
            'from_scheme' => Redirect::SCHEME_AUTO,
            'from_url' => '/from/url',
            'to_url' => '/to/url',
            'to_scheme' => Redirect::SCHEME_HTTPS,
            'status_code' => 301,
            'ignore_query_parameters' => true,
            'from_date' => Carbon::today()->toDateString(),
            'to_date' => Carbon::tomorrow()->toDateTimeString(),
        ]);
    }
}
