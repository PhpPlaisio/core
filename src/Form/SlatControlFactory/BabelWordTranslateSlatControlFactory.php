<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\C;
use Plaisio\Form\Control\SlatControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\SlatJoint\TextSlatJoint;
use Plaisio\Form\SlatJointFactory\SlatControlFactory;
use Plaisio\Form\Validator\MandatoryValidator;
use Plaisio\Kernel\Nub;
use Plaisio\Obfuscator\Obfuscator;
use Plaisio\Table\TableColumn\NumberTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;
use SetBased\Helper\Cast;

/**
 * Slat control factory for creating slat controls for translating words.
 */
class BabelWordTranslateSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The obfuscator for word IDs.
   *
   * @var Obfuscator
   */
  private Obfuscator $wrdIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $lanIdRef The ID of the reference language.
   * @param int $lanIdTar The ID of the target language.
   */
  public function __construct(int $lanIdRef, int $lanIdTar)
  {
    parent::__construct();

    $languageRef = Nub::$nub->DL->abcBabelLanguageGetName($lanIdRef, $lanIdRef);
    $languageTar = Nub::$nub->DL->abcBabelLanguageGetName($lanIdTar, $lanIdRef);

    // Create slat joint for table column with word ID.
    $this->addColumn(new NumberTableColumn('ID', 'wrd_id'));

    // Create slat joint for table column with the word in the reference language.
    $this->addColumn(new TextTableColumn($languageRef, 'ref_wdt_text'));

    // Create slat joint with text form control for the word in the target language.
    $this->addSlatJoint(new TextSlatJoint('tar_wdt_text', $languageTar));

    $this->wrdIdObfuscator = Nub::$nub->obfuscator::create('wrd');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(array $data): SlatControl
  {
    $row = new SlatControl(Cast::toOptString($data['wrd_id']));
    $row->setObfuscator($this->wrdIdObfuscator);

    /** @var TextControl $input */
    $input = $this->createFormControl($row, 'tar_wdt_text');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $input->setValue($data['tar_wdt_text']);
    $input->addValidator(new MandatoryValidator());

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
