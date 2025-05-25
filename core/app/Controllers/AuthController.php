<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Auth;
use App\Models\AuthModel;
use App\Models\UsersModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class AuthController extends BaseController
{
    public $data = [
        'site_title' => 'Login',
        'site_description' => 'Login to dashboard',
    ];
    private GoogleAuthenticator $authenticator;
    private Auth|AuthModel $auth;
    private \CodeIgniter\Validation\ValidationInterface $validation;
    private \App\Config\Auth|\CodeIgniter\Config\BaseConfig|null $configIonAuth;
    private \CodeIgniter\Session\Session $session;
    /**
     * @var mixed|string
     */
    private mixed $validationListTemplate;

    public function __construct()
    {
        $this->auth    = new Auth();
        $this->validation = \Config\Services::validation();
        helper(['form', 'url']);
        $this->configIonAuth = config('Auth');
        $this->session       = \Config\Services::session();

        if (! empty($this->configIonAuth->templates['errors']['list']))
        {
            $this->validationListTemplate = $this->configIonAuth->templates['errors']['list'];
        }
    }
    public function index()
    {
        return $this->login();
    }

    public function login()
    {
        if ($this->auth->loggedIn()) {
            if ($this->auth->isAdmin()) {
                return redirect()->to(route('admin.dashboard'));
            }
            //Redirect to profile because not admin
            //return redirect()->to(route('user.dashboard'));
            return redirect()->to(route('admin.dashboard'));
        }

        if ($this->request->getPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $remember = $this->request->getPost('remember') !== null;

            if ($this->auth->login($email, $password, $remember)) {
                if (session()->get('_2fa_required')) {
                    return redirect()->to(route('auth.2fa'));
                }
                return $this->_redirectUser();
            } else {
                //dd($this->auth->getErrors());
                return redirect()->back()->with('error', $this->auth->getErrors());
                //return redirect()->back()->with('error', "Wrong username or password");
            }
        }

        return $this->_renderPage('login');
    }

    public function twoFactor()
    {
        $this->data['site_title'] = "Verification";
        $secret = session()->get('_2fa_user')->two_factor_secret;
        $this->data['secret'] = $secret;
        if ($this->request->getPost()) {
            $otp = $this->request->getPost('otp');
            $this->authenticator = new GoogleAuthenticator();
            if ($this->authenticator->checkCode($secret, $otp)) {
                
                if ($this->auth->complete2FALogin()) {
                    return $this->_redirectUser();
                }
                return redirect()->back()->with('error', "Login failed");
            }

            return redirect()->back()->with('error', "OTP Verification failed");
        }

        return $this->_renderPage('2fa');
    }
    public function register()
    {
        return redirect()->back()->with('error', "Sign up is not allowed");

        $this->data['site_title'] = 'Sign Up';
        if ($this->request->getPost()) {
            
            $identity = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $email = $this->request->getPost('email');
            $fname = $this->request->getPost('fname');
            $lname = $this->request->getPost('lname');
            $phone = $this->request->getPost('phone');

            //Get referrer
            $usersModel = model(UsersModel::class);
            $ref = $this->request->getGet('ref');
            $referrer = $usersModel->where('alnum', $ref)->first();

            helper('text');

            $additional_data = [
                'first_name' => $fname,
                'last_name' => $lname,
                'phone' => $phone,
                'token' => random_string('alnum', 7),
                'alnum' => random_string('alnum', 10),
                'referrer' => $referrer ? $referrer->id : null,
            ];
            if ($this->auth->register($identity, $password, $email, $additional_data)) {
                return redirect()->to(route('auth.login'))->with('success', "Registration successful. Login to continue");
            } else {
                //return redirect()->back()->withInput()->with('error', "Registration failed. Kindly try again after some time");
                return redirect()->back()->withInput()->with('error', $this->auth->getErrors());
            }
        }

        return $this->_renderPage('register');
    }

    public function forgotPassword()
    {
        $this->data['site_title'] = "Recover Password";

        if($this->request->getPost()) {
            $this->validation->setRule('email', 'Email Address', 'required|valid_email');
            if ($this->validation->withRequest($this->request)->run()) {
                $identityColumn = $this->configIonAuth->identity;
                $identity = $this->auth->where($identityColumn, $this->request->getPost('email'))->users()->row();
                if (empty($identity)) {
                    if ($this->configIonAuth->identity !== 'email') {
                        $this->auth->setError('Auth.forgot_password_identity_not_found');
                    } else {
                        $this->auth->setError('Auth.forgot_password_email_not_found');
                    }

                    $this->session->setFlashdata('message', $this->auth->errors($this->validationListTemplate));

                    return redirect()->back()->withInput();
                }

                // run the forgotten password method to email an activation code to the user
                $forgotten = $this->auth->forgottenPassword($identity->{$this->configIonAuth->identity});

                if ($forgotten) {
                    // if there were no errors
                    $this->session->setFlashdata('message', $this->auth->messages());

                    if ($identity->two_factor == '1' && !empty($identity->two_factor_secret)) {
                        //Can use the Authenticator app
                        session()->set('_2fa_secret', $identity->two_factor_secret);
                        session()->set('_2fa_identity', $identity->{$this->configIonAuth->identity});
                        return redirect()->to(route('auth.app_otp'));
                    }
                    return redirect()->to(route('auth.login'));
                } else {
                    $this->session->setFlashdata('message', $this->auth->errors($this->validationListTemplate));
                }
                return redirect()->back();
            } else {
                $message = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
                $this->session->setFlashdata('message', $message);
            }

            return redirect()->back()->withInput();
        }

        return $this->_renderPage('forgot_password');
    }

    public function appOTP()
    {
        $secret = session()->get('_2fa_secret');
        $identity = session()->get('_2fa_identity');

        if (!$secret || !$identity) return redirect()->to(route('auth.login'));

        $this->data['site_title'] = "OTP Verification";

        if ($this->request->getPost()) {
            $otp = $this->request->getPost('otp');
            $this->authenticator = new GoogleAuthenticator();
            $this->validation->setRule('new_password', 'New Password', 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[confirm_password]');
            $this->validation->setRule('confirm_password', 'Confirm Password', 'required');

            if ($this->authenticator->checkCode($secret, $otp)) {
                if($this->validation->withRequest($this->request)->run()) {
                    if($this->auth->resetPassword($identity, $this->request->getPost('new_password'))) {
                        session()->remove('_2fa_secret');
                        session()->remove('_2fa_identity');

                        $this->session->setFlashdata('success', "Password reset was successful. Login to continue");

                        return redirect()->to(route('auth.login'));
                    } else {
                        return redirect()->back()->with('message', $this->auth->messages());
                    }
                } else {
                    return redirect()->back()->with('error', 'Passwords do not match');
                }
                return redirect()->back()->with('error', "OTP Verification failed");
            }

            return redirect()->back()->with('error', "OTP Verification failed");
        }

        return $this->_renderPage('app_otp');
    }

    public function resetPassword($code)
    {
        if (!$code) {
            throw PageNotFoundException::forPageNotFound();
        }
        $user = $this->auth->forgottenPasswordCheck($code);
        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        if($this->request->getPost()) {
            $this->validation->setRule('new_password', 'New Password', 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[confirm_password]');
            $this->validation->setRule('confirm_password', 'Confirm Password', 'required');
            if($this->validation->withRequest($this->request)->run()) {
                $identity = $user->{$this->configIonAuth->identity};
                if($user->id == $this->request->getPost('user_id')) {

                    if($this->auth->resetPassword($identity, $this->request->getPost('new_password'))) {

                        //TODO: Notify user of password change

                        return redirect()->to(route('auth.login'))->with('success', 'Password changed successfully.');
                    } else {
                        $this->session->setFlashdata('error', $this->auth->messages());

                        return redirect()->back();
                    }
                } else {
                    $this->auth->clearForgottenPasswordCode($identity);
                    $this->session->setFlashdata('error', 'SECURITY ERROR! Reset code does not match your profile');

                    return redirect()->back()->withInput();
                }
            } else {
                $this->session->setFlashdata('error', 'Passwords do not match');

                return redirect()->back()->withInput();
            }
        }

        $this->data['site_title'] = 'Reset Password';
        $this->data['user'] = $user;

        return $this->_renderPage('reset_password');
    }

    public function activate($id, $code)
    {
        $activation = false;

        if ($code) {
            $activation = $this->auth->activate($id, $code);
        } else if ($this->auth->isAdmin()) {
            $activation = $this->auth->activate($id);
        }

        if ($activation) {
            // redirect them to the auth page
            return redirect()->to(route('auth.login'))->with('success', "Activation successful. Login to continue");
        } else {
            // redirect them to the forgot password page
            return redirect()->to(route('auth.forgot_password'))->with('error', "Activation failed. Kindly reset password to activate account!");
        }
    }

    public function logout()
    {
        
        session()->remove('_USER_DATA');
        session()->remove('_USER_DATA_SAVE');
        session()->remove('_next_url');
        $this->auth->logout();

        return redirect()->to(route('auth.login'));
    }

    private function _redirectUser()
    {
        if ($toRedirect = session()->get('_next_url')) {
            return redirect()->to($toRedirect);
        }
        if ($this->auth->isAdmin()) {
            return redirect()->to(route('admin.dashboard'));
        }
        return redirect()->to(route('user.dashboard'));
    }

    public function _renderPage($view)
    {

        $this->data['_html_content'] = view('Auth/'.$view, $this->data);

        return view('Auth/layout', $this->data);
    }
}