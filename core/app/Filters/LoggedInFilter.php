<?php
namespace App\Filters;

use App\Libraries\Auth;
use App\Libraries\IonAuth;
use App\Models\UsersModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoggedInFilter implements FilterInterface
{
    /**
     * @var IonAuth
     */
    private $ionAuth;
    private $session;

    /**
     * @inheritDoc
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $this->session = \Config\Services::session();
        $this->ionAuth = new Auth();
        if(!$this->ionAuth->loggedIn()) {
            session()->set('_next_url', uri_string());
            $this->session->setFlashdata('error', "Please login to continue");
            //return redirect()->to(site_url('auth/login'));

            return redirect()->to(route('auth.login'));
        }
        //Check if active or admin
        $user = $this->ionAuth->user()->row();

        if ($user->active != '1' && !$this->ionAuth->isAdmin($user->id)) {
            $this->ionAuth->logout();
            $this->session->setFlashdata('error', "This account has been suspended");
            //return redirect()->to(site_url('auth/login'));

            return redirect()->to(route('auth.login'));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}