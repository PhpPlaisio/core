<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for updating the details of a functionality.
 */
class FunctionalityUpdatePage extends FunctionalityBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the functionality.
   *
   * @var array
   */
  private $details;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->funId       = Nub::$cgi->getManId('fun', 'fun');
    $this->details     = Nub::$DL->abcSystemFunctionalityGetDetails($this->funId, $this->lanId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $funId The ID of the functionality.
   *
   * @return string
   */
  public static function getUrl(int $funId): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_UPDATE, 'pag');
    $url .= Nub::$cgi->putId('fun', $funId, 'fun');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the functionality.
   */
  protected function dataBaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['fun_name'])
    {
      $wrd_id = Nub::$DL->abcBabelWordInsertWord(C::WDG_ID_FUNCTIONALITIES, null, null, $values['fun_name']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Nub::$DL->abcSystemFunctionalityUpdateDetails($this->funId, $values['mdl_id'], $wrd_id);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadValues(): void
  {
    $values = $this->details;
    unset($values['fun_name']);

    $this->form->mergeValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
