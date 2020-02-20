<?php

namespace App\Http\Controllers;

use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;


class APILoginController extends Controller
{
    use SendsPasswordResetEmails;

    private $user_repo;

    /**
     * Create a new AuthController instance.
     *
     * @param UserRepositoryInterface $user_repo
     */
    public function __construct(UserRepositoryInterface $user_repo)
    {
        $this->user_repo = $user_repo;

        $this->middleware('jwt.verify', ['except' => ['login', 'register', 'resetPassword']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials["email"] = strtolower($credentials["email"]);
//        $credentials["email"]=strtolower($credentials["email"]);
        if ($token = auth('api')->attempt($credentials)) {
            $user = auth('api')->user();
            // $user->roles()->makeHidden('pivot');
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                // 'expires_in' => auth('api')->factory()->getTTL(),
                'roles' => $user->getRoleNames()
            ]);
        }

        return response()->json(['message' => 'Wrong Username or password', 'errors' => $token], 400);
    }

    public function resetPassword(Request $request)
    {

    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company' => 'required',
            'abn' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'roles' => 'in:contractor,rep,reo_rep',
            'photo' => 'mimes:jpeg,png|max:5120',
        ],[
            'photo.max:5120'=>"The logo should be less than 5 MB."
        ]);

        try {
            if($validator->fails()){
                return response()->json(["error_msg"=>"Some error occured in registration","errors"=>$validator->errors()], 400);
            }
            $requests = $request->all();
            $requests["email"] = strtolower($requests["email"]);
            $user_details = $request->only('email', 'password', 'first_name', 'last_name', 'phone', 'abn', 'company', 'state', 'city', 'roles', 'title');
            if ($this->user_repo->save($user_details, $request->file("photo"))) {
                if ($token = auth('api')->attempt(["email" => $user_details["email"], "password" => $user_details["password"]])) {

                    $user = auth('api')->user();
                    return response()->json([
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => auth('api')->factory()->getTTL() * 60,
                        'roles' => $user->getRoleNames()
                    ]);
                }
                return response()->json(['message' => 'Unauthorized'], 400);
            }

        }
        catch (\Exception $e) {
            return response()->json([$e->getMessage()], 400);
        }

    }

    /**
     * Get the authenticated User
     *
     * @return JsonResponse
     */
    public function me()
    {
        $user = auth('api')->user();
        if ($user) {
            $user = $user->load('detail');
            $user->roles = auth('api')->user()->getRoleNames();
            return response()->json($user, 200)->header('Content-type', 'application/json');
        } else {
            return response()->json(array("msg" => "Error on Verifying User"), 200)->header('Content-type', 'application/json');
        }


    }

    /**register
     * Log the user out (Invalidate the token)
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
