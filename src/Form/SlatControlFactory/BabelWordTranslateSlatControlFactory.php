<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\SlatControlFactory;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Form\Control\SlatControl;
use SetBased\Abc\Form\Control\SlatControlFactory;
use SetBased\Abc\Form\Control\TableColumnControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\SlatJoint\TableColumnSlatJoint;
use SetBased\Abc\Form\SlatJoint\TextSlatJoint;
use SetBased\Abc\Obfuscator\Obfuscator;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
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
  private $wrdIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $lanId       The ID of the reference language.
   * @param int $targetLanId The ID of the target language.
   */
  public function __construct($lanId, $targetLanId)
  {
    $ref_language = Abc::$DL->bblLanguageGetName($lanId, $lanId);
    $act_language = Abc::$DL->bblLanguageGetName($targetLanId, $lanId);

    // Create slat joint for table column with word ID.
    $table_column = new NumericTableColumn('ID', 'wrd_id');
    $this->addSlatJoint('wrd_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with the word in the reference language.
    $table_column = new TextTableColumn($ref_language, 'ref_wdt_text');
    $this->addSlatJoint('ref_wdt_text', new TableColumnSlatJoint($table_column));

    // Create slat joint with text form control for the word in the target language.
    $table_column = new TextSlatJoint($act_language);
    $this->addSlatJoint('act_wdt_text', $table_column);

    $this->wrdIdObfuscator = Abc::getObfuscator('wrd');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function createRow($louverControl, $data)
  {
    /** @var SlatControl $row */
    $row = $louverControl->addFormControl(new SlatControl($data['wrd_id']));
    $row->setObfuscator($this->wrdIdObfuscator);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'wrd_id');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'ref_wdt_text');
    $control->setValue($data);

    /** @var TextControl $control */
    $control = $this->createFormControl($row, 'act_wdt_text');
    $control->setValue($data['act_wdt_text']);
    $control->setAttrSize(C::LEN_WDT_TEXT);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
