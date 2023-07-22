<?php
declare(strict_types=1);

namespace App\Services\Xml;

use App\Exceptions\XmlStorageException;
use App\Models\users\AttributeModel;
use App\Models\users\UserXmlModel;
use SimpleXMLElement;

class UsersXmlStorage extends XmlStorageService
{

    public function __construct()
    {
        parent::__construct($_SERVER['DOCUMENT_ROOT'] . '/../storage/users.xml');
    }

    /**
     * @return UserXmlModel[]
     * @throws XmlStorageException
     */
    public function getUsers(): array
    {
        $data = [];
        /** @var SimpleXMLElement $user */
        foreach (parent::getData() as $user) {
            $data[] = (new UserXmlModel($user));
        }
        return $data;
    }

    /**
     * @param int $id
     * @return array
     * @throws XmlStorageException
     */
    public function getUser(int $id): array
    {
        foreach ($this->getUsers() as $key => $user) {
            if ($user->id == $id){
                return [$key, $user];
            }
        }
        throw new XmlStorageException('Unable to find model');
    }

    /**
     * @throws XmlStorageException
     */
    public function saveUserModel(int $id, array $attributes): UserXmlModel
    {
        $user = parent::getData()->addChild('user');
        $user->addAttribute('id', (string)$id);
        /** @var AttributeModel $attribute */
        foreach ($attributes as $attribute) {
            $user->addChild($attribute->getName(), $attribute->getValue());
        }
        parent::saveData();

        return new UserXmlModel($user);
    }

    /**
     * @throws XmlStorageException
     */
    public function deleteUser(int $index): bool
    {
        if (isset($this->getData()->user[$index])) {
            unset($this->getData()->user[$index]);
            $this->saveData();
            return true;
        }

        return false;
    }
}
