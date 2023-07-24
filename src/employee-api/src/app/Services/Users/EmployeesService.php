<?php
declare(strict_types=1);

namespace App\Services\Users;

use App\Exceptions\XmlStorageException;
use App\Models\users\EmployeeXmlModel;
use App\Services\Xml\EmployeesXmlStorage;

class EmployeesService
{

    /**
     * @param EmployeesXmlStorage $employeesXmlStorage
     * @param EmployeesAttributesService $usersAttributesService
     */
    public function __construct(
        private readonly EmployeesXmlStorage        $employeesXmlStorage,
        private readonly EmployeesAttributesService $usersAttributesService
    )
    {
    }

    /**
     * @throws XmlStorageException
     */
    public function getEmployees(): array
    {
        return $this->employeesXmlStorage->getEmployees();
    }

    /**
     * @param array $data
     * @return EmployeeXmlModel
     * @throws XmlStorageException
     */
    public function createEmployee(array $data): EmployeeXmlModel
    {
        $attributes = $this->usersAttributesService->parseAttributes($data);
        $this->usersAttributesService->validateAttributes($attributes);
        $this->usersAttributesService->validateRequiredAttributes($attributes);

        return $this->employeesXmlStorage->saveEmployeeModel(time(), $attributes);
    }

    /**
     * @throws XmlStorageException
     */
    public function deleteEmployee(int $userId): void
    {
        [$index] = $this->employeesXmlStorage->getEmployee($userId);

        $this->employeesXmlStorage->deleteEmployee($index);
    }

    /**
     * @param int $userId
     * @param array $data
     * @return EmployeeXmlModel
     * @throws XmlStorageException
     */
    public function updateUser(int $userId, array $data): EmployeeXmlModel
    {
        $attributes = $this->usersAttributesService->parseAttributes($data);
        $this->usersAttributesService->validateAttributes($attributes);

        /** @var EmployeeXmlModel $user */
        [$index, $user] = $this->employeesXmlStorage->getEmployee($userId);

        $updateUser = $user->update($attributes);
        $this->employeesXmlStorage->saveData();

        return $updateUser;
    }
}
