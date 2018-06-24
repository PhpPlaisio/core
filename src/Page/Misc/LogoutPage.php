<?php

namespace SetBased\Abc\Core\Page\Misc;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Abc\Page\CorePage;

/**
 * Page for logging off from the website.
 */
class LogoutPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_MISC_LOGOUT, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Logs the user out of the website. I.e. the current session is ended and the user is redirected to the home
   * page of the site.
   */
  public function echoPage(): void
  {
    Abc::$session->logout();

    HttpHeader::redirectSeeOther('/');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

