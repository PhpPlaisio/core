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
use Plaisio\Table\TableColumn\NumericTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;
use SetBased\Helper\Cast;

/**
 * Slat control factory for creating slat controls for updating the functionality that grant access to a page.
 */
class SystemPageUpdateFunctionalitiesSlatControlFactory extends SlatControlFactory
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
    // Create slat joint for table column with module ID.
    $table_column = new NumericTableColumn('ID', 'mdl_id');
    $this->addSlatJoint('mdl_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with name of module.
    $table_column = new TextTableColumn('Module', 'mdl_name');
    $this->addSlatJoint('mdl_name', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with functionality ID.
    $table_column = new NumericTableColumn('ID', 'fun_id');
    $this->addSlatJoint('fun_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with name of functionality.
    $table_column = new TextTableColumn('Functionality', 'fun_name');
    $this->addSlatJoint('fun_name', new TableColumnSlatJoint($table_column));

    // Create slat joint with checkbox for enabled or disabled page.
    $table_column = new CheckboxSlatJoint('Enable');
    $this->addSlatJoint('fun_checked', $table_column);

    $this->funIdObfuscator = Nub::$nub->getObfuscator('fun');
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
    $control = $this->createFormControl($row, 'mdl_id');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'mdl_name');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'fun_id');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'fun_name');
    $control->setValue($data);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'fun_checked');
    $control->setValue($data['fun_checked']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
