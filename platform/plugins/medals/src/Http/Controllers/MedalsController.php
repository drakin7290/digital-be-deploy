<?php

namespace Botble\Medals\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Medals\Http\Requests\MedalsRequest;
use Botble\Medals\Repositories\Interfaces\MedalsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Medals\Tables\MedalsTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Medals\Forms\MedalsForm;
use Botble\Base\Forms\FormBuilder;

class MedalsController extends BaseController
{
    /**
     * @var MedalsInterface
     */
    protected $medalsRepository;

    /**
     * @param MedalsInterface $medalsRepository
     */
    public function __construct(MedalsInterface $medalsRepository)
    {
        $this->medalsRepository = $medalsRepository;
    }

    /**
     * @param MedalsTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(MedalsTable $table)
    {
        page_title()->setTitle(trans('plugins/medals::medals.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/medals::medals.create'));

        return $formBuilder->create(MedalsForm::class)->renderForm();
    }

    /**
     * @param MedalsRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(MedalsRequest $request, BaseHttpResponse $response)
    {
        $check = $request->input('icon_url');
        if ($check != "") {
            $data = $request->input('icon_url');
            $request->merge(['icon' => $data]);
        }
        $medals = $this->medalsRepository->createOrUpdate($request->input());
        event(new CreatedContentEvent(MEDALS_MODULE_SCREEN_NAME, $request, $medals));

        return $response
            ->setPreviousUrl(route('medals.index'))
            ->setNextUrl(route('medals.edit', $medals->id))
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
        $medals = $this->medalsRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $medals));

        page_title()->setTitle(trans('plugins/medals::medals.edit') . ' "' . $medals->name . '"');

        return $formBuilder->create(MedalsForm::class, ['model' => $medals])->renderForm();
    }

    /**
     * @param int $id
     * @param MedalsRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, MedalsRequest $request, BaseHttpResponse $response)
    {
        $medals = $this->medalsRepository->findOrFail($id);

        $medals->fill($request->input());

        $medals = $this->medalsRepository->createOrUpdate($medals);

        event(new UpdatedContentEvent(MEDALS_MODULE_SCREEN_NAME, $request, $medals));

        return $response
            ->setPreviousUrl(route('medals.index'))
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
            $medals = $this->medalsRepository->findOrFail($id);

            $this->medalsRepository->delete($medals);

            event(new DeletedContentEvent(MEDALS_MODULE_SCREEN_NAME, $request, $medals));

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
            $medals = $this->medalsRepository->findOrFail($id);
            $this->medalsRepository->delete($medals);
            event(new DeletedContentEvent(MEDALS_MODULE_SCREEN_NAME, $request, $medals));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
