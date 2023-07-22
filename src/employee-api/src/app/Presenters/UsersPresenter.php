<?php
declare(strict_types=1);

namespace App\Presenters;

use App\Services\Users\UsersService;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\Http\IRequest;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class UsersPresenter extends Presenter
{

    /**
     * @param UsersService $usersRepository
     */
    public function __construct(private UsersService $usersRepository)
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
		$users = $this->usersRepository->getUsers();

        $this->sendJson($users);
    }

    /**
     * @throws AbortException
     * @throws Exception
     */
    public function actionCreateUser(): void
    {
        $request = $this->validateRequest('POST');
		$user = $this->usersRepository->createUser($this->getBody($request));

        $this->sendJson($user);
    }

    /**
     * @throws AbortException
     * @throws Exception
     */
    public function actionDeleteUser(int $id): void
    {
        $request = $this->validateRequest('DELETE');
		$this->usersRepository->deleteUser($id);

        $this->sendJson([]);
    }

    /**
     * @throws AbortException
     * @throws Exception
     */
    public function actionUpdateUser(int $id): void
    {
        $request = $this->validateRequest('PUT');
		$user = $this->usersRepository->updateUser($id, $this->getBody($request));

        $this->sendJson($user);
    }
}
