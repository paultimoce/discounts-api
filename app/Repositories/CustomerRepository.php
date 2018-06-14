<?php namespace App\Repositories;

use App\Customer;

class CustomerRepository
{
    /**
     * @param int $id
     * @return Customer|null - customer instance with given id or null if id does not exist
     */
    public function getCustomerById(int $id)
    {
        return Customer::find($id);
    }
}