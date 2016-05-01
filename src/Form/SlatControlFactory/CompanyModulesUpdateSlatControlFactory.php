<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\SlatControlFactory;

use SetBased\Abc\Abc;
use SetBased\Abc\Form\Control\CheckboxControl;
use SetBased\Abc\Form\Control\SlatControl;
use SetBased\Abc\Form\Control\SlatControlFactory;
use SetBased\Abc\Form\Control\TableColumnControl;
use SetBased\Abc\Form\SlatJoint\CheckboxSlatJoint;
use SetBased\Abc\Form\SlatJoint\TableColumnSlatJoint;
use SetBased\Abc\Obfuscator\Obfuscator;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat control factory for creating slat controls for enabling or disabling active modules of a company.
 */
class CompanyModulesUpdateSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The obfuscator for module IDs.
   *
   * @var Obfuscator
   */
  private $mdlIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Create slat joint for table column with name of module.
    $table_column = new TextTableColumn('Module', 'mdl_name');
    $this->addSlatJoint('mdl_name', new TableColumnSlatJoint($table_column));

    // Create slat joint with checkbox for enabled or disabled module.
    $table_column = new CheckboxSlatJoint('Enable');
    $this->addSlatJoint('mdl_enabled', $table_column);

    $this->mdlIdObfuscator = Abc::getObfuscator('mdl');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function createRow($louverControl, $data)
  {
    /** @var SlatControl $row */
    $row = $louverControl->addFormControl(new SlatControl($data['mdl_id']));
    $row->setObfuscator($this->mdlIdObfuscator);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'mdl_name');
    $control->setValue($data);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'mdl_enabled');
    $control->setValue($data['mdl_enabled']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
