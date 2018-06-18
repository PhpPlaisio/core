<?php

namespace SetBased\Abc\Core\Form\SlatControlFactory;

use SetBased\Abc\Abc;
use SetBased\Abc\Form\Control\CheckboxControl;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Form\Control\SlatControl;
use SetBased\Abc\Form\Control\SlatControlFactory;
use SetBased\Abc\Form\Control\TableColumnControl;
use SetBased\Abc\Form\SlatJoint\CheckboxSlatJoint;
use SetBased\Abc\Form\SlatJoint\TableColumnSlatJoint;
use SetBased\Abc\Obfuscator\Obfuscator;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

/**
 * Slat control factory for creating slat controls for updating the pages that a functionality grants access to.
 */
class SystemModuleUpdateCompaniesSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The obfuscator for company IDs.
   *
   * @var Obfuscator
   */
  private $cmpIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Create slat joint for table column with company ID.
    $table_column = new NumericTableColumn('ID', 'cmp_id');
    $this->addSlatJoint('cmp_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with abbr of the company.
    $table_column = new TextTableColumn('Name', 'cmp_abbr');
    $table_column->setSortOrder(1);
    $this->addSlatJoint('cmp_abbr', new TableColumnSlatJoint($table_column));

    // Create slat joint with checkbox for granting or revoking the module.
    $table_column = new CheckboxSlatJoint('Grant');
    $this->addSlatJoint('mdl_granted', $table_column);

    $this->cmpIdObfuscator = Abc::getObfuscator('cmp');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(LouverControl $louverControl, array $data): SlatControl
  {
    $row = new SlatControl($data['cmp_id']);
    $row->setObfuscator($this->cmpIdObfuscator);
    $louverControl->addFormControl($row);;

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'cmp_id');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'cmp_abbr');
    $control->setValue($data);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'mdl_granted');
    $control->setValue($data['mdl_granted']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
