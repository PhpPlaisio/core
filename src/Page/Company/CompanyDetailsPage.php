<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Core\TableAction\Company\CompanyUpdateTableAction;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

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
  private array $details;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->details = Nub::$nub->DL->abcCompanyGetDetails($this->targetCmpId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $targetCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_DETAILS, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->showCompanyDetails();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Show the details of the company.
   */
  private function showCompanyDetails(): void
  {
    $table = new CoreDetailTable();

    // Add table action for update the company details.
    $table->addTableAction('default', new CompanyUpdateTableAction($this->targetCmpId));

    // Show company ID.
    IntegerTableRow::addRow($table, 'ID', $this->details['cmp_id']);

    // Show company abbreviation.
    TextTableRow::addRow($table, 'Abbreviation', $this->details['cmp_abbr']);

    // Show label.
    TextTableRow::addRow($table, 'Label', $this->details['cmp_label']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

