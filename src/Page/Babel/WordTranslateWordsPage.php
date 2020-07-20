<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Core\Form\SlatControlFactory\BabelWordTranslateSlatControlFactory;
use Plaisio\Form\LouverForm;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Page for translating all words in a word group.
 */
class WordTranslateWordsPage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the words.
   *
   * @var array
   */
  protected $details;

  /**
   * The form shown on this page.
   *
   * @var LouverForm
   */
  protected $form;

  /**
   * The ID of the word group.
   *
   * @var int
   */
  protected $wdgId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->wdgId = Nub::$nub->cgi->getManId('wdg', 'wdg');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $wdgId The ID of the word group.
   * @param int $lanId The target language.
   *
   * @return string
   */
  public static function getUrl(int $wdgId, int $lanId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_BABEL_WORD_TRANSLATE_WORDS, 'pag');
    $url .= Nub::$nub->cgi->putId('wdg', $wdgId, 'wdg');
    $url .= Nub::$nub->cgi->putId('act_lan', $lanId, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function echoTabContent(): void
  {
    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    $words = Nub::$nub->DL->abcBabelWordGroupGetAllWordsTranslator($this->wdgId, $this->actLanId);

    $this->form = new LouverForm();
    $this->form->setFactory(new  BabelWordTranslateSlatControlFactory($this->refLanId, $this->actLanId))
               ->setData($words)
               ->addSubmitButton(C::WRD_ID_BUTTON_TRANSLATE, 'handleForm')
               ->populate();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the translations of the words in the target language.
   */
  private function databaseAction(): void
  {
    $values  = $this->form->getValues();
    $changes = $this->form->getChangedControls();

    // If no changes are submitted return immediately.
    if (empty($changes)) return;

    foreach ($changes['data'] as $wrd_id => $changed)
    {
      Nub::$nub->DL->abcBabelWordTranslateWord($this->usrId, $wrd_id, $this->actLanId, $values['data'][$wrd_id]['act_wdt_text']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm(): void
  {
    $method = $this->form->execute();
    switch ($method)
    {
      case 'handleForm':
        $this->handleForm();
        break;

      default:
        $this->form->defaultHandler($method);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm(): void
  {
    $this->databaseAction();

    $this->response = new SeeOtherResponse(WordGroupDetailsPage::getUrl($this->wdgId, $this->actLanId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

