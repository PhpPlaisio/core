<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\Core\TableColumn\Company\RoleTableColumn;
use Plaisio\Core\TableColumn\System\RoleGroupTableColumn;
use Plaisio\Form\Control\CheckboxControl;
use Plaisio\Form\Control\LouverControl;
use Plaisio\Form\Control\SlatControl;
use Plaisio\Form\Control\SlatControlFactory;
use Plaisio\Form\Control\TableColumnControl;
use Plaisio\Form\SlatJoint\CheckboxSlatJoint;
use Plaisio\Form\SlatJoint\InvisibleSlatJoint;
use Plaisio\Form\SlatJoint\TableColumnSlatJoint;
use Plaisio\Kernel\Nub;
use Plaisio\Obfuscator\Obfuscator;
use Plaisio\Table\TableColumn\NumericTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;
use SetBased\Helper\Cast;

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

    $this->rolIdObfuscator = Nub::$nub->getObfuscator('rol');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(LouverControl $louverControl, array $data): SlatControl
  {
    $row = new SlatControl(Cast::toOptString($data['rol_id']));
    $row->setObfuscator($this->rolIdObfuscator);
    $louverControl->addFormControl($row);

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

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
