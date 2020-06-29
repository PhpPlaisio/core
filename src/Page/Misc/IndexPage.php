<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Misc;

use Plaisio\Core\Page\TabPage;
use Plaisio\Kernel\Nub;

/**
 * The home page.
 */
class IndexPage extends TabPage
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
   * @inheritdoc
   */
  public function echoPage(): void
  {
    if (Nub::$nub->session->isAnonymous())
    {
      $this->showAnonymousPage();
    }
    else
    {
      parent::echoPage();
    }
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
  /**
   * Shows the page content for an anonymous in, i.e. a user that is not logged in.
   */
  private function showAnonymousPage(): void
  {
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<!DOCTYPE html PUBLIC "-//W3C//Dtd XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/Dtd/xhtml1-strict.dtd">';
    echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>';
    echo '<title>SetBased Framework</title>';
    echo '<style type="text/css" media="all">';
    echo "  @import url('/css/reset.css');";
    echo "  @import url('/css/style.css');";
    echo '</style>';
    echo '</head>';
    echo '<body>';
    echo '<div id="splash">';

    echo '<p>Put some nice words about this abc (e.g. manual, references, and that page must be replaced with a ';
    echo 'child class.</p>';

    $url = LoginPage::getUrl();
    echo "<p>Click <a href='".$url."'>here</a> to login.</p>";

    echo '</div>';
    echo '</body>';
    echo '</html>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

