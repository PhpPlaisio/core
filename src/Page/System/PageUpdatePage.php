<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for updating the details of a target page.
 */
class PageUpdatePage extends PageBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the target page.
   *
   * @var array
   */
  private array $details;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->pagIdTarget = Nub::$nub->cgi->getManId('pag-target', 'pag');
    $this->details     = Nub::$nub->DL->abcSystemPageGetDetails($this->pagIdTarget, $this->lanId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $pagId The ID of the target page.
   *
   * @return string
   */
  public static function getUrl(int $pagId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_PAGE_UPDATE, 'pag');
    $url .= Nub::$nub->cgi->putId('pag-target', $pagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a page.
   */
  protected function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['pag_title'])
    {
      $wrd_id = Nub::$nub->DL->abcBabelWordInsertWord(C::WDG_ID_PAGE_TITLE, null, null, $values['pag_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Nub::$nub->DL->abcSystemPageUpdateDetails($this->pagIdTarget,
                                              $wrd_id,
                                              $values['ptb_id'],
                                              $values['pag_id_org'],
                                              $values['pag_alias'],
                                              $values['pag_class'],
                                              $values['pag_label'],
                                              $values['pag_weight']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadValues(): void
  {
    $values = $this->details;
    unset($values['pag_title']);

    $this->form->setValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

