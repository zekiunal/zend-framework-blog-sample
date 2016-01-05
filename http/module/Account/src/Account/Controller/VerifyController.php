<?php
namespace Account\Controller;

use News\Model\User;
use News\Model\UserTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     Account\Controller
 * @name        VerifyController
 * @version     0.1
 * @created     2015/12/20 16:46
 */
class VerifyController extends AbstractActionController
{
    /**
     * @var UserTable
     */
    protected $user_table;

    public function indexAction()
    {
        $user_id = $this->zfcUserAuthentication()->getIdentity()->getId();

        /**
         * @param User $user
         */
        $user = $this->getUserTable()->getUser($user_id);

        $resend = (int)$this->params()->fromQuery('resend', 0);

        if(($resend && $user->state == 0) or !$user->token) {
            $user->token = md5($user->updated_at . $user_id . $user->created_at);
            $this->getUserTable()->saveUser($user);
            $this->sendVerificationMail($user);
        }

        return new ViewModel(array('email' => $user->email));
    }

    public function confirmAction()
    {
        $token = $this->params()->fromRoute('token');

        try {
            /**
             * @param User $user
             */
            $user = $this->getUserTable()->getByToken($token);
            $user->state = 1;
            $this->getUserTable()->saveUser($user);
            $this->redirect()->toRoute('account');
        } catch (\Exception $e) {
            return new ViewModel(array('error' => 'Email confirmation is not successful.'));
        }
    }

    /**
     * @return \News\Model\UserTable
     */
    public function getUserTable()
    {
        if (!$this->user_table) {
            $this->user_table = $this->getServiceLocator()->get('News\Model\UserTable');
        }
        return $this->user_table;
    }

    /**
     * @param User $user
     *
     * @throws \phpmailerException
     */
    protected function sendVerificationMail($user)
    {
        $mail = new \PHPMailer();
        $mail->isSendmail();
        $mail->setFrom('noreply@blog.com', 'Sample Blog News');
        $mail->addAddress($user->email, $user->email);
        $mail->Subject = 'Confirm your Sample Blog News account';

        $body = "You need to confirm your email address " . $user->email . " in order to activate your Sample Blog News
            account. <br/> Activating your account will give you more benefits and better control.<br />
            Please click the link below to confirm your account <br />
            http://" . $this->getRequest()->getUri()->getHost() . "/account/confirm/" . $user->token;

        $mail->msgHTML($body);
        $mail->send();
    }
}
