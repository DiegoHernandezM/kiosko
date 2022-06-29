<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Log;

class AuthController extends Controller
{
    protected $mUser;
    public function __construct()
    {
        $this->mUser = new User();
    }

    public function login(Request $request)
    {
        try {
            $user = $this->mUser->where('email', $request->username)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $data = [
                        'access_token' => $token,
                        'accessToken' => $token,
                        'user' => [
                            'displayName' => $user->name,
                            'permissions' => $user->permissions
                        ]
                    ];
                    return ApiResponses::okObject($data);
                } else {
                    return ApiResponses::badRequest("Contraseña incorrecta");
                }
            } else {
                return ApiResponses::notFound('El usuario no existe');
            }
        } catch (\Exception $e) {
            Log::error('Error en '.__METHOD__.' línea '.$e->getLine().':'.$e->getMessage());
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function verifyemail(Request $request)
    {
        $token = $request->token;
        if ($token) {
            $user = $this->mUser->where('device_key', $token)
                            ->whereNull('email_verified_at')
                            ->first();
        }
        if (!empty($user)) {
            $user->email_verified_at = new \DateTime();
            $user->save();
        } else {
            $response = 'Bad Request.';
            return response($response, 400);
        }
        $response = 'You have succesfully verified your email!';
        return response($response, 200);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = 'You have been succesfully logged out!';
        return response($response, 200);
    }

    public function resetPassword(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|email|exists:users'
            ]);
            if ($validate->fails()) {
                return ApiResponses::badRequest('Verifique su correo');
            }
            $token = Str::random(64);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::to($request->email)->send(new ResetPasswordMail($token));
            return ApiResponses::ok();
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function submitResetPassword(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'password' => 'required|string|min:6',
            ]);

            if ($validate->fails()) {
                return ApiResponses::badRequest(implode(",",$validate->messages()->all()));
            }

            $updatePassword = DB::table('password_resets')
                ->where(['token' => $request->token])
                ->first();

            if(!$updatePassword){
                return ApiResponses::notFound('No se encontraron registro de solicitud');
            }

            User::where('email', $updatePassword->email)->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email'=> $updatePassword->email])->delete();
            return ApiResponses::ok('Contraseña actualizada');
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
