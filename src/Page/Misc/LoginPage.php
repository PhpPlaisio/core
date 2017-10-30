<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Misc;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Form\Control\ConstantControl;
use SetBased\Abc\Form\Control\PasswordControl;
use SetBased\Abc\Form\Control\SpanControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Form;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Abc\Helper\Password;
use SetBased\Abc\Page\CorePage;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for logging on the website.
 */
class LoginPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The login form shown on this page.
   *
   * @var Form
   */
  protected $form;

  /**
   * If set the URI to which the user agent must redirected after a successful login.
   *
   * @var string
   */
  private $redirect;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->redirect = self::getCgiUrl('redirect', '/');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param string|null $redirect A URL to which the user agent must be redirected after a successful login.
   *
   * @return string
   */
  public static function getUrl($redirect = null)
  {
    $url = self::putCgiId('pag', C::PAG_ID_MISC_LOGIN, 'pag');
    $url .= self::putCgiUrl('redirect', $redirect);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function echoPage()
  {
    // Buffer for actual contents.
    ob_start();

    $this->showPageContent();

    $contents = ob_get_contents();
    if (ob_get_level()>0) ob_end_clean();

    // Buffer for header.
    ob_start();

    $this->showPageHeader();

    echo '<div id="container">';
    echo $contents;
    echo '</div>';

    $this->showPageTrailer();

    if (ob_get_level()>0) ob_end_flush();
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function showPageContent()
  {
    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $this->form = new CoreForm('', false);

    // Input for user name.
    $input = new TextControl('usr_name');
    $input->setAttrMaxLength(C::LEN_USR_NAME);
    $this->form->addFormControl($input, 'Naam', true);

    // Input for password.
    $input = new PasswordControl('usr_password');
    $input->setAttrSize(C::LEN_USR_NAME);
    $input->setAttrMaxLength(C::LEN_PASSWORD);
    $this->form->addFormControl($input, 'Wachtwoord', true);

    $this->form->addSubmitButton(C::WRD_ID_BUTTON_LOGIN, 'handleForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the form shown on this page.
   */
  private function echoForm()
  {
    echo $this->form->generate();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm()
  {
    $method = $this->form->execute();
    switch ($method)
    {
      case 'handleForm':
        $this->handleForm();
        break;

      default:
        $this->form->defaultHandler($method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm()
  {
    $login_succeeded = $this->login();

    if (!$login_succeeded) $this->echoForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if login is successful. Otherwise returns false.
   *
   * @return bool
   */
  private function login()
  {
    $values = $this->form->getValues();

    // Phase 1: Validate the user is allowed to login (except for password validation).
    $response = Abc::$DL->abcSessionLogin1($this->cmpId, $values['usr_name']);
    $lgr_id   = $response['lgr_id'];

    if ($lgr_id==C::LGR_ID_GRANTED)
    {
      // Phase 2: So far, user is allowed to login. Last validation: verify password.
      $match = Password::passwordVerify($values['usr_password'], $response['usr_password_hash']);
      if ($match!==true) $lgr_id = C::LGR_ID_WRONG_PASSWORD;
    }

    if ($lgr_id==C::LGR_ID_GRANTED)
    {
      // The user has logged on successfully.
      Abc::$session->login($response['usr_id']);

      // First verify that the hash is sill up to date.
      if (Password::passwordNeedsRehash($response['usr_password_hash']))
      {
        $hash = Password::passwordHash($values['usr_password']);
        Abc::$DL->abcUserPasswordUpdateHash($this->cmpId, $response['usr_id'], $hash);
      }

      HttpHeader::redirectSeeOther($this->redirect);

      return true;
    }
    else
    {
      // The user has not logged on successfully.
      return false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function showPageHeader()
  {
    echo '<!DOCTYPE html>';
    echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl" dir="ltr">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>';

    Abc::$assets->echoCascadingStyleSheets();

    echo '</head><body>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function showPageTrailer()
  {
    echo '</body></html>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
