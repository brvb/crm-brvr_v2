<?php

namespace App\Interfaces\Tenant\Files;

use App\Models\Tenant\Files;
use Illuminate\Support\Collection;

interface FilesInterface
{
    public function addToDatabase($files,$customer_id,$anwser): Collection;

    public function removeFileFromCustomer($id): int;

    //public function getFilesFromCustomer($customer_id): int;
    

}
