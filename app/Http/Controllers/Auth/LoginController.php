<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the login credentials and determine if username is NIM or NIDN
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $username = $request->input('username');

        return [
            $this->usernameType($username) => $username,
            'password' => $request->input('password'),
        ];
    }

    /**
     * Determine if the username is NIM or NIDN
     *
     * @param string $username
     * @return string
     */

        protected function usernameType($username)
        {
            if (is_numeric($username)) {
                return 'nim';
            } elseif (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                return 'nidn'; // Anda bisa menyesuaikan ini jika 'nidn' harus berupa email
            } else {
                return 'nama_lengkap';
            }
        }
        

    /**
     * Get the username property to be used for login
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Send the response after the user failed to authenticate
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $fields = $this->username();
        return redirect()->back()
            ->withInput($request->only($fields, 'remember'))
            ->withErrors([
                $fields => [trans('auth.failed')],
            ]);
    }
}
