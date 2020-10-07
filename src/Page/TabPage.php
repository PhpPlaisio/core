<?php
declare(strict_types=1);

namespace Plaisio\Core\Page;

use Plaisio\Helper\Html;
use Plaisio\Kernel\Nub;
use Plaisio\Page\CorePage;
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
  protected bool $showDisabledTabs = true;

  /**
   * If set the tab content is shown.
   *
   * @var bool
   */
  protected bool $showTabContent = false;

  /**
   * The tabs of the core page.
   *
   * @var array[]
   */
  protected array $tabs;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the actual page content, i.e. the inner HTML of the body tag.
   */
  public function handleRequest(): Response
  {
    $decorator = Nub::$nub->pageDecorator->create('core');

    ob_start();
    $this->echoPageContent();
    $content = ob_get_clean();

    if ($this->response===null)
    {
      $this->response = $decorator->decorate($content);
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

    echo '<nav class="secondary-menu clearfix">';
    $this->echoTabs();
    echo '</nav>';

    echo '<div class="content">';
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
    $pag_id_org = Nub::$nub->pageInfo['pag_id_org'];

    $this->getPageTabs();

    echo '<ul>';
    foreach ($this->tabs as $tab)
    {

      if ($tab['url']!==null)
      {
        $class = ($tab['pag_id']===$pag_id_org) ? $class = 'selected' : '';
        echo '<li>';
        echo Html::generateElement('a', ['href' => $tab['url'], 'class' => $class], $tab['tab_name']);
        echo '</li>';
      }
      else
      {
        if ($this->showDisabledTabs)
        {
          echo '<li>';
          echo Html::generateElement('a', ['class' => 'disabled'], $tab['tab_name']);
          echo '</li>';
        }
      }

      if ($tab['pag_id']===$pag_id_org && $tab['url']!==null)
      {
        $this->showTabContent = true;
      }
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
}

//----------------------------------------------------------------------------------------------------------------------
