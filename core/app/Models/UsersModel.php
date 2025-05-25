<?php


namespace App\Models;


class UsersModel extends \CodeIgniter\Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\UserEntity';

    protected $allowedFields = [
        'username', 'email', 'first_name', 'middle_name', 'last_name', 'phone', 'active', 'token', 'alnum', 'apply_balance',
        'main_balance', 'demo_balance', 'avatar', 'two_factor', 'kyc', 'kyc_files', 'currency', 'two_factor_secret',
        'referrer'
    ];
}