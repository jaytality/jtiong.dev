<?php

namespace spark\Models;

use \spark\Core\Model as Model;
use Illuminate\Database\Capsule\Manager as Capsule;

class UserModel extends Model
{
    /**
     * authenticate a user - false if not valid, true if valid
     *
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function authenticate($email, $password): bool
    {
        $user = Capsule::table('jtdev_users')
            ->where('email', '=', $email)
            ->get();

        if ($user->isEmpty()) {
            return false;
        } else {
            dd($user);
            if (password_verify($password, $user->password)) {
                $_SESSION['authenticated'] = true;
                $_SESSION['user']['id']    = $user->id;
                $_SESSION['user']['name']  = $user->name;
                $_SESSION['user']['email'] = $user->email;

                $updateUser = Capsule::table('jtdev_users')
                    ->where('id', $user->id)
                    ->update([
                        'lastonline' => time()
                    ]);

                return true;
            }
        }
    }
}

// end of file
