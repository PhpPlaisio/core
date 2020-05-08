<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for inserting a module.
 */
class ModuleInsertPage extends ModuleBasePage
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
   * Returns the relative URL to this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_MODULE_INSERT, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a module.
   */
  protected function dataBaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['mdl_name'])
    {
      // New module name. Insert word en retrieve wrd_id of the new word.
      $wrd_id = Nub::$nub->DL->abcBabelWordInsertWord(C::WDG_ID_MODULE, null, null, $values['mdl_name']);
    }
    else
    {
      // Reuse of exiting module name.
      $wrd_id = $values['wrd_id'];
    }

    // Create the new module in the database.
    $this->mdlId = Nub::$nub->DL->abcSystemModuleInsert($wrd_id);
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
