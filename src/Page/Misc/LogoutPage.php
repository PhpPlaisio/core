<?php

namespace SetBased\Abc\Core\Page\Misc;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Page\CorePage;
use SetBased\Abc\Response\Response;
use SetBased\Abc\Response\SeeOtherResponse;

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
  public function handleRequest(): Response
  {
    Abc::$session->logout();

    $this->response = new SeeOtherResponse('/');

    return $this->response;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

