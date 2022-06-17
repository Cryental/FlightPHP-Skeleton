<?php

namespace Ntric\Backend\Databases;

use SleekDB\Store;

class Database
{
    protected $database;

    public function Create(array $user): ?array
    {
        try {
            return $this->database->insert($user);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function FindById(int $id): ?array
    {
        try {
            return $this->database->findById($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function FindOneBy(string $columnName, string $value): ?array
    {
        try {
            $find = $this->database->findOneBy([$columnName, '=', $value]);

            if (!$find) {
                return null;
            }

            return $find;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function FindOneByQuery(array $query): ?array
    {
        try {
            $find = $this->database->findOneBy($query);

            if (!$find) {
                return null;
            }

            return $find;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function FindBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        try {
            $find = $this->database->findBy($criteria, $orderBy, $limit, $offset);

            if (!$find) {
                return null;
            }

            return $find;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function FindAll(): ?array
    {
        try {
            return $this->database->findAll();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function Delete(int $id): ?bool
    {
        try {
            $toBeDeletedToken = $this->database->deleteById($id);

            if ($toBeDeletedToken == null) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function UpdateById(int $id, array $updatable): ?array
    {
        try {
            $update = $this->database->updateById($id, $updatable);

            if (!$update) {
                return null;
            }

            return $update;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function UpdateOrInsert(array $data): ?array
    {
        try {
            $updateOrInsert = $this->database->updateOrInsert($data);

            if (!$updateOrInsert) {
                return null;
            }

            return $updateOrInsert;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function DeleteById(int $id): ?bool
    {
        try {
            $toBeDeletedToken = $this->database->deleteById($id);

            if ($toBeDeletedToken == null) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function DeleteBy(string $columnName, string $value): ?bool
    {
        try {
            $toBeDeletedToken = $this->database->deleteBy([$columnName, '=', $value]);

            if ($toBeDeletedToken == null) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function DeleteByQuery(array $query): ?bool
    {
        try {
            $toBeDeletedToken = $this->database->deleteBy($query);

            if ($toBeDeletedToken == null) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function DeleteStore(): ?bool
    {
        try {
            return $this->database->deleteStore();
        } catch (\Exception $e) {
            return null;
        }
    }
}