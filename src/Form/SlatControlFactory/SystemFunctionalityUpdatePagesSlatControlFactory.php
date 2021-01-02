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
 * Slat control factory for creating slat controls for updating the pages that a functionality grants access to.
 */
class SystemFunctionalityUpdatePagesSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The obfuscator for page IDs.
   *
   * @var Obfuscator
   */
  private Obfuscator $pagIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    // Create slat joint for table column with page ID.
    $this->addColumn(new NumberTableColumn('ID', 'pag_id'));

    // Create slat joint for table column with name of class.
    $this->addColumn(new TextTableColumn('Name', 'pag_class'));

    // Create slat joint with checkbox for enabled or disabled page.
    $this->addSlatJoint(new CheckboxSlatJoint('pag_enabled', 'Enable'));

    $this->pagIdObfuscator = Nub::$nub->obfuscator::create('pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(array $data): SlatControl
  {
    $row = new SlatControl(Cast::toOptString($data['pag_id']));
    $row->setObfuscator($this->pagIdObfuscator);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'pag_enabled');
    $control->setValue($data['pag_enabled']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
