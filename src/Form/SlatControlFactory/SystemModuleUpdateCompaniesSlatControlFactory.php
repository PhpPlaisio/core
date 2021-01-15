<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\SlatControlFactory;

use Plaisio\Form\Control\CheckboxControl;
use Plaisio\Form\Control\SlatControl;
use Plaisio\Form\SlatJoint\CheckboxSlatJoint;
use Plaisio\Form\SlatJointFactory\SlatControlFactory;
use Plaisio\Kernel\Nub;
use Plaisio\Obfuscator\Obfuscator;
use Plaisio\Table\TableColumn\NumberTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;
use SetBased\Helper\Cast;

/**
 * Slat control factory for creating slat controls for updating the pages that a functionality grants access to.
 */
class SystemModuleUpdateCompaniesSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The obfuscator for company IDs.
   *
   * @var Obfuscator
   */
  private Obfuscator $cmpIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    // Create slat joint for table column with company ID.
    $this->addColumn(new NumberTableColumn('ID', 'cmp_id'));

    // Create slat joint for table column with abbr of the company.
    $column = new TextTableColumn('Name', 'cmp_abbr');
    $column->setSortOrder(1);
    $this->addColumn($column);

    // Create slat joint with checkbox for granting or revoking the module.
    $this->addSlatJoint(new CheckboxSlatJoint('mdl_granted', 'Grant'));

    $this->cmpIdObfuscator = Nub::$nub->obfuscator::create('cmp');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function createRow(array $data): SlatControl
  {
    $row = new SlatControl(Cast::toOptString($data['cmp_id']));
    $row->setObfuscator($this->cmpIdObfuscator);

    /** @var CheckboxControl $control */
    $control = $this->createFormControl($row, 'mdl_granted');
    $control->setValue($data['mdl_granted']);

    return $row;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
