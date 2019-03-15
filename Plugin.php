<?php

declare(strict_types=1);

namespace Vdlp\RedirectConditions;

use Backend\Classes\FormTabs;
use Backend\Widgets\Form;
use Illuminate\Support\Facades\Event;
use System\Classes\PluginBase;
use Vdlp\Redirect;
use Vdlp\RedirectConditions\Models\ConditionParameter;

/**
 * Class Plugin
 *
 * @package Vdlp\RedirectConditions
 */
class Plugin extends PluginBase
{
    /**
     * {@inheritdoc}
     */
    public $require = [
        'Vdlp.Redirect',
    ];

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * {@inheritdoc}
     */
    public function pluginDetails(): array
    {
        return [
            'name' => 'Redirect Conditions',
            'description' => 'Allows plugin developers to create Redirect Condition extension plugins.',
            'author' => 'Van der Let & Partners',
            'icon' => 'icon-link',
            'homepage' => 'https://octobercms.com/plugin/vdlp-redirect',
        ];
    }


    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        Redirect\Models\Redirect::extend(function (Redirect\Models\Redirect $redirect) {
            $redirect->hasMany['conditionParameters'] = [
                ConditionParameter::class,
                'table' => 'vdlp_redirectconditions_condition_parameters',
            ];
        });

        Event::listen('vdlp.redirect.afterRedirectSave', function (Redirect\Models\Redirect $redirect) {
            /** @var Redirect\Classes\Contracts\RedirectManagerInterface $manager */
            $manager = resolve(Redirect\Classes\Contracts\RedirectManagerInterface::class);

            $postData = request()->get('Redirect', []);

            foreach ($manager->getConditions() as $condition) {
                /** @var Redirect\Classes\Contracts\RedirectConditionInterface $condition */
                $condition = app($condition);

                $formValues = array_get(
                    $postData,
                    "_VdlpRedirectConditionParameters.{$condition->getCode()}",
                    []
                );

                $isEnabled = (bool) array_get(
                    $postData,
                    "_VdlpRedirectConditionEnabled.{$condition->getCode()}",
                    false
                );

                ConditionParameter::query()->updateOrCreate(
                    [
                        'redirect_id' => $redirect->getKey(),
                        'condition_code' => $condition->getCode(),
                    ],
                    [
                        'is_enabled' => $isEnabled ? date('Y-m-d H:i:s') : null,
                        'parameters' => $formValues,
                    ]
                );
            }
        });

        Event::listen('backend.form.extendFields', function (Form $form) {
            if (!$form->getController() instanceof Redirect\Controllers\Redirects) {
                return;
            }

            if (!($form->model instanceof Redirect\Models\Redirect)) {
                return;
            }

            /** @var Redirect\Classes\Contracts\RedirectManagerInterface $manager */
            $manager = resolve(Redirect\Classes\Contracts\RedirectManagerInterface::class);

            foreach ($manager->getConditions() as $condition) {
                /** @var Redirect\Classes\Contracts\RedirectConditionInterface $condition */
                $condition = app($condition);

                $formParentFieldKey = sprintf(
                    '_VdlpRedirectConditionEnabled[%s]',
                    $condition->getCode()
                );

                $form->addFields([
                    $formParentFieldKey => [
                        'label' => $condition->getDescription(),
                        'tab' => Redirect\Classes\Contracts\RedirectConditionInterface::TAB_NAME,
                        'type' => 'checkbox',
                        'comment' => $condition->getExplanation(),
                    ]
                ], FormTabs::SECTION_PRIMARY);

                foreach ($condition->getFormConfig() as $formFieldKey => $formField) {
                    $formFieldKey = sprintf(
                        '_VdlpRedirectConditionParameters[%s][%s]',
                        $condition->getCode(),
                        $formFieldKey
                    );

                    $formField['trigger'] = [
                        'action' => 'show',
                        'field' => $formParentFieldKey,
                        'condition' => 'checked'
                    ];

                    $formField['cssClass'] = 'field-indent';

                    $form->addFields([
                        $formFieldKey => $formField
                    ], FormTabs::SECTION_PRIMARY);
                }
            }

            if (!$form->model->exists) {
                return;
            }

            /*
             * Populate the form with the stored values.
             */

            $data = [];

            /** @var ConditionParameter $conditionParameter */
            foreach ($form->model->getAttribute('conditionParameters') as $conditionParameter) {
                $data['_VdlpRedirectConditionParameters'][$conditionParameter->getAttribute('condition_code')]
                    = $conditionParameter->getAttribute('parameters');
                $data['_VdlpRedirectConditionEnabled'][$conditionParameter->getAttribute('condition_code')]
                    = (bool) $conditionParameter->getAttribute('is_enabled');
            }

            $form->setFormValues($data);
        });
    }
}
