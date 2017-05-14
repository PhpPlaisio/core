<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\SlatControlFactory;

use SetBased\Abc\Abc;
use SetBased\Abc\Core\TableColumn\Company\RoleTableColumn;
use SetBased\Abc\Core\TableColumn\System\RoleGroupTableColumn;
use SetBased\Abc\Form\Control\CheckboxControl;
use SetBased\Abc\Form\Control\SlatControl;
use SetBased\Abc\Form\Control\SlatControlFactory;
use SetBased\Abc\Form\Control\TableColumnControl;
use SetBased\Abc\Form\SlatJoint\CheckboxSlatJoint;
use SetBased\Abc\Form\SlatJoint\InvisibleSlatJoint;
use SetBased\Abc\Form\SlatJoint\TableColumnSlatJoint;
use SetBased\Abc\Obfuscator\Obfuscator;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat control factory for creating slat controls for updating the pages that a functionality grants access to.
 */
class SystemFunctionalityUpdateRolesSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The obfuscator for role IDs.
   *
   * @var Obfuscator
   */
  private $rolIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Create slat joint for table column with page ID.
    $slat_joint = new InvisibleSlatJoint();
    $this->addSlatJoint('cmp_id', $slat_joint);

    $table_column = new NumericTableColumn('ID', 'cmp_id');
    $this->addSlatJoint('cmp_id_column', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with name of class.
    $table_column = new TextTableColumn('Company', 'cmp_abbr');
    $this->addSlatJoint('cmp_abbr', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with ID and name of role group.
    $table_column = new RoleGroupTableColumn('Role Group');
    $this->addSlatJoint('role_group', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with ID and name of role.
    $table_column = new RoleTableColumn('Role');
    $this->addSlatJoint('role', new TableColumnSlatJoint($table_column));

    // Create slat joint with checkbox for enabled or disabled page.
    $slat_joint = new CheckboxSlatJoint('Grant');
    $this->addSlatJoint('rol_enabled', $slat_joint);

    $this->rolIdObfuscator = Abc::getObfuscator('rol');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function createRow($louverControl, $data)
  {
    /** @var SlatControl $row */
    $row = $louverControl->addFormControl(new SlatControl($data['rol_id']));
    $row->setObfuscator($this->rolIdObfuscator);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'cmp_id');
    $control->setValue($data['cmp_id']);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'cmp_id_column');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'cmp_abbr');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'role_group');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'role');
    $control->setValue($data);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'rol_enabled');
    $control->setValue($data['rol_enabled']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
