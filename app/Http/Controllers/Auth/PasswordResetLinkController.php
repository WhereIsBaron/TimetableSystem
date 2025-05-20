<?php

<<<<<<< HEAD
//namespace App\Http\Controllers\Auth;

//use App\Http\Controllers\Controller;
//use Illuminate\Http\RedirectResponse;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Password;
//use Illuminate\View\View;

//class PasswordResetLinkController extends Controller
//{
    /**
     * Display the password reset link request view.
     */
//    public function create(): View
//    {
//        return view('auth.forgot-password');
//    }
=======
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }
>>>>>>> d36a851 (Install Breeze)

    /**
     * Handle an incoming password reset link request.
     *
<<<<<<< HEAD
//     * @throws \Illuminate\Validation\ValidationException
     */
//    public function store(Request $request): RedirectResponse
//    {
//        $request->validate([
 //           'email' => ['required', 'email'],
//        ]);
=======
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
>>>>>>> d36a851 (Install Breeze)

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
<<<<<<< HEAD
//        $status = Password::sendResetLink(
//            $request->only('email')
  //      );

    //    return $status == Password::RESET_LINK_SENT
      //              ? back()->with('status', __($status))
        //            : back()->withInput($request->only('email'))
          //              ->withErrors(['email' => __($status)]);
//    }
//}
=======
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
>>>>>>> d36a851 (Install Breeze)
