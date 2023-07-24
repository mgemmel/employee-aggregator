<?php
declare(strict_types=1);

namespace App\Services\Xml;

use App\Exceptions\XmlStorageException;
use App\Models\users\AttributeModel;
use App\Models\users\EmployeeXmlModel;
use SimpleXMLElement;

class EmployeesXmlStorage extends XmlStorageService
{

    public function __construct()
    {
        parent::__construct($_SERVER['DOCUMENT_ROOT'] . '/../storage/employees.xml');
    }

    /**
     * @return EmployeeXmlModel[]
     * @throws XmlStorageException
     */
    public function getEmployees(): array
    {
        $data = [];
        /** @var SimpleXMLElement $employee */
        foreach (parent::getData() as $employee) {
            $data[] = (new EmployeeXmlModel($employee));
        }
        return $data;
    }

    /**
     * @param int $id
     * @return array
     * @throws XmlStorageException
     */
    public function getEmployee(int $id): array
    {
        foreach ($this->getEmployees() as $key => $employee) {
            if ($employee->id == $id) {
                return [$key, $employee];
            }
        }
        throw new XmlStorageException('Unable to find model');
    }

    /**
     * @throws XmlStorageException
     */
    public function saveEmployeeModel(int $id, array $attributes): EmployeeXmlModel
    {
        $user = parent::createXmlElement('employee', [new AttributeModel('id', (string)$id)]);
        $userXmlModel = new EmployeeXmlModel($user);
        $userXmlModel->update($attributes);
        $this->saveData();

        return $userXmlModel;
    }

    /**
     * @throws XmlStorageException
     */
    public function deleteEmployee(int $index): bool
    {
        if (isset($this->getData()->employee[$index])) {
            unset($this->getData()->employee[$index]);
            $this->saveData();
            return true;
        }

        return false;
    }
}
