<?php

namespace Botble\Customers\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Customers\Http\Requests\CustomersRequest;
use Botble\Customers\Repositories\Interfaces\CustomersInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Customers\Tables\CustomersTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Customers\Forms\CustomersForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CustomersController extends BaseController
{
    /**
     * @var CustomersInterface
     */
    protected $customersRepository;

    /**
     * @param CustomersInterface $customersRepository
     */
    public function __construct(CustomersInterface $customersRepository)
    {
        $this->customersRepository = $customersRepository;
    }

    /**
     * @param CustomersTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CustomersTable $table)
    {
        page_title()->setTitle(trans('plugins/customers::customers.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/customers::customers.create'));

        return $formBuilder->create(CustomersForm::class)->remove('is_change_password')->renderForm();
    }

    /**
     * @param CustomersRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(CustomersRequest $request, BaseHttpResponse $response)
    {
        // $customers = $this->customersRepository->createOrUpdate($request->input());
        $customers = $this->customersRepository->getModel();
        $customers->fill($request->input());
        $customers->password = Hash::make($request->input('password'));
        $customers->birthday = Carbon::parse($request->input('birthday'))->toDateString();
        $customers = $this->customersRepository->createOrUpdate($customers);
        event(new CreatedContentEvent(CUSTOMERS_MODULE_SCREEN_NAME, $request, $customers));

        return $response
            ->setPreviousUrl(route('customers.index'))
            ->setNextUrl(route('customers.edit', $customers->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $customers = $this->customersRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $customers));

        page_title()->setTitle(trans('plugins/customers::customers.edit') . ' "' . $customers->name . '"');

        return $formBuilder->create(CustomersForm::class, ['model' => $customers])->renderForm();
    }

    /**
     * @param int $id
     * @param CustomersRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, CustomersRequest $request, BaseHttpResponse $response)
    {
        $customers = $this->customersRepository->findOrFail($id);

        $customers->fill($request->except('password'));

        if ($request->input('is_change_password') == 1) {
            $customers->password = Hash::make($request->input('password'));
        }
        $customers->birthday = Carbon::parse($request->input('birthday'))->toDateString();
        $customers = $this->customersRepository->createOrUpdate($customers);

        event(new UpdatedContentEvent(CUSTOMERS_MODULE_SCREEN_NAME, $request, $customers));

        return $response
            ->setPreviousUrl(route('customers.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $customers = $this->customersRepository->findOrFail($id);

            $this->customersRepository->delete($customers);

            event(new DeletedContentEvent(CUSTOMERS_MODULE_SCREEN_NAME, $request, $customers));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $customers = $this->customersRepository->findOrFail($id);
            $this->customersRepository->delete($customers);
            event(new DeletedContentEvent(CUSTOMERS_MODULE_SCREEN_NAME, $request, $customers));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
