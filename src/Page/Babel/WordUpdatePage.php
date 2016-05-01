<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a word.
 */
class WordUpdatePage extends WordBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the word.
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

    $this->wrdId       = self::getCgiId('wrd', 'wrd');
    $this->details     = Abc::$DL->wordGetDetails($this->wrdId, $this->actLanId);
    $this->wdgId       = $this->details['wdg_id'];
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int         $wrdId       The ID of the word.
   * @param string|null $redirectUrl If set the URL to redirect the user agent.
   *
   * @return string
   */
  public static function getUrl($wrdId, $redirectUrl = null)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_BABEL_WORD_UPDATE, 'pag');
    $url .= self::putCgiVar('wrd', $wrdId, 'wrd');
    $url .= self::putCgiVar('redirect', $redirectUrl);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the word.
   */
  public function databaseAction()
  {
    $values  = $this->form->getValues();
    $changes = $this->form->getChangedControls();

    // Return immediately when no form controls are changed.
    if (empty($changes)) return;

    Abc::$DL->wordUpdateDetails($this->wrdId,
                                $values['wdg_id'],
                                $values['wrd_label'],
                                $values['wrd_comment'],
                                $values['wdt_text']);
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

