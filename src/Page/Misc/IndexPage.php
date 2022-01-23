<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Misc;

use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Kernel\Nub;

/**
 * The home page.
 */
class IndexPage extends CoreCorePage
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
  protected function htmlTabContent(): ?string
  {
    return 'System content';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

