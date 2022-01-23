<?php
declare(strict_types=1);

namespace Plaisio\Core\Page;

use Plaisio\C;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Kernel\Nub;
use Plaisio\Page\CorePage;
use Plaisio\Response\HtmlResponse;
use Plaisio\Response\Response;

/**
 * Abstract parent page for all core pages of PhpPlaisio.
 */
abstract class CoreCorePage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set disabled tabs (i.e. tabs in $tabs field 'url' is empty) are shown. Otherwise, disabled tabs are hidden.
   */
  protected bool $showDisabledTabs = true;

  /**
   * If set the tab content is shown.
   *
   * @var bool
   */
  protected bool $showTabContent = false;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->lanId = C::LAN_ID_BABEL_REFERENCE;

    Nub::$nub->assets->jsAdmSetMain(__CLASS__);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function handleRequest(): Response
  {
    $page = $this->structPage();
    if ($this->response===null)
    {
      $this->response = new HtmlResponse(Html::htmlNested($page));
    }

    return $this->response;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Retrieves the tabs of page group of the current page.
   *
   * @return array[]
   */
  protected function getPageTabs(): array
  {
    $tabs = Nub::$nub->DL->abcAuthGetPageTabs($this->cmpId,
                                              Nub::$nub->pageInfo['ptb_id'],
                                              $this->proId,
                                              $this->lanId);
    foreach ($tabs as &$tab)
    {
      $tab['url'] = $this->getTabUrl($tab['pag_id']);
    }

    return $tabs;
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
  /**
   * Returns the actual page content.
   *
   * @return string
   */
  abstract protected function htmlTabContent(): ?string;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the structure of the admin menu.
   */
  protected function structAdminMenu(): ?array
  {
    return ['tag'   => 'div',
            'attr'  => ['class' => 'grid-admin-menu'],
            'inner' => [['tag'   => 'div',
                         'attr'  => ['class' => 'admin-menu-icon'],
                         'inner' => [['tag'  => 'div',
                                      'attr' => ['class' => 'menu-bar1'],
                                      'html' => null],
                                     ['tag'  => 'div',
                                      'attr' => ['class' => 'menu-bar2'],
                                      'html' => null],
                                     ['tag'  => 'div',
                                      'attr' => ['class' => 'menu-bar3'],
                                      'html' => null]]],
                        ['tag'   => 'div',
                         'attr'  => ['class' => 'admin-menu-logo'],
                         'inner' => ['tag'  => 'a',
                                     'attr' => ['href' => '/'],
                                     'html' => null]],
                        ['html' => Nub::$nub->menu->menu(C::MNU_ID_ADMIN)]]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the structure of the HTML code of the body.
   *
   * @param string|null $htmlContent The actual content of the page.
   *
   * @return array
   */
  protected function structBody(?string $htmlContent): ?array
  {
    return ['tag'   => 'body',
            'inner' => [$this->structGridContainer($htmlContent),
                        $this->structTrailingJavaScript()]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Can be overridden to echo a summary of the entity shown of the current page.
   *
   * @return array|null
   */
  protected function structDashboard(): ?array
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the struct of the container grid.
   *
   * @param string|null $htmlContent The actual content of the page.
   *
   * @return array
   */
  protected function structGridContainer(?string $htmlContent): ?array
  {
    return ['tag'   => 'div',
            'attr'  => ['class' => 'grid-container'],
            'inner' => [$this->structGridMain($htmlContent),
                        $this->structAdminMenu()]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the struct of the main grid.
   *
   * @param string|null $htmlContent The actual content of the page.
   *
   * @return array
   */
  protected function structGridMain(?string $htmlContent): ?array
  {
    return ['tag'   => 'div',
            'attr'  => ['class' => 'grid-main'],
            'inner' => [$this->structDashboard(),
                        $this->structNavigationBar(),
                        $this->structSecondaryMenu(),
                        ['tag'  => 'div',
                         'attr' => ['class' => 'l-content'],
                         'html' => $htmlContent]]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the structure of the navigation bar.
   *
   * @return array|null
   */
  protected function structNavigationBar(): ?array
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the structure of the HTML code of the body.
   *
   * @return array|null
   */
  protected function structPage(): ?array
  {
    $htmlContent = $this->htmlTabContent();
    if ($this->response===null)
    {
      Nub::$nub->assets->metaAddElement(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
      Nub::$nub->assets->cssPushSourcesList(__CLASS__);
      Nub::$nub->assets->pushPageTitle(Nub::$nub->pageInfo['pag_title']);
      Nub::$nub->assets->pushPageTitle(Nub::$nub->pageInfo['ptb_title']);

      $struct = [['html' => '<!DOCTYPE html>'],
                 ['tag'   => 'html',
                  'attr'  => ['xmlns'    => 'http://www.w3.org/1999/xhtml',
                              'xml:lang' => Nub::$nub->babel->getLang(),
                              'lang'     => Nub::$nub->babel->getLang()],
                  'inner' => [$this->structHead(),
                              $this->structBody($htmlContent)]]];
    }
    else
    {
      $struct = null;
    }

    return $struct;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return the structure of the secondary menu (e.g. page tabs).
   *
   * @return array|null
   */
  protected function structSecondaryMenu(): ?array
  {
    $pagIdOrg = Nub::$nub->pageInfo['pag_id_org'];
    $tabs     = $this->getPageTabs();
    $walker   = new RenderWalker('secondary-menu');
    $items    = [];
    foreach ($tabs as $tab)
    {
      if ($tab['url']!==null)
      {
        $additionalClass = ($tab['pag_id']===$pagIdOrg) ? 'is-selected' : null;
        $items[]         = ['tag'   => 'li',
                            'attr'  => ['class' => $walker->getClasses('item')],
                            'inner' => ['tag'  => 'a',
                                        'attr' => ['class' => $walker->getClasses('link', $additionalClass),
                                                   'href'  => $tab['url']],
                                        'text' => $tab['tab_name']]];
      }
      elseif ($this->showDisabledTabs)
      {
        $items[] = ['tag'   => 'li',
                    'attr'  => ['class' => $walker->getClasses('item')],
                    'inner' => ['tag'  => 'a',
                                'attr' => ['class' => $walker->getClasses('link', 'disabled')],
                                'text' => $tab['tab_name']]];
      }

      if ($tab['pag_id']===$pagIdOrg && $tab['url']!==null)
      {
        $this->showTabContent = true;
      }
    }

    return ['tag'   => 'nav',
            'attr'  => ['class' => $walker->getClasses('wrapper')],
            'inner' => ['tag'   => 'ul',
                        'attr'  => ['class' => $walker->getClasses('list')],
                        'inner' => $items]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return array
   */
  protected function structTrailingJavaScript(): ?array
  {
    return Nub::$nub->assets->structJavaScript();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the structure of the header.
   *
   * @return array|null
   */
  private function structHead(): ?array
  {
    return ['tag'   => 'head',
            'inner' => [Nub::$nub->assets->structMetaTags(),
                        Nub::$nub->assets->structPageTitle(),
                        Nub::$nub->assets->structCascadingStyleSheets()]];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
