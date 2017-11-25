<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page;

use SetBased\Abc\Abc;
use SetBased\Abc\Core\Page\Misc\W3cValidatePage;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Page\CorePage;

//----------------------------------------------------------------------------------------------------------------------
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

  /**
   * The path where the HTML code of this page is stored for the W3C validator.
   *
   * @var string
   */
  protected $w3cPathName;

  /**
   * If set to true (typically on DEV environment) the HTML code of this page will be validated by the W3C validator.
   *
   * @var bool
   */
  protected $w3cValidate = false;


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

    if (isset($_SERVER['ABC_ENV']) && $_SERVER['ABC_ENV']=='dev')
    {
      $this->enableW3cValidator();
    }

    Abc::$assets->setPageTitle(Abc::$abc->getPageGroupTitle().
                               ' - '.
                               Abc::$assets->getPageTitle());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the actual page content, i.e. the inner HTML of the body tag.
   */
  public function echoPage()
  {
    // Buffer for actual contents.
    ob_start();

    $this->echoMainContent();

    $contents = ob_get_contents();
    if (ob_get_level()) ob_end_clean();

    // Buffer for header.
    ob_start();

    $this->echoPageLeader();

    // Show the actual content of the page.
    echo '<div id="main-content">';
    echo $contents;
    echo '</div>';

    // Show the menu.
    echo '<nav id="main-menu">';
    $this->echoMainMenu();
    echo '</nav>';

    $this->echoPageTrailer();

    // Write the HTML code of this page to the file system for (asynchronous) validation.
    if ($this->w3cValidate)
    {
      file_put_contents($this->w3cPathName, ob_get_contents());
    }

    if (ob_get_level()) ob_end_flush();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Can be overridden to echo a summary of the entity shown of the current page.
   */
  protected function echoDashboard()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the main content of the page, e.g. the dashboard, the tabs (secondary menu), and tab content.
   */
  protected function echoMainContent()
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
   * Echos the actual page content.
   *
   * @return void
   */
  abstract protected function echoTabContent();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the tabs of this page, a.k.a. the secondary menu.
   */
  protected function echoTabs()
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
  protected function getPageTabs()
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
   * Enables validation of the HTML code of this page by the W3C Validator.
   */
  protected function enableW3cValidator()
  {
    $prefix            = 'w3c_validator_'.Abc::obfuscate($this->usrId, 'usr').'_';
    $w3c_file          = uniqid($prefix).'.xhtml';
    $this->w3cValidate = true;
    $this->w3cPathName = DIR_TMP.'/'.$w3c_file;
    $url               = W3cValidatePage::getUrl($w3c_file);
    Abc::$assets->jsAdmClassSpecificFunctionCall(__CLASS__, 'w3cValidate', [$url]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of a tab of the page group of current page.
   *
   * @param int $pagId The ID of the page of the tab.
   *
   * @return string
   */
  protected function getTabUrl($pagId)
  {
    $url = self::putCgiId('pag', $pagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function showIconBar()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the main menu.
   */
  private function echoMainMenu()
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
        $link = self::putCgiId('pag', $item['pag_id'], 'pag');
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

    // Define a content area for the feed back of w3c-validator.
    if ($this->w3cValidate)
    {
      echo '<div id="w3c_validate"></div>';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
