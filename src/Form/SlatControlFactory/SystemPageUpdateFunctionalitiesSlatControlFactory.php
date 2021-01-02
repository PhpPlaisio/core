<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\Form\Control\CheckboxControl;
use Plaisio\Form\Control\SlatControl;
use Plaisio\Form\SlatJointFactory\SlatControlFactory;
use Plaisio\Form\SlatJoint\CheckboxSlatJoint;
use Plaisio\Kernel\Nub;
use Plaisio\Obfuscator\Obfuscator;
use Plaisio\Table\TableColumn\NumberTableColumn;
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
  private Obfuscator $funIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    // Create slat joint for table column with module ID.
    $this->addColumn(new NumberTableColumn('ID', 'mdl_id'));

    // Create slat joint for table column with name of module.
    $this->addColumn(new TextTableColumn('Module', 'mdl_name'));

    // Create slat joint for table column with functionality ID.
    $this->addColumn(new NumberTableColumn('ID', 'fun_id'));

    // Create slat joint for table column with name of functionality.
    $this->addColumn(new TextTableColumn('Functionality', 'fun_name'));

    // Create slat joint with checkbox for enabled or disabled page.
    $this->addSlatJoint(new CheckboxSlatJoint('fun_checked', 'Enable'));

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
    $control = $this->createFormControl($row, 'fun_checked');
    $control->setValue($data['fun_checked']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
