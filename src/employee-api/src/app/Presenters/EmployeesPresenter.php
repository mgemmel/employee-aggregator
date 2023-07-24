<?php
declare(strict_types=1);

namespace App\Presenters;

use App\Services\Users\EmployeesAttributesService;
use App\Services\Users\EmployeesService;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\Http\IRequest;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class EmployeesPresenter extends Presenter
{

    /**
     * @param EmployeesService $usersService
     * @param EmployeesAttributesService $usersAttributesService
     */
    public function __construct(
        private readonly EmployeesService           $usersService,
        private readonly EmployeesAttributesService $usersAttributesService
    )
    {
        parent::__construct();
    }

    /**
     * @throws JsonException
     */
    private function validateRequest(string $method): IRequest
    {
        $request = $this->getHttpRequest();
        if (!$request->isMethod($method)) {
            throw new \HttpRequestMethodException();
        }
        return $request;
    }

    /**
     * @param IRequest $request
     * @return array
     * @throws JsonException
     */
    private function getBody(IRequest $request): array
    {
        return Json::decode($request->getRawBody(), true);
    }

    /**
     * @throws AbortException
     * @throws Exception
     */
    public function actionIndex(): void
    {
        $this->validateRequest('GET');
		$users = $this->usersService->getEmployees();

        $this->sendJson($users);
    }

    /**
     * @throws AbortException
     * @throws Exception
     */
    public function actionCreateEmployee(): void
    {
        $request = $this->validateRequest('POST');
		$user = $this->usersService->createEmployee($this->getBody($request));

        $this->sendJson($user);
    }

    /**
     * @throws AbortException
     * @throws Exception
     */
    public function actionDeleteEmployee(int $id): void
    {
        $request = $this->validateRequest('DELETE');
		$this->usersService->deleteEmployee($id);

        $this->sendJson([]);
    }

    /**
     * @throws AbortException
     * @throws Exception
     */
    public function actionUpdateEmployee(int $id): void
    {
        $request = $this->validateRequest('PUT');
		$user = $this->usersService->updateUser($id, $this->getBody($request));

        $this->sendJson($user);
    }

    /**
     * @throws AbortException
     * @throws Exception
     */
    public function actionGetAttributesConfig(): void
    {
        $this->validateRequest('GET');
		$config = $this->usersAttributesService->getAttributesConfig();

        $this->sendJson($config);
    }
}
