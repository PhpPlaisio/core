<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\Form\Control\CheckboxControl;
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
  private Obfuscator $mdlIdObfuscator;

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

    $this->mdlIdObfuscator = Nub::$nub->obfuscator::create('mdl');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(array $data): SlatControl
  {
    $row = new SlatControl(Cast::toOptString($data['mdl_id']));
    $row->setObfuscator($this->mdlIdObfuscator);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'mdl_name');
    $control->setValue($data);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'mdl_enabled');
    $control->setValue($data['mdl_enabled']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
