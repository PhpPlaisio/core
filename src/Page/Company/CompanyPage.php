<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\Core\Page\PlaisioCorePage;
use Plaisio\Helper\Html;
use Plaisio\Kernel\Nub;

/**
 * Abstract parent page for pages about companies.
 */
abstract class CompanyPage extends PlaisioCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the company of which data is shown on this page.
   *
   * @var array
   */
  protected array $companyDetails;

  /**
   * The ID of the company of which data is shown on this page (i.e. the target company).
   *
   * @var int
   */
  protected int $targetCmpId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->targetCmpId = Nub::$nub->cgi->getManId('cmp', 'cmp');

    $this->companyDetails = Nub::$nub->DL->abcCompanyGetDetails($this->targetCmpId);

    Nub::$nub->assets->appendPageTitle($this->companyDetails['cmp_abbr']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL to a child page of this page.
   *
   * @param int      $pagId       The ID of the child page.
   * @param int|null $targetCmpId The ID of the target company.
   *
   * @return string The URL.
   */
  public static function getChildUrl(int $pagId, ?int $targetCmpId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', $pagId, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows brief information about the target company.
   */
  protected function echoDashboard(): void
  {
    echo '<div id="dashboard">';
    echo '<div id="info">';

    echo '<div id="info0">';
    echo Html::txt2Html($this->companyDetails['cmp_abbr']);
    echo '<br/>';
    echo '<br/>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getTabUrl(int $pagId): ?string
  {
    return self::getChildUrl($pagId, $this->targetCmpId);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
