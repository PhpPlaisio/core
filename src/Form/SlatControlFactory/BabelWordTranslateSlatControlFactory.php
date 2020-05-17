<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\C;
use Plaisio\Form\Control\LouverControl;
use Plaisio\Form\Control\SlatControl;
use Plaisio\Form\Control\SlatControlFactory;
use Plaisio\Form\Control\TableColumnControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\SlatJoint\TableColumnSlatJoint;
use Plaisio\Form\SlatJoint\TextSlatJoint;
use Plaisio\Kernel\Nub;
use Plaisio\Obfuscator\Obfuscator;
use Plaisio\Table\TableColumn\NumericTableColumn;
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
  private $wrdIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $lanId       The ID of the reference language.
   * @param int $targetLanId The ID of the target language.
   */
  public function __construct(int $lanId, int $targetLanId)
  {
    $ref_language = Nub::$nub->DL->abcBabelLanguageGetName($lanId, $lanId);
    $act_language = Nub::$nub->DL->abcBabelLanguageGetName($targetLanId, $lanId);

    // Create slat joint for table column with word ID.
    $table_column = new NumericTableColumn('ID', 'wrd_id');
    $this->addSlatJoint('wrd_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with the word in the reference language.
    $table_column = new TextTableColumn($ref_language, 'ref_wdt_text');
    $this->addSlatJoint('ref_wdt_text', new TableColumnSlatJoint($table_column));

    // Create slat joint with text form control for the word in the target language.
    $table_column = new TextSlatJoint($act_language);
    $this->addSlatJoint('act_wdt_text', $table_column);

    $this->wrdIdObfuscator = Nub::$nub->obfuscator::create('wrd');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(LouverControl $louverControl, array $data): SlatControl
  {
    $row = new SlatControl(Cast::toOptString($data['wrd_id']));
    $row->setObfuscator($this->wrdIdObfuscator);
    $louverControl->addFormControl($row);

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

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
