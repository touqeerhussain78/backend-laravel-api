<?php

namespace App\Core;

use App\Core\Contracts\IBaseAppService;

class BaseAppService implements IBaseAppService
{
    /**
     * Hold the entity repository
     * @var EntityRepository $entityRepository
    */
    protected $entityRepository;

    /**
     * Hold the errors
     * @var mixed $errors
     *
    */
    protected $errors;

    /**
     * get all data against a particular entity/model
     * @param array $relations
     * @param string $order
     * @return mixed
    */
    public function getAll($relations = [], $order = 'asc')
    {
        return $this->entityRepository->with($relations)->orderBy('id', $order)->get();
    }

    /**
     * get a particular data by id
     * @param int $id
     * @param array $relations
     * @return mixed
    */
    public function getById($id, $relations = [])
    {
        return $this->entityRepository->with($relations)->find($id);
    }

    /**
     * set errors as a mutator
     * @param mixed $errs
     * @return mixed
    */

    public function setErrors($errs)
    {
        $this->errors = $errs;
    }

    /**
     * get errors as a mutator
     * @return mixed
    */
    public function getErrors()
    {
        return $this->errors;
    }
}
