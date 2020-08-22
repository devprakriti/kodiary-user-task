<?php

namespace App\Repositories;

use App\User;

class UserRepository extends Repository
{
    public function find($id)
    {
        return User::find($id);
    }

    public function get()
    {
        return User::get();
    }
    public function save($data)
    {
        return User::create($data);
    }

    public function update($id, $data)
    {
        $User = User::find($id);

        return $User->update($data);
    }

    public function delete($id)
    {
        return User::destroy($id);
    }
}
