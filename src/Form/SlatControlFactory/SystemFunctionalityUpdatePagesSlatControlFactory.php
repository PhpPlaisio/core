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
  private $pagIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    // Create slat joint for table column with page ID.
    $table_column = new NumericTableColumn('ID', 'pag_id');
    $this->addSlatJoint('pag_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with name of class.
    $table_column = new TextTableColumn('Name', 'pag_class');
    $this->addSlatJoint('pag_class', new TableColumnSlatJoint($table_column));

    // Create slat joint with checkbox for enabled or disabled page.
    $table_column = new CheckboxSlatJoint('Enable');
    $this->addSlatJoint('pag_enabled', $table_column);

    $this->pagIdObfuscator = Nub::$nub->getObfuscator('pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(LouverControl $louverControl, array $data): SlatControl
  {
    $row = new SlatControl(Cast::toOptString($data['pag_id']));
    $row->setObfuscator($this->pagIdObfuscator);
    $louverControl->addFormControl($row);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'pag_id');
    $control->setValue($data);

    /** @var TableColumnControl $control */
    $control = $this->createFormControl($row, 'pag_class');
    $control->setValue($data);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'pag_enabled');
    $control->setValue($data['pag_enabled']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
