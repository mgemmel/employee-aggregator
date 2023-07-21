<?php
declare(strict_types=1);

namespace App\Services\Users;

use App\Exceptions\XmlStorageException;
use App\Models\users\UserXmlModel;
use App\Services\Xml\UsersXmlStorage;
use InvalidArgumentException;

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
        $attributes = $this->usersAttributesService->validateUserAttributes($data);
        $this->usersAttributesService->validateRequiredAttributes($attributes);

        return $this->usersXmlStorage->saveUserModel(time(), $attributes);
    }

    /**
     * @throws XmlStorageException
     */
    public function deleteUser(int $userId): void
    {
        $indexToDelete = null;
        foreach ($this->usersXmlStorage->getUsers() as $key => $user) {
            if ($user->id == $userId){
                $indexToDelete = $key;
                break;
            }
        }
		if (empty($indexToDelete)){
            throw new InvalidArgumentException('Invalid user id: '. $userId);
        }

        $this->usersXmlStorage->deleteUser($indexToDelete);
    }
}
