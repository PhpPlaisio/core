<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Misc;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Abc\Page\Page;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for logging off from the website.
 */
class LogoutPage extends Page
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    return self::putCgiVar('pag', C::PAG_ID_MISC_LOGOUT, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Logs the user out of the website. I.e. the current session is ended and the user is redirected to the home
   * page of the site.
   */
  public function echoPage()
  {
    $abc = Abc::getInstance();

    Abc::$DL->abcSessionLogout($abc->getSesId(), $_SERVER['REMOTE_ADDR']);

    // Unset session and CSRF cookies.
    setcookie('ses_session_token', false, false, '/', $abc->getCanonicalServerName(), true, true);
    setcookie('ses_csrf_token', false, false, '/', $abc->getCanonicalServerName(), true, false);

    session_start();
    session_destroy();

    HttpHeader::redirectSeeOther('/');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

