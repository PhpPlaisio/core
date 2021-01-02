<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\Core\TableColumn\Company\RoleTableColumn;
use Plaisio\Core\TableColumn\System\RoleGroupTableColumn;
use Plaisio\Form\Control\CheckboxControl;
use Plaisio\Form\Control\SlatControl;
use Plaisio\Form\SlatJoint\CheckboxSlatJoint;
use Plaisio\Form\SlatJoint\InvisibleSlatJoint;
use Plaisio\Form\SlatJointFactory\SlatControlFactory;
use Plaisio\Kernel\Nub;
use Plaisio\Obfuscator\Obfuscator;
use Plaisio\Table\TableColumn\NumberTableColumn;
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
  private Obfuscator $rolIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    // Create slat joint for table column with page ID.
    $this->addSlatJoint(new InvisibleSlatJoint('cmp_id'));

    $this->addColumn(new NumberTableColumn('ID', 'cmp_id'));

    // Create slat joint for table column with name of class.
    $this->addColumn(new TextTableColumn('Company', 'cmp_abbr'));

    // Create slat joint for table column with ID and name of role group.
    $this->addColumn(new RoleGroupTableColumn('Role Group'));

    // Create slat joint for table column with ID and name of role.
    $this->addColumn(new RoleTableColumn('Role'));

    // Create slat joint with checkbox for enabled or disabled page.
    $this->addSlatJoint(new CheckboxSlatJoint('rol_enabled', 'Grant'));

    $this->rolIdObfuscator = Nub::$nub->obfuscator::create('rol');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(array $data): SlatControl
  {
    $row = new SlatControl(Cast::toOptString($data['rol_id']));
    $row->setObfuscator($this->rolIdObfuscator);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'rol_enabled');
    $control->setValue($data['rol_enabled']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
