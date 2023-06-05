<?php

namespace Botble\Checkin\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Checkin\Http\Requests\CheckinRequest;
use Botble\Checkin\Repositories\Interfaces\CheckinInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Checkin\Tables\CheckinTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Checkin\Forms\CheckinForm;
use Botble\Base\Forms\FormBuilder;

class CheckinController extends BaseController
{
    /**
     * @var CheckinInterface
     */
    protected $checkinRepository;

    /**
     * @param CheckinInterface $checkinRepository
     */
    public function __construct(CheckinInterface $checkinRepository)
    {
        $this->checkinRepository = $checkinRepository;
    }

    /**
     * @param CheckinTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CheckinTable $table)
    {
        page_title()->setTitle(trans('plugins/checkin::checkin.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/checkin::checkin.create'));

        return $formBuilder->create(CheckinForm::class)->renderForm();
    }

    /**
     * @param CheckinRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(CheckinRequest $request, BaseHttpResponse $response)
    {
        $checkin = $this->checkinRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(CHECKIN_MODULE_SCREEN_NAME, $request, $checkin));

        return $response
            ->setPreviousUrl(route('checkin.index'))
            ->setNextUrl(route('checkin.edit', $checkin->id))
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
        $checkin = $this->checkinRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $checkin));

        page_title()->setTitle(trans('plugins/checkin::checkin.edit') . ' "' . $checkin->name . '"');

        return $formBuilder->create(CheckinForm::class, ['model' => $checkin])->renderForm();
    }

    /**
     * @param int $id
     * @param CheckinRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, CheckinRequest $request, BaseHttpResponse $response)
    {
        $checkin = $this->checkinRepository->findOrFail($id);

        $checkin->fill($request->input());

        $checkin = $this->checkinRepository->createOrUpdate($checkin);

        event(new UpdatedContentEvent(CHECKIN_MODULE_SCREEN_NAME, $request, $checkin));

        return $response
            ->setPreviousUrl(route('checkin.index'))
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
            $checkin = $this->checkinRepository->findOrFail($id);

            $this->checkinRepository->delete($checkin);

            event(new DeletedContentEvent(CHECKIN_MODULE_SCREEN_NAME, $request, $checkin));

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
            $checkin = $this->checkinRepository->findOrFail($id);
            $this->checkinRepository->delete($checkin);
            event(new DeletedContentEvent(CHECKIN_MODULE_SCREEN_NAME, $request, $checkin));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
