<?php 

namespace App\Controller\Custom;
use App\Entity\User;

Class GetUserByUuid
{
    public function __invoke(User $data): User{
        dd($data);
        return $data;
    }
}