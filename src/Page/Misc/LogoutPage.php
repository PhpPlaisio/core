<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Misc;

use Plaisio\C;
use Plaisio\Kernel\Nub;
use Plaisio\Page\CorePage;
use Plaisio\Response\Response;
use Plaisio\Response\SeeOtherResponse;

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
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_MISC_LOGOUT, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Logs the user out of the website. I.e. the current session is ended and the user is redirected to the home
   * page of the site.
   */
  public function handleRequest(): Response
  {
    Nub::$nub->session->logout();

    $this->response = new SeeOtherResponse('/');

    return $this->response;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

