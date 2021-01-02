<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\C;
use Plaisio\Form\Control\SlatControl;
use Plaisio\Form\SlatJointFactory\SlatControlFactory;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\SlatJoint\TextSlatJoint;
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
   * @param int $lanId       The ID of the reference language.
   * @param int $targetLanId The ID of the target language.
   */
  public function __construct(int $lanId, int $targetLanId)
  {
    parent::__construct();

    $ref_language = Nub::$nub->DL->abcBabelLanguageGetName($lanId, $lanId);
    $act_language = Nub::$nub->DL->abcBabelLanguageGetName($targetLanId, $lanId);

    // Create slat joint for table column with word ID.
    $this->addColumn(new NumberTableColumn('ID', 'wrd_id'));

    // Create slat joint for table column with the word in the reference language.
    $this->addColumn(new TextTableColumn($ref_language, 'ref_wdt_text'));

    // Create slat joint with text form control for the word in the target language.
    $this->addSlatJoint(new TextSlatJoint('act_wdt_text', $act_language));

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
    $input = $this->createFormControl($row, 'act_wdt_text');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $input->setValue($data['act_wdt_text']);
    $input->addValidator(new MandatoryValidator());

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
