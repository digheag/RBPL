<?php

namespace App\Dto;

use Carbon\Carbon;

class UserDTO
{
    public string $id;
    public string $username;
    public string $fullname;
    public string $email;
    public string $telpNumber;
    public string $profile;
    public Carbon $updatedAt;
    public Carbon $createdAt;
}
