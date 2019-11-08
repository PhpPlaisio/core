<?php

namespace SetBased\Abc\Core\Page;

use SetBased\Abc\Abc;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Page\CorePage;
use SetBased\Abc\Response\BaseResponse;
use SetBased\Abc\Response\Response;

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

    Abc::$assets->cssAppendSource('abc/reset.css');
    Abc::$assets->cssAppendSource('abc/layout.css');
    Abc::$assets->cssAppendSource('abc/main-menu.css');
    Abc::$assets->cssAppendSource('abc/secondary-menu.css');
    Abc::$assets->cssAppendSource('abc/dashboard.css');
    Abc::$assets->cssAppendSource('abc/content.css');
    Abc::$assets->cssAppendSource('abc/style.css');
    Abc::$assets->cssAppendSource('abc/overview_table.css');
    Abc::$assets->cssAppendSource('abc/detail_table.css');
    Abc::$assets->cssAppendSource('abc/input_table.css');

    Abc::$assets->jsAdmSetPageSpecificMain(__CLASS__);

    Abc::$assets->setPageTitle(Abc::$abc->getPageGroupTitle().
                               ' - '.
                               Abc::$assets->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the actual page content, i.e. the inner HTML of the body tag.
   */
  public function handleRequest(): Response
  {
    // Buffer for actual contents.
    ob_start();

    $this->echoMainContent();

    $contents = ob_get_clean();

    if ($this->response!==null)
    {
      return $this->response;
    }

    // Buffer for header.
    ob_start();

    $this->echoPageLeader();
    echo '<div id="main-content">';
    echo $contents;
    echo '</div>';
    echo '<nav id="main-menu">';
    $this->echoMainMenu();
    echo '</nav>';
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
  protected function echoMainContent(): void
  {
    $this->echoDashboard();

    $this->showIconBar();

    echo '<nav id="secondary-menu" class="clearfix">';
    $this->echoTabs();
    echo '</nav>';

    echo '<div id="content">';
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
                            'xml:lang' => Abc::$babel->getLang(),
                            'lang'     => Abc::$babel->getLang()]);
    echo '<head>';

    // Echo the meta tags.
    Abc::$assets->echoMetaTags();

    // Echo the title of the XHTML document.
    Abc::$assets->echoPageTitle();

    // Echo style sheets (if any).
    Abc::$assets->echoCascadingStyleSheets();

    echo '</head><body>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the XHTML document trailer, i.e. the end body and html tags, including the JavaScript code that will be
   * executed using RequireJS.
   */
  protected function echoPageTrailer(): void
  {
    Abc::$assets->echoJavaScript();

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
    $pag_id_org = Abc::$abc->getPagIdOrg();

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
    $this->tabs = Abc::$DL->abcAuthGetPageTabs($this->cmpId,
                                               Abc::$abc->getPtbId(),
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
    return Abc::$cgi->putId('pag', $pagId, 'pag');
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
    $items       = Abc::$DL->abcAuthGetMenu($this->cmpId, $this->proId, $this->lanId);
    $page_mnu_id = Abc::$abc->getMnuId();

    echo '<ul>';
    $last_group = 0;
    foreach ($items as $i => $item)
    {
      if (isset($item['pag_alias']))
      {
        $link = '/'.$item['pag_alias'];
      }
      else
      {
        $link = Abc::$cgi->putId('pag', $item['pag_id'], 'pag');
      }
      $link .= $item['mnu_link'];

      $class = 'menu_'.$item['mnu_level'];

      if ($i==0) $class .= ' first';

      if ($i==count($items) - 1) $class .= ' last';

      if ($item['mnu_id']==$page_mnu_id) $class .= ' menu_active';

      if ($item['mnu_group']<>$last_group) $class .= ' group_first';

      if (!isset($items[$i + 1]) || $item['mnu_group']<>$items[$i + 1]['mnu_group'])
      {
        $class .= ' group_last';
      }

      $a = Html::generateElement('a', ['href' => $link], Html::txt2Html($item['mnu_text']));
      echo Html::generateElement('li', ['class' => $class], $a, true);

      $last_group = $item['mnu_group'];
    }
    echo '</ul>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
