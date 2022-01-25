<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Form;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Abstract parent page for all Babel pages.
 */
abstract class BabelPage extends CoreCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The language ID from which the word/text/topic is being translated.
   *
   * @var int
   */
  protected int $lanIdRef;

  /**
   * The language ID to which the word/text/topic is being translated.
   *
   * @var int
   */
  protected int $lanIdTar;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct()
  {
    parent::__construct();

    $this->lanIdRef = C::LAN_ID_BABEL_REFERENCE;
    $this->lanIdTar = Nub::$nub->cgi->getManId('lan-target', 'lan', $this->lanIdRef);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the target language. If the user is authorized for multiple languages a form is shown.
   */
  public function htmlSelectLanguage(): string
  {
    $languages = Nub::$nub->DL->abcBabelLanguageGetAllLanguages($this->lanIdRef);

    // If translator is authorized for 1 language return immediately.
    if (count($languages)==1)
    {
      $key = key($languages);

      $this->lanIdTar = $languages[$key]['lan_id'];
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
    }

    return $form->htmlForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param Form $form The form for selecting the language.
   */
  protected function handleSelectLanguage(Form $form): void
  {
    $values         = $form->getValues();
    $this->lanIdTar = $values['tar_lan_id'];

    $get               = $_GET;
    $get['lan-target'] = Nub::$nub->obfuscator::encode($this->lanIdTar, 'lan');

    $url = '';
    foreach ($get as $name => $value)
    {
      $url .= '/'.$name.'/'.$value;
    }

    $this->response = new SeeOtherResponse($url);
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function createSelectLanguageForm(array $languages): Form
  {
    $form = new CoreForm();

    // Input for language.
    $input = new SelectControl('tar_lan_id');
    $input->setOptions($languages, 'lan_id', 'lan_name');
    $input->setOptionsObfuscator(Nub::$nub->obfuscator::create('lan'));
    $input->setValue($this->lanIdTar);
    $form->addFormControl($input, C::WRD_ID_LANGUAGE, true);

    // Create a submit button.
    $form->addSubmitButton(C::WRD_ID_BUTTON_OK, 'handleSelectLanguage', 'change-language');

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

