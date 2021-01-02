<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\Form\Control\CheckboxControl;
use Plaisio\Form\Control\SlatControl;
use Plaisio\Form\SlatJoint\CheckboxSlatJoint;
use Plaisio\Form\SlatJointFactory\SlatControlFactory;
use Plaisio\Kernel\Nub;
use Plaisio\Obfuscator\Obfuscator;
use Plaisio\Table\TableColumn\TextTableColumn;
use SetBased\Helper\Cast;

/**
 * Slat control factory for creating slat joints for updating enabled functionalities.
 */
class CompanyRoleUpdateFunctionalitiesSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The obfuscator for function IDs.
   *
   * @var Obfuscator
   */
  private Obfuscator $funIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    // Create slat joint for table column with name of module.
    $column = new TextTableColumn('Module', 'mdl_name');
    $column->setSortOrder(1);
    $this->addColumn($column);

    // Create slat joint for table column with name of functionality.
    $column = new TextTableColumn('Functionality', 'fun_name');
    $column->setSortOrder(2);
    $this->addColumn($column);

    // Create slat joint with checkbox for enabled or disabled page.
    $this->addSlatJoint(new CheckboxSlatJoint('fun_enabled', 'Enable'));

    $this->funIdObfuscator = Nub::$nub->obfuscator::create('fun');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(array $data): SlatControl
  {
    $row = new SlatControl(Cast::toOptString($data['fun_id']));
    $row->setObfuscator($this->funIdObfuscator);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'fun_enabled');
    $control->setValue($data['fun_enabled']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
