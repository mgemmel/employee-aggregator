<?php
declare(strict_types=1);

namespace App\Services\Users;

use App\Exceptions\XmlStorageException;
use App\Models\users\UserXmlModel;
use App\Services\Xml\UsersXmlStorage;

class UsersService
{

    /**
     * @param UsersXmlStorage $usersXmlStorage
     * @param UsersAttributesService $usersAttributesService
     */
    public function __construct(
        private readonly UsersXmlStorage        $usersXmlStorage,
        private readonly UsersAttributesService $usersAttributesService
    )
    {
    }

    /**
     * @throws XmlStorageException
     */
    public function getUsers(): array
    {
        return $this->usersXmlStorage->getUsers();
    }

    /**
     * @param array $data
     * @return UserXmlModel
     * @throws XmlStorageException
     */
    public function createUser(array $data): UserXmlModel
    {
        $attributes = $this->usersAttributesService->parseAttributes($data);
        $this->usersAttributesService->validateAttributes($attributes);
        $this->usersAttributesService->validateRequiredAttributes($attributes);

        return $this->usersXmlStorage->saveUserModel(time(), $attributes);
    }

    /**
     * @throws XmlStorageException
     */
    public function deleteUser(int $userId): void
    {
        [$index] = $this->usersXmlStorage->getUser($userId);

        $this->usersXmlStorage->deleteUser($index);
    }

    /**
     * @param int $userId
     * @param array $data
     * @return UserXmlModel
     * @throws XmlStorageException
     */
    public function updateUser(int $userId, array $data): UserXmlModel
    {
        $attributes = $this->usersAttributesService->parseAttributes($data);
        $this->usersAttributesService->validateAttributes($attributes);

        /** @var UserXmlModel $user */
        [$index, $user] = $this->usersXmlStorage->getUser($userId);

        $updateUser = $user->update($attributes);
        $this->usersXmlStorage->saveData();

        return $updateUser;
    }
}
