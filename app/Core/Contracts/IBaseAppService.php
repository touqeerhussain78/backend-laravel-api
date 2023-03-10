<?php

namespace App\Core\Contracts;

interface IBaseAppService {
    public function getAll($relations = [], $order = null);

    public function getById($id, $relations = []);

    public function setErrors($errs);

    public function getErrors();
}
