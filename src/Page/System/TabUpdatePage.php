<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for updating the details of a page group.
 */
class TabUpdatePage extends TabBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the page group.
   *
   * @var array
   */
  private array $details;

  /**
   * The ID of the page group.
   *
   * @var int
   */
  private int $ptbId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->ptbId       = Nub::$nub->cgi->getManId('ptb', 'ptb');
    $this->details     = Nub::$nub->DL->abcSystemTabGetDetails($this->ptbId, $this->lanId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $ptbId The ID of the page tab.
   *
   * @return string
   */
  public static function getUrl(int $ptbId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_TAB_UPDATE, 'pag');
    $url .= Nub::$nub->cgi->putId('ptb', $ptbId, 'ptb');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of a page group.
   */
  protected function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['ptb_title'])
    {
      $wrd_id = Nub::$nub->DL->abcBabelWordInsertWord(C::WDG_ID_PAGE_GROUP_TITLE, null, null, $values['ptb_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Nub::$nub->DL->abcSystemTabUpdateDetails($this->ptbId, $wrd_id, $values['ptb_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadValues(): void
  {
    $values = $this->details;
    unset($values['ptb_title']);

    $this->form->setValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

