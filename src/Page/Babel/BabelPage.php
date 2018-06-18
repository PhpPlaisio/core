<?php

namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Form;
use SetBased\Abc\Helper\HttpHeader;

/**
 * Abstract parent page for all Babel pages.
 */
abstract class BabelPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The language ID to which the word/text/topic is been translated.
   *
   * @var int
   */
  protected $actLanId;

  /**
   * The language ID from which the word/text/topic is been translated.
   *
   * @var int
   */
  protected $refLanId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct()
  {
    parent::__construct();

    $this->refLanId = C::LAN_ID_BABEL_REFERENCE;

    $this->actLanId = self::getCgiId('act_lan', 'lan');
    if (!isset($this->actLanId)) $this->actLanId = $this->lanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the target language. If the user is authorized for multiple languages a form is shown.
   */
  public function selectLanguage(): void
  {
    $languages = Abc::$DL->abcBabelLanguageGetAllLanguages($this->refLanId);

    // If translator is authorized for 1 language return immediately.
    if (count($languages)==1)
    {
      $key = key($languages);

      $this->actLanId = $languages[$key]['lan_id'];
    }

    $form   = $this->createSelectLanguageForm($languages);
    $method = $form->execute();
    switch ($method)
    {
      case 'handleSelectLanguage':
        $this->handleSelectLanguage($form);
        break;

      default:
        $form->defaultHandler($method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param Form $form The form for selecting the language.
   */
  protected function handleSelectLanguage(Form $form): void
  {
    $values         = $form->getValues();
    $this->actLanId = $values['babel']['act_lan_id'];

    $get            = $_GET;
    $get['act_lan'] = Abc::obfuscate($this->actLanId, 'lan');

    $url = '';
    foreach ($get as $name => $value)
    {
      $url .= '/'.$name.'/'.$value;
    }

    HttpHeader::redirectSeeOther($url);
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function createSelectLanguageForm(array $languages): Form
  {
    $form = new CoreForm('babel', false);

    // Input for language.
    $input = new SelectControl('act_lan_id');
    $input->setOptions($languages, 'lan_id', 'lan_name');
    $input->setOptionsObfuscator(Abc::getObfuscator('lan'));
    $input->setValue($this->actLanId);
    $form->addFormControl($input, C::WRD_ID_LANGUAGE, true);

    // Create a submit button.
    $form->addSubmitButton(C::WRD_ID_BUTTON_OK, 'handleSelectLanguage');

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

