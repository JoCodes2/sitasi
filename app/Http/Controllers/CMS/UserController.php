<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepositories;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $User;

    public function __construct(UserRepositories $User)
    {
        $this->User = $User;
    }
    public function getAllData()
    {
        return $this->User->getAllData();
    }
    public function createData(UserRequest $request)
    {
        return $this->User->createData($request);
    }
    public function getDataById($id)
    {
        return $this->User->getDataById($id);
    }

    public function updateData(UserRequest $request, $id)
    {
        return $this->User->updateData($id, $request);
    }

    public function deleteData($id)
    {
        return $this->User->deleteData($id);
    }
}
