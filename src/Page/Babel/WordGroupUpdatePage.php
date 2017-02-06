<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
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
  private $details;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->wdgId       = self::getCgiId('wdg', 'wdg');
    $this->details     = Abc::$DL->bblWordGroupGetDetails($this->wdgId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $wdgId
   *
   * @return string
   */
  public static function getUrl($wdgId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_BABEL_WORD_GROUP_UPDATE, 'pag');
    $url .= self::putCgiId('wdg', $wdgId, 'wdg');
    $url .= self::putCgiId('act_lan', C::LAN_ID_BABEL_REFERENCE, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the word group.
   */
  protected function databaseAction()
  {
    $values = $this->form->getValues();

    Abc::$DL->bblWordGroupUpdateDetails($this->wdgId, $values['wdg_name'], $values['wdg_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function setValues()
  {
    $this->form->setValues($this->details);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

