<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\Form\Control\CheckboxControl;
use Plaisio\Form\Control\LouverControl;
use Plaisio\Form\Control\SlatControl;
use Plaisio\Form\Control\SlatControlFactory;
use Plaisio\Form\Control\TableColumnControl;
use Plaisio\Form\SlatJoint\CheckboxSlatJoint;
use Plaisio\Form\SlatJoint\TableColumnSlatJoint;
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
  private $funIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Create slat joint for table column with name of module.
    $table_column = new TextTableColumn('Module', 'mdl_name');
    $col          = $this->addSlatJoint('mdl_name', new TableColumnSlatJoint($table_column));
    $col->setSortOrder(1);

    // Create slat joint for table column with name of functionality.
    $table_column = new TextTableColumn('Functionality', 'fun_name');
    $col          = $this->addSlatJoint('fun_name', new TableColumnSlatJoint($table_column));
    $col->setSortOrder(2);

    // Create slat joint with checkbox for enabled or disabled page.
    $table_column = new CheckboxSlatJoint('Enable');
    $this->addSlatJoint('fun_enabled', $table_column);

    $this->funIdObfuscator = Nub::$nub->obfuscator::create('fun');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(LouverControl $louverControl, array $data): SlatControl
  {
    $row = new SlatControl(Cast::toOptString($data['fun_id']));
    $row->setObfuscator($this->funIdObfuscator);
    $louverControl->addFormControl($row);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'mdl_name');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'fun_name');
    $control->setValue($data);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'fun_enabled');
    $control->setValue($data['fun_enabled']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
