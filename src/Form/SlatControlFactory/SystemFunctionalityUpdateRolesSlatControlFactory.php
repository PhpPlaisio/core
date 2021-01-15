<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\Core\TableColumn\Company\RoleTableColumn;
use Plaisio\Core\TableColumn\System\RoleGroupTableColumn;
use Plaisio\Form\Control\CheckboxControl;
use Plaisio\Form\Control\InvisibleControl;
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
 * Slat control factory for creating slat controls for updating the roles a functionality is granted to.
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

    // Invisible cmp_id.
    $this->addSlatJoint(new InvisibleSlatJoint('cmp_id'));

    // Show company ID.
    $this->addColumn(new NumberTableColumn('ID', 'cmp_id'));

    // Show company abbreviation.
    $this->addColumn(new TextTableColumn('Company', 'cmp_abbr'));

    // Show role group.
    $this->addColumn(new RoleGroupTableColumn('Role Group'));

    // Show role.
    $this->addColumn(new RoleTableColumn('Role'));

    // Checkbox for granting functionality.
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

    // Invisible cmp_id.
    /** @var InvisibleControl $control */
    $control = $this->createFormControl($row, 'cmp_id');
    $control->setValue($data['cmp_id']);

    // Checkbox for granting functionality.
    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'rol_enabled');
    $control->setValue($data['rol_enabled']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
