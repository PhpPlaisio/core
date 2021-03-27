<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Misc;

use Plaisio\Core\Page\PlaisioCorePage;
use Plaisio\Kernel\Nub;

/**
 * The home page.
 */
class IndexPage extends PlaisioCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL of this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Nub::$nub->cgi->putLeader();

    return ($url==='') ? '/' : $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows the page content for an identified user, i.e. a user that has logged in.
   */
  protected function echoTabContent(): void
  {
    echo 'System content';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

