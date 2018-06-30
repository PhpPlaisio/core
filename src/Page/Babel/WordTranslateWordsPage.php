<?php

namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\BabelWordTranslateSlatControlFactory;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Helper\HttpHeader;

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
   * @var CoreForm
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

    $this->wdgId = Abc::$cgi->getManId('wdg', 'wdg');
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
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_BABEL_WORD_TRANSLATE_WORDS, 'pag');
    $url .= Abc::$cgi->putId('wdg', $wdgId, 'wdg');
    $url .= Abc::$cgi->putId('act_lan', $lanId, 'lan');

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
    $words = Abc::$DL->abcBabelWordGroupGetAllWordsTranslator($this->wdgId, $this->actLanId);

    $this->form = new CoreForm();

    // Add field set.
    $field_set = new FieldSet('');
    $this->form->addFieldSet($field_set);

    // Create factory.
    $factory = new BabelWordTranslateSlatControlFactory($this->refLanId, $this->actLanId);
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl();
    $submit = new SubmitControl('submit');
    $submit->setMethod('handleForm');
    $submit->setValue(Abc::$babel->getWord(C::WRD_ID_BUTTON_TRANSLATE));
    $button->addFormControl($submit);

    // Put everything together in a LoverControl.
    $louver = new LouverControl('data');
    $louver->addClass('overview_table');
    $louver->setRowFactory($factory);
    $louver->setFooterControl($button);
    $louver->setData($words);
    $louver->populate();

    // Add the LouverControl the the form.
    $field_set->addFormControl($louver);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the translations of the words in the target language.
   */
  private function databaseAction(): void
  {
    $values  = $this->form->getValues();
    $changes = $this->form->getChangedControls();

    foreach ($changes['data'] as $wrd_id => $changed)
    {
      Abc::$DL->abcBabelWordTranslateWord($this->usrId, $wrd_id, $this->actLanId, $values['data'][$wrd_id]['act_wdt_text']);
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
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm(): void
  {
    $this->databaseAction();

    HttpHeader::redirectSeeOther(WordGroupDetailsPage::getUrl($this->wdgId, $this->actLanId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

