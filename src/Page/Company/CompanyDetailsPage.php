<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\TableAction\Company\CompanyUpdateTableAction;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with details of a company.
 */
class CompanyDetailsPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the target company.
   *
   * @var array
   */
  private $details;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->details = Abc::$DL->abcCompanyGetDetails($this->targetCmpId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $targetCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl($targetCmpId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_COMPANY_DETAILS, 'pag');
    $url .= self::putCgiId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showCompanyDetails();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Show the details of the company.
   */
  private function showCompanyDetails()
  {
    $table = new CoreDetailTable();

    // Add table action for update the company details.
    $table->addTableAction('default', new CompanyUpdateTableAction($this->targetCmpId));

    // Show company ID.
    NumericTableRow::addRow($table, 'ID', $this->details['cmp_id'], '%d');

    // Show company abbreviation.
    TextTableRow::addRow($table, 'Abbreviation', $this->details['cmp_abbr']);

    // Show label.
    TextTableRow::addRow($table, 'Label', $this->details['cmp_label']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

