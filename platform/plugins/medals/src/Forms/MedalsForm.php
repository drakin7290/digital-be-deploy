<?php

namespace Botble\Medals\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Medals\Http\Requests\MedalsRequest;
use Botble\Medals\Models\Medals;
use Assets;

class MedalsForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScriptsDirectly(['/vendor/core/plugins/medals/js/medal-admin.js']);
        $this
            ->setupModel(new Medals)
            ->setValidatorClass(MedalsRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label'      => 'Description',
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('is_foreign_link_icon', 'checkbox', [
                'label'      => 'Use foreign link',
                'label_attr' => ['class' => 'control-label'],
                'value'      => 1,
            ])
            ->add('icon', 'mediaImage', [
                'label'      => 'Icon',
                'label_attr' => ['class' => 'control-label required icon-image-media'],
            ])
            ->add('icon_url', 'text', [
                'label'      => 'Icon',
                'label_attr' => ['class' => 'control-label required icon-image-url'],
            ])
            // ->add('status', 'customSelect', [
            //     'label'      => trans('core/base::tables.status'),
            //     'label_attr' => ['class' => 'control-label required'],
            //     'attr'       => [
            //         'class' => 'form-control select-full',
            //     ],
            //     'choices'    => BaseStatusEnum::labels(),
            // ])
            ->setBreakFieldPoint('is_foreign_link_icon');
    }
}
