<?php
namespace App;

use App\Models\User;
use App\Repositories\MysqlUsersRepository;

class Auth
{
    private static ?MysqlUsersRepository $repository = null;
    private static ?User $user = null;


    public static function user(?int $id):?string
    {
        if($id == null)
        {
            return null;
        }
        if(Auth::$repository == null)
        {
            Auth::$repository = new MysqlUsersRepository();
        }
        if(Auth::$user == null)
        {
           Auth::$user = Auth::$repository->getById($id);
        }
        return Auth::$user->name();

    }
}