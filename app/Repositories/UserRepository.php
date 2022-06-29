<?php

namespace App\Repositories;

use App\Mail\MailAmazonSes;
use App\Models\Associate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository
{
    protected $mUser;
    protected $mAssociate;
    protected $rMails;

    public function __construct()
    {
        $this->mUser = new User();
        $this->mAssociate = new Associate();
        $this->rMails = new MailsRepository();
    }

    public function getAll()
    {
        return $this->mUser->all();
    }

    public function findUser($id)
    {
        return $this->mUser->with('permissions')->find($id);
    }

    public function showUser() {
        $user = Auth::user();
        $user->displayName = $user->name;
        return [
            'user' => $user,
            'permissions' => $user->permissions
        ];
    }

    public function createUser($request)
    {
        $request['device_key'] = Str::random(40);
        $user = $this->mUser->create($request);
        $request = (object)$request;
        $this->setPermission($user, $request->authority['permissions']);
        $body = [
            $user,
            $request->password,
            $request->device_key
        ];
        $this->rMails->sendWelcome($user, $body);
        return $user;
    }

    public function updateUser($id, $request)
    {
        $user = $this->findUser($id);
        if ($user) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->update();
            $this->setPermission($user, $request->authority['permissions']);

            return $user;
        }
    }

    public function setPermission($user, $permissions)
    {
        if (count($permissions) > 0) {
            $delete = DB::table('model_has_permissions')->where('model_id', '=', $user->id);
            $delete->delete();
            foreach ($permissions as $permission) {
                $user->givePermissionTo(Permission::where('name', $permission)
                    ->where('guard_name', 'web')
                    ->first());
            }
        }
        return true;
    }

    public function destroyUser($id)
    {
        $user = $this->findUser($id);
        $associate = $this->mAssociate->where('user_id', $user->id)->first();
        if (!$associate) {
            $user->delete();
        } else {
            $this->setPermission($user, ['associate']);
        }
    }

    public function restoreUser($id)
    {
        $this->mUser->withTrashed()->find($id)->restore();
    }

    public function getUsersByPermission($permission)
    {
        return $this->mUser->permission($permission)->withTrashed()->get();
    }

    public function changePasswordUser($oRequest)
    {
        try {
            if (!(Hash::check($oRequest->old_password, Auth::user()->password))) {
                return response()->json(['message' => 'La contraseña proporcionada no coincide con los registros'], 400);
            }
            if (strcmp($oRequest->old_password, $oRequest->new_password) == 0) {
                return response()->json(['message' => 'La nueva contraseña tiene que ser diferente a la anterior'], 400);
            }
            $oValidator = Validator::make($oRequest->all(), [
                'old_password' => 'required',
                'new_password' => 'required|string|min:6|confirmed',
            ]);
            if ($oValidator->fails()) {
                return response()->json(['message' => json_encode($oValidator->errors())], 400);
            }

            $user = Auth::user();
            $user->password = $oRequest->new_password;
            $user->save();

            return response()->json($user, 200);

        } catch (\Exception $e) {
            Log::error('Error en '.__METHOD__.' línea '.$e->getLine().':'.$e->getMessage());
            return  response()->json([
                'code' => 500,
                'type' => 'User',
                'message' => 'Error al actualizar el recurso: '.$e->getMessage(),
            ]);
        }
    }
}
