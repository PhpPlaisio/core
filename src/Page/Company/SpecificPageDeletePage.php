<?php

namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Abc\Page\CorePage;

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

    $this->targetCmpId = self::getCgiId('cmp', 'cmp');
    $this->targetPagId = self::getCgiId('tar_pag', 'pag');
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
    $url = self::putCgiId('pag', C::PAG_ID_COMPANY_SPECIFIC_PAGE_DELETE, 'pag');
    $url .= self::putCgiId('cmp', $targetCmpId, 'cmp');
    $url .= self::putCgiId('tar_pag', $targetPagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Deletes a company specific page.
   */
  public function echoPage(): void
  {
    Abc::$DL->abcCompanySpecificPageDelete($this->targetCmpId, $this->targetPagId);

    HttpHeader::redirectSeeOther(SpecificPageOverviewPage::getUrl($this->targetCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
