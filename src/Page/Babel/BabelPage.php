<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Page\TabPage;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Form;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;
use SetBased\Helper\Cast;

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
  protected int $actLanId;

  /**
   * The language ID from which the word/text/topic is been translated.
   *
   * @var int
   */
  protected int $refLanId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct()
  {
    parent::__construct();

    $this->refLanId = C::LAN_ID_BABEL_REFERENCE;
    $this->actLanId = Nub::$nub->cgi->getManId('act_lan', 'lan', $this->lanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the target language. If the user is authorized for multiple languages a form is shown.
   */
  public function selectLanguage(): void
  {
    $languages = Nub::$nub->DL->abcBabelLanguageGetAllLanguages($this->refLanId);

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
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param Form $form The form for selecting the language.
   */
  protected function handleSelectLanguage(Form $form): void
  {
    $values         = $form->getValues();
    $this->actLanId = Cast::toManInt($values['act_lan_id']);

    $get            = $_GET;
    $get['act_lan'] = Nub::$nub->obfuscator::encode($this->actLanId, 'lan');

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
    $input = new SelectControl('act_lan_id');
    $input->setOptions($languages, 'lan_id', 'lan_name');
    $input->setOptionsObfuscator(Nub::$nub->obfuscator::create('lan'));
    $input->setValue($this->actLanId);
    $form->addFormControl($input, C::WRD_ID_LANGUAGE, true);

    // Create a submit button.
    $form->addSubmitButton(C::WRD_ID_BUTTON_OK, 'handleSelectLanguage', 'change-language');

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

