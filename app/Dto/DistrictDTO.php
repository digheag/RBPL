<?php

namespace App\Dto;

use Carbon\Carbon;

class DistrictDTO
{
    public string $id;
    public string $postalCode;
    public string $name;
    public Carbon $updatedAt;
    public Carbon $createdAt;
}
