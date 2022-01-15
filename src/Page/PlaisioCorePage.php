<?php
declare(strict_types=1);

namespace Plaisio\Core\Page;

use Plaisio\C;
use Plaisio\Helper\Html;
use Plaisio\Helper\OB;
use Plaisio\Helper\RenderWalker;
use Plaisio\Kernel\Nub;
use Plaisio\Page\CorePage;
use Plaisio\Response\Response;

/**
 * Abstract parent page for all core pages of PhpPlaisio.
 */
abstract class PlaisioCorePage extends CorePage
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
   * PlaisioCorePage constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->lanId = C::LAN_ID_BABEL_REFERENCE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the actual page content, i.e. the inner HTML of the body tag.
   */
  public function handleRequest(): Response
  {
    $ob        = new OB();
    $decorator = Nub::$nub->pageDecorator->create('core');

    $this->echoPageContent();

    if ($this->response===null)
    {
      $this->response = $decorator->decorate($ob->getClean());
    }

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

    echo '<nav class="secondary-menu">';
    $this->echoTabs();
    echo '</nav>';

    echo '<div class="l-content">';
    $this->echoTabContent();
    echo '</div>';
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
    $pagIdOrg = Nub::$nub->pageInfo['pag_id_org'];
    $tabs     = $this->getPageTabs();
    $walker   = new RenderWalker('secondary-menu');
    $items    = [];
    foreach ($tabs as $tab)
    {
      if ($tab['url']!==null)
      {
        $additionalClass = ($tab['pag_id']==$pagIdOrg) ? 'selected' : null;
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

    $struct = ['tag'   => 'nav',
               'attr'  => ['class' => $walker->getClasses('wrapper')],
               'inner' => ['tag'   => 'ul',
                           'attr'  => ['class' => $walker->getClasses('list')],
                           'inner' => $items]];

    echo Html::htmlNested($struct);
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
  protected function showIconBar(): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
