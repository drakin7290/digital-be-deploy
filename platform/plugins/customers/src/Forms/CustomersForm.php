<?php

namespace Botble\Customers\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Customers\Http\Requests\CustomersRequest;
use Botble\Customers\Models\Customers;
use Assets;
use BaseHelper;

class CustomersForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScriptsDirectly(['/vendor/core/plugins/member/js/member-admin.js']);
        $this
            ->setupModel(new Customers)
            ->setValidatorClass(CustomersRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('student_id', 'text', [
                'label'      => 'Student ID',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'DTHxxxyyy',
                    'data-counter' => 120,
                ],
            ])
            ->add('email', 'email', [
                'label'      => 'Email',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Email',
                    'data-counter' => 120,
                ],
            ])
            ->add('phone', 'tel', [
                'label'      => 'Phone',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => 'Phone',
                    'data-counter' => 120,
                ],
            ])
            ->add('is_change_password', 'checkbox', [
                'label'      => 'Change password',
                'label_attr' => ['class' => 'control-label'],
                'value'      => 1,
            ])
            ->add('password', 'password', [
                'label'      => 'Password',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Password',
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel()->id ? ' hidden' : null),
                ],
            ])
            // ->add('password_confirmation', 'password', [
            //     'label'      => trans('plugins/member::member.form.password_confirmation'),
            //     'label_attr' => ['class' => 'control-label required'],
            //     'attr'       => [
            //         'data-counter' => 60,
            //     ],
            //     'wrapper'    => [
            //         'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel()->id ? ' hidden' : null),
            //     ],
            // ])
            ->add('gender', 'customSelect', [
                'label'      => 'Gender',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => [
                    1 => __('Male'),
                    2 => __('Female'),
                ],
            ])
            ->add('birthday', 'date', [
                'label'      => 'Birthday',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-date-format' => 'dd-mm-yyyy',
                ],
                'default_value' => now(config('app.timezone'))->format('d-m-Y'),
            ])
            // ->add('status', 'customSelect', [
            //     'label'      => trans('core/base::tables.status'),
            //     'label_attr' => ['class' => 'control-label required'],
            //     'attr'       => [
            //         'class' => 'form-control select-full',
            //     ],
            //     'choices'    => BaseStatusEnum::labels(),
            // ])
            ->add('avatar', 'mediaImage', [
                'label'      => 'Avatar',
                'label_attr' => ['class' => 'control-label'],
            ])
            ->setBreakFieldPoint('avatar');
    }
}