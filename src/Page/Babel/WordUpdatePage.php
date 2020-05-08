<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Kernel\Nub;

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

    $this->wrdId       = Nub::$nub->cgi->getManId('wrd', 'wrd');
    $this->details     = Nub::$nub->DL->abcBabelWordGetDetails($this->wrdId, $this->actLanId);
    $this->wdgId       = $this->details['wdg_id'];
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int         $wrdId    The ID of the word.
   * @param string|null $redirect If set the URL to redirect the user agent.
   *
   * @return string
   */
  public static function getUrl(int $wrdId, ?string $redirect = null): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_BABEL_WORD_UPDATE, 'pag');
    $url .= Nub::$nub->cgi->putId('wrd', $wrdId, 'wrd');
    $url .= Nub::$nub->cgi->putUrl('redirect', $redirect);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the word.
   */
  public function databaseAction(): void
  {
    $values  = $this->form->getValues();
    $changes = $this->form->getChangedControls();

    // Return immediately when no form controls are changed.
    if (empty($changes)) return;

    Nub::$nub->DL->abcBabelWordUpdateDetails($this->wrdId,
                                             $values['wdg_id'],
                                             $values['wrd_label'],
                                             $values['wrd_comment'],
                                             $values['wdt_text']);
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

