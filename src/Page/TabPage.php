<?php
declare(strict_types=1);

namespace Plaisio\Core\Page;

use Plaisio\C;
use Plaisio\Helper\Html;
use Plaisio\Kernel\Nub;
use Plaisio\Page\CorePage;
use Plaisio\Response\BaseResponse;
use Plaisio\Response\Response;

/**
 * Abstract parent page for all core pages of ABC.
 */
abstract class TabPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set disabled tabs (i.e. tabs in $tabs field 'url' is empty) are shown. Otherwise, disabled tabs are hidden.
   */
  protected $showDisabledTabs = true;

  /**
   * If set the tab content is shown.
   *
   * @var bool
   */
  protected $showTabContent = false;

  /**
   * The tabs of the core page.
   *
   * @var array[]
   */
  protected $tabs;

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    Nub::$nub->assets->cssAppendSource('reset.css');
    Nub::$nub->assets->cssAppendSource('ui-lightness/jquery-ui.css');
    Nub::$nub->assets->cssAppendSource('style.css');
    Nub::$nub->assets->cssAppendSource('grid.css');
    Nub::$nub->assets->cssAppendSource('grid-large.css');
    Nub::$nub->assets->cssAppendSource('grid-small.css');
    Nub::$nub->assets->cssAppendSource('layout.css');
    Nub::$nub->assets->cssAppendSource('main-menu-large.css');
    Nub::$nub->assets->cssAppendSource('main-menu-small.css');
    Nub::$nub->assets->cssAppendSource('main-menu-icon-large.css');
    Nub::$nub->assets->cssAppendSource('main-menu-icon-small.css');
    Nub::$nub->assets->cssAppendSource('secondary-menu.css');
    Nub::$nub->assets->cssAppendSource('icon-bar.css');
    Nub::$nub->assets->cssAppendSource('detail-table.css');
    Nub::$nub->assets->cssAppendSource('overview-table.css');
    Nub::$nub->assets->cssAppendSource('overview-table-content-types.css');
    Nub::$nub->assets->cssAppendSource('overview-table-menu.css');
    Nub::$nub->assets->cssAppendSource('overview-table-large.css');
    Nub::$nub->assets->cssAppendSource('overview-table-large-content-types.css');
    Nub::$nub->assets->cssAppendSource('overview-table-large-filter.css');
    Nub::$nub->assets->cssAppendSource('overview-table-large-sort.css');
    Nub::$nub->assets->cssAppendSource('overview-table-small.css');
    Nub::$nub->assets->cssAppendSource('overview-table-small-content-types.css');
    Nub::$nub->assets->cssAppendSource('overview-table-small-filter.css');
    Nub::$nub->assets->cssAppendSource('input-table.css');
    Nub::$nub->assets->cssAppendSource('input-table-small.css');
    Nub::$nub->assets->cssAppendSource('button.css');
    Nub::$nub->assets->cssAppendSource('icons.css');
    Nub::$nub->assets->cssAppendSource('icons-small.css');
    Nub::$nub->assets->cssAppendSource('icons-medium.css');

    Nub::$nub->assets->jsAdmSetPageSpecificMain(__CLASS__);

    Nub::$nub->assets->setPageTitle(Nub::$nub->pageInfo['ptb_title'].
                                    ' - '.
                                    Nub::$nub->assets->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the actual page content, i.e. the inner HTML of the body tag.
   */
  public function handleRequest(): Response
  {
    // Buffer for actual contents.
    ob_start();

    $this->echoPageContent();

    $contents = ob_get_clean();

    if ($this->response!==null)
    {
      return $this->response;
    }

    // Buffer for header.
    ob_start();

    $this->echoPageLeader();
    echo '<div class="grid-container">';
    echo '<div class="grid-main">';
    echo $contents;
    echo '</div>';
    $this->echoAdminMenu();
    echo '</div>';
    $this->echoPageTrailer();

    $contents = ob_get_clean();

    $this->response = new BaseResponse($contents);
    $this->response->headers->set('Content-Type', 'text/html; charset='.Html::$encoding);

    return $this->response;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Can be overridden to echo a summary of the entity shown of the current page.
   */
  protected function echoDashboard(): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the main content of the page, e.g. the dashboard, the tabs (secondary menu), and tab content.
   */
  protected function echoPageContent(): void
  {
    $this->echoDashboard();

    $this->showIconBar();

    echo '<nav class="secondary-menu clearfix">';
    $this->echoTabs();
    echo '</nav>';

    echo '<div class="layout-content">';
    $this->echoTabContent();
    echo '</div>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the XHTML document leader, i.e. the start html tag, the head element, and start body tag.
   */
  protected function echoPageLeader(): void
  {
    echo '<!DOCTYPE html>';
    echo Html::generateTag('html',
                           ['xmlns'    => 'http://www.w3.org/1999/xhtml',
                            'xml:lang' => Nub::$nub->babel->getLang(),
                            'lang'     => Nub::$nub->babel->getLang()]);
    echo '<head>';

    // Echo the meta tags.
    Nub::$nub->assets->echoMetaTags();

    // Echo the title of the XHTML document.
    Nub::$nub->assets->echoPageTitle();

    // Echo style sheets (if any).
    Nub::$nub->assets->echoCascadingStyleSheets();

    echo '</head><body>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the XHTML document trailer, i.e. the end body and html tags, including the JavaScript code that will be
   * executed using RequireJS.
   */
  protected function echoPageTrailer(): void
  {
    Nub::$nub->assets->echoJavaScript();

    echo '</body></html>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the actual page content.
   *
   * @return void
   */
  abstract protected function echoTabContent(): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the tabs of this page, a.k.a. the secondary menu.
   */
  protected function echoTabs(): void
  {
    $pag_id_org = Nub::$nub->pageInfo['pag_id_org'];

    $this->getPageTabs();

    echo '<ul>';
    foreach ($this->tabs as &$tab)
    {
      if (isset($tab['url']))
      {
        $class = ($tab['pag_id']==$pag_id_org) ? $class = "class='selected'" : '';
        echo '<li><a href="', $tab['url'], '" ', $class, '>', Html::txt2Html($tab['tab_name']), '</a></li>';
      }
      else
      {
        if ($this->showDisabledTabs) echo '<li><a class="disabled">', Html::txt2Html($tab['tab_name']), '</a></li>';
      }

      if ($tab['pag_id']==$pag_id_org && $tab['url']) $this->showTabContent = true;
    }
    echo '</ul>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Retrieves the tabs of page group of the current page.
   */
  protected function getPageTabs(): void
  {
    $this->tabs = Nub::$nub->DL->abcAuthGetPageTabs($this->cmpId,
                                                    Nub::$nub->pageInfo['ptb_id'],
                                                    $this->proId,
                                                    $this->lanId);
    foreach ($this->tabs as &$tab)
    {
      $tab['url'] = $this->getTabUrl($tab['pag_id']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of a tab of the page group of current page.
   *
   * @param int $pagId The ID of the page of the tab.
   *
   * @return string
   */
  protected function getTabUrl(int $pagId): ?string
  {
    return Nub::$nub->cgi->putId('pag', $pagId, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function showIconBar(): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the main menu.
   */
  private function echoAdminMenu(): void
  {
    echo '<div class="grid-main-menu">';

    echo '<div class="main-menu-icon">';
    echo '<div class="menu-bar1"></div>';
    echo '<div class="menu-bar2"></div>';
    echo '<div class="menu-bar3"></div>';
    echo '</div>';

    echo Nub::$nub->menu->menu(C::MNU_ID_ADMIN);
    echo '</div>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
