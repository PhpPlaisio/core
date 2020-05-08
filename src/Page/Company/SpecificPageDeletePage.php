<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Kernel\Nub;
use Plaisio\Page\CorePage;
use Plaisio\Response\Response;
use Plaisio\Response\SeeOtherResponse;

/**
 * Page for deleting a company specific page that overrides a standard page.
 */
class SpecificPageDeletePage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target company.
   *
   * @var int
   */
  private $targetCmpId;

  /**
   * The ID of the target page.
   *
   * @var int
   */
  private $targetPagId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->targetCmpId = Nub::$nub->cgi->getManId('cmp', 'cmp');
    $this->targetPagId = Nub::$nub->cgi->getManId('tar_pag', 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $targetCmpId The ID of the target company.
   * @param int $targetPagId The ID of the page the must be deleted.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId, int $targetPagId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_SPECIFIC_PAGE_DELETE, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');
    $url .= Nub::$nub->cgi->putId('tar_pag', $targetPagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Deletes a company specific page.
   */
  public function handleRequest(): Response
  {
    Nub::$nub->DL->abcCompanySpecificPageDelete($this->targetCmpId, $this->targetPagId);

    $this->response = new SeeOtherResponse(SpecificPageOverviewPage::getUrl($this->targetCmpId));

    return $this->response;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
