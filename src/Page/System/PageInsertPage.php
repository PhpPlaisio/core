<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for inserting a page.
 */
class PageInsertPage extends PageBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->buttonWrdId = C::WRD_ID_BUTTON_INSERT;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_PAGE_INSERT, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a page.
   */
  protected function databaseAction(): void
  {
    $values = $this->form->getValues();
    if ($values['pag_title'])
    {
      $wrd_id = Nub::$nub->DL->abcBabelWordInsertWord(C::WDG_ID_PAGE_TITLE, null, null, $values['pag_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    $this->pagIdTarget = Nub::$nub->DL->abcSystemPageInsertDetails($wrd_id,
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
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

