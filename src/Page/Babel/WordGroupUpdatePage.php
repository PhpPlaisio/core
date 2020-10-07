<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for updating the details of a word group.
 */
class WordGroupUpdatePage extends WordGroupBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the word group.
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

    $this->wdgId       = Nub::$nub->cgi->getManId('wdg', 'wdg');
    $this->details     = Nub::$nub->DL->abcBabelWordGroupGetDetails($this->wdgId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $wdgId The ID of the word group.
   *
   * @return string
   */
  public static function getUrl(int $wdgId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_BABEL_WORD_GROUP_UPDATE, 'pag');
    $url .= Nub::$nub->cgi->putId('wdg', $wdgId, 'wdg');
    $url .= Nub::$nub->cgi->putId('act_lan', C::LAN_ID_BABEL_REFERENCE, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the word group.
   */
  protected function databaseAction(): void
  {
    $values = $this->form->getValues();

    Nub::$nub->DL->abcBabelWordGroupUpdateDetails($this->wdgId, $values['wdg_name'], $values['wdg_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function setValues(): void
  {
    $this->form->setValues($this->details);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

