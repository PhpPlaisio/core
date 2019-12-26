<?php
declare(strict_types=1);

namespace Plaisio\Core\Page;

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

    Nub::$assets->cssAppendSource('reset.css');
    Nub::$assets->cssAppendSource('ui-lightness/jquery-ui.css');
    Nub::$assets->cssAppendSource('style.css');
    Nub::$assets->cssAppendSource('grid.css');
    Nub::$assets->cssAppendSource('grid-large.css');
    Nub::$assets->cssAppendSource('grid-small.css');
    Nub::$assets->cssAppendSource('layout.css');
    Nub::$assets->cssAppendSource('main-menu-large.css');
    Nub::$assets->cssAppendSource('main-menu-small.css');
    Nub::$assets->cssAppendSource('main-menu-icon-large.css');
    Nub::$assets->cssAppendSource('main-menu-icon-small.css');
    Nub::$assets->cssAppendSource('secondary-menu.css');
    Nub::$assets->cssAppendSource('icon-bar.css');
    Nub::$assets->cssAppendSource('detail-table.css');
    Nub::$assets->cssAppendSource('overview-table.css');
    Nub::$assets->cssAppendSource('overview-table-content-types.css');
    Nub::$assets->cssAppendSource('overview-table-menu.css');
    Nub::$assets->cssAppendSource('overview-table-large.css');
    Nub::$assets->cssAppendSource('overview-table-large-content-types.css');
    Nub::$assets->cssAppendSource('overview-table-large-filter.css');
    Nub::$assets->cssAppendSource('overview-table-large-sort.css');
    Nub::$assets->cssAppendSource('overview-table-small.css');
    Nub::$assets->cssAppendSource('overview-table-small-content-types.css');
    Nub::$assets->cssAppendSource('overview-table-small-filter.css');
    Nub::$assets->cssAppendSource('input-table.css');
    Nub::$assets->cssAppendSource('input-table-small.css');
    Nub::$assets->cssAppendSource('button.css');
    Nub::$assets->cssAppendSource('icons.css');
    Nub::$assets->cssAppendSource('icons-small.css');
    Nub::$assets->cssAppendSource('icons-medium.css');

    Nub::$assets->jsAdmSetPageSpecificMain(__CLASS__);

    Nub::$assets->setPageTitle(Nub::$nub->getPageGroupTitle().
                               ' - '.
                               Nub::$assets->getPageTitle());
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
    $this->echoMainMenu();
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
                            'xml:lang' => Nub::$babel->getLang(),
                            'lang'     => Nub::$babel->getLang()]);
    echo '<head>';

    // Echo the meta tags.
    Nub::$assets->echoMetaTags();

    // Echo the title of the XHTML document.
    Nub::$assets->echoPageTitle();

    // Echo style sheets (if any).
    Nub::$assets->echoCascadingStyleSheets();

    echo '</head><body>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the XHTML document trailer, i.e. the end body and html tags, including the JavaScript code that will be
   * executed using RequireJS.
   */
  protected function echoPageTrailer(): void
  {
    Nub::$assets->echoJavaScript();

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
    $pag_id_org = Nub::$nub->getPagIdOrg();

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
    $this->tabs = Nub::$DL->abcAuthGetPageTabs($this->cmpId,
                                               Nub::$nub->getPtbId(),
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
    return Nub::$cgi->putId('pag', $pagId, 'pag');
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
  private function echoMainMenu(): void
  {
    $items       = Nub::$DL->abcAuthGetMenu($this->cmpId, $this->proId, $this->lanId);
    $page_mnu_id = Nub::$nub->getMnuId();

    echo '<nav class="grid-main-menu">';

    echo '<div class="main-menu-icon">';
    echo '<div class="menu-bar1"></div>';
    echo '<div class="menu-bar2"></div>';
    echo '<div class="menu-bar3"></div>';
    echo '</div>';

    $last_group = 0;
    foreach ($items as $i => $item)
    {
      $attributes = ['class' => 'menu'];

      if ($item['pag_alias']!==null)
      {
        $link = '/'.$item['pag_alias'];
      }
      else
      {
        $link = Nub::$cgi->putId('pag', $item['pag_id'], 'pag');
      }
      $link .= $item['mnu_link'];

      if ($item['mnu_id']==$page_mnu_id)
      {
        $attributes['class'] .= ' menu-active';
      }

      if ($item['mnu_group']>$last_group)
      {
        if ($last_group<>0)
        {
          echo '</ul>';
        }

        echo Html::generateTag('li', ['class' => 'menu menu-has-submenu']);
        echo Html::generateElement('a', ['class' => 'menu'], $groups[$item['mnu_group']]['mnu_text'] ?? null, true);
        echo '<ul class="menu menu-submenu">';
      }

      $a = Html::generateElement('a', ['class' => 'menu', 'href' => $link], Html::txt2Html($item['mnu_text']));
      echo Html::generateElement('li', $attributes, $a, true);

      $last_group = $item['mnu_group'];
    }

    if ($last_group<>0)
    {
      echo '</ul>';
    }

    echo '</nav>';;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
