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
  protected array $details;

  /**
   * The form shown on this page.
   *
   * @var LouverForm
   */
  protected LouverForm $form;

  /**
   * The ID of the word group.
   *
   * @var int
   */
  protected int $wdgId;

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
    $url .= Nub::$nub->cgi->putId('lan-target', $lanId, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function htmlTabContent(): ?string
  {
    $this->createForm();
    $this->executeForm();

    return $this->form->htmlFormEmptyIfValid();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    $words = Nub::$nub->DL->abcBabelWordGroupGetAllWordsTranslator($this->wdgId, $this->lanIdTar);

    $this->form = new LouverForm();
    $this->form->setRowFactory(new  BabelWordTranslateSlatControlFactory($this->lanIdRef, $this->lanIdTar))
               ->addSubmitButton(C::WRD_ID_BUTTON_TRANSLATE, 'handleForm')
               ->setName('data')
               ->populate($words);
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

        $this->response = new SeeOtherResponse(WordGroupDetailsPage::getUrl($this->wdgId, $this->lanIdTar));
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
    $values  = $this->form->getValues();
    $changes = $this->form->getChangedControls();

    if (empty($changes['data'])) return;

    foreach ($changes['data'] as $wrdId => $changed)
    {
      Nub::$nub->DL->abcBabelWordTranslateWord($this->usrId,
                                               $wrdId,
                                               $this->lanIdTar,
                                               $values['data'][$wrdId]['tar_wdt_text']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
