<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Form\Control\SpanControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Page for translating a single word.
 */
class WordTranslatePage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the word.
   *
   * @var array
   */
  protected $details;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $form;

  /**
   * The ID of the word to be translated.
   *
   * @var int
   */
  protected $wrdId;

  /**
   * The URL to return after the word has been translated.
   *
   * @var string
   */
  private $redirect;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->wrdId    = Nub::$cgi->getManId('wrd', 'wrd');
    $this->redirect = Nub::$cgi->getOptUrl('redirect');

    $this->details = Nub::$DL->abcBabelWordGetDetails($this->wrdId, $this->actLanId);

    if ($this->redirect===null)
    {
      $this->redirect = WordGroupDetailsPage::getUrl($this->details['wdg_id'], $this->actLanId);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int         $wrdId    The ID of the word to be translated.
   * @param int         $lanId    The ID of the target language.
   * @param string|null $redirect If set the URL to redirect the user agent after the word has been translated.
   *
   * @return string
   */
  public static function getUrl(int $wrdId, int $lanId, ?string $redirect = null): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_BABEL_WORD_TRANSLATE, 'pag');
    $url .= Nub::$cgi->putId('wrd', $wrdId, 'wrd');
    $url .= Nub::$cgi->putId('act_lan', $lanId, 'lan');
    $url .= Nub::$cgi->putUrl('redirect', $redirect);

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
    $refLanguage = Nub::$DL->abcBabelLanguageGetName($this->refLanId, $this->refLanId);
    $actLanguage = Nub::$DL->abcBabelLanguageGetName($this->actLanId, $this->refLanId);

    $this->form = new CoreForm();

    // Show word group name.
    $input = new SpanControl('word_group');
    $input->setInnerText($this->details['wdg_name']);
    $this->form->addFormControl($input, 'Word Group');

    // Show word group ID
    $input = new SpanControl('wrd_id');
    $input->setInnerText($this->details['wdg_id']);
    $this->form->addFormControl($input, 'ID Group');

    // Show label
    $input = new SpanControl('label');
    $input->setInnerText($this->details['wrd_label']);
    $this->form->addFormControl($input, 'Label');

    // Show comment.
    $input = new SpanControl('comment');
    $input->setInnerText($this->details['wrd_comment']);
    $this->form->addFormControl($input, 'Comment');

    // Show data
    // @todo Show data.

    // Show word in reference language.
    Nub::$babel->pushLanguage($this->refLanId);
    try
    {
      $input = new SpanControl('ref_language');
      $input->setInnerText(Nub::$babel->getWord($this->wrdId));
      $this->form->addFormControl($input, $refLanguage);
    }
    finally
    {
      Nub::$babel->popLanguage();
    }

    // Create form control for the actual word.
    $input = new TextControl('wdt_text');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $input->setValue($this->details['wdt_text']);
    $this->form->addFormControl($input, $actLanguage, true);

    // Create a submit button.
    $this->form->addSubmitButton(C::WRD_ID_BUTTON_TRANSLATE, 'handleForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the translation of the word in the target language.
   */
  private function dataBaseAction(): void
  {
    $values  = $this->form->getValues();
    $changes = $this->form->getChangedControls();

    // Return immediately when no form controls are changed.
    if (empty($changes)) return;

    Nub::$DL->abcBabelWordTranslateWord($this->usrId, $this->wrdId, $this->actLanId, $values['wdt_text']);
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
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm(): void
  {
    $this->dataBaseAction();

    $this->response = new SeeOtherResponse($this->redirect);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

