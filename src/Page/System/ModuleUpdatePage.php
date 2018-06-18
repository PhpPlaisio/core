<?php

namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

/**
 * Page for modifying the details of a module.
 */
class ModuleUpdatePage extends ModuleBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var array The details of the module.
   */
  private $details;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->mdlId       = self::getCgiId('mdl', 'mdl');
    $this->details     = Abc::$DL->abcSystemModuleGetDetails($this->mdlId, $this->lanId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $mdlId The ID of the module.
   *
   * @return string
   */
  public static function getUrl(int $mdlId): string
  {
    $url = self::putCgiId('pag', C::PAG_ID_SYSTEM_MODULE_UPDATE, 'pag');
    $url .= self::putCgiId('mdl', $mdlId, 'mdl');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the module.
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
      $wrd_id = Abc::$DL->abcBabelWordInsertWord(C::WDG_ID_MODULE, null, null, $values['mdl_name']);
    }
    else
    {
      // Reuse of exiting module name.
      $wrd_id = $values['wrd_id'];
    }

    // Create the new module in the database.
    Abc::$DL->abcSystemModuleModify($this->mdlId, $wrd_id);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadValues(): void
  {
    $values = $this->details;
    unset($values['mdl_name']);

    $this->form->mergeValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
