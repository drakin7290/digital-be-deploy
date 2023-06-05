<?php

namespace Botble\Customers\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Customers\Repositories\Interfaces\CustomersInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
use Carbon\Carbon;

class CustomersTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * CustomersTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param CustomersInterface $customersRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CustomersInterface $customersRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $customersRepository;

        if (!Auth::user()->hasAnyPermission(['customers.edit', 'customers.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('customers.edit')) {
                    return $item->name;
                }
                return Html::link(route('customers.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('attendance', function ($item) {
                $date = Carbon::parse(now(config('app.timezone'))->format('d-m-Y'))->toDateString();
                if ($date == $item->attendance_day) {
                    $style = '
                        background-color: #198754; 
                        color: #fff; 
                        width: max-content;
                        padding: 2px 6px;
                        border-radius: 4px;
                    ';
                    return '<label style="'.$style.'">'.$item->attendance.'</label>';
                }
                return '<label style="padding: 0px 6px;">'.$item->attendance.'</label>';
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('is_active', function ($item) {
                return $item->is_active ? '✅' : '❌';
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('customers.edit', 'customers.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'name',
                'student_id',
                'email',
                'gender',
                'phone',
                'is_active',
                'birthday',
                'attendance',
                'attendance_day',
                'avatar',
                'created_at'
           ]);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            // 'id' => [
            //     'title' => trans('core/base::tables.id'),
            //     'width' => '20px',
            // ],
            'name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'student_id' => [
                'title' => 'Student ID',
                'class' => 'text-start',
            ],
            'attendance' => [
                'title' => trans('core/base::tables.attendance'),
                'class' => 'text-start',
            ],
            'gender' => [
                'title' => trans('core/base::tables.gender'),
                'class' => 'text-start',
            ],
            'email' => [
                'title' => trans('core/base::tables.email'),
                'class' => 'text-start',
            ],
            'phone' => [
                'title' => trans('core/base::tables.phone'),
                'class' => 'text-start',
            ],
            'birthday' => [
                'title' => trans('core/base::tables.birthday'),
                'width' => '100px',
            ],
            'is_active' => [
                'title' => trans('core/base::tables.active'),
                'class' => 'text-start',
            ],
            // 'created_at' => [
            //     'title' => trans('core/base::tables.created_at'),
            //     'width' => '100px',
            // ],
            // 'status' => [
            //     'title' => trans('core/base::tables.status'),
            //     'width' => '100px',
            // ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton(route('customers.create'), 'customers.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('customers.deletes'), 'customers.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    // public function getBulkChanges(): array
    // {
    //     return [
    //         'name' => [
    //             'title'    => trans('core/base::tables.name'),
    //             'type'     => 'text',
    //             'validate' => 'required|max:120',
    //         ],
    //         'status' => [
    //             'title'    => trans('core/base::tables.status'),
    //             'type'     => 'select',
    //             'choices'  => BaseStatusEnum::labels(),
    //             'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
    //         ],
    //         'created_at' => [
    //             'title' => trans('core/base::tables.created_at'),
    //             'type'  => 'date',
    //         ],
    //     ];
    // }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }
}
