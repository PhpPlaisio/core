<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Form\SlatControlFactory\CompanyRoleUpdateFunctionalitiesSlatControlFactory;
use Plaisio\Form\LouverForm;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Page for modifying the granted functionalities to a role.
 */
class RoleUpdateFunctionalitiesPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var LouverForm
   */
  private LouverForm $form;

  /**
   * The ID of the role.
   *
   * @var int
   */
  private int $rolId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->rolId = Nub::$nub->cgi->getManId('rol', 'rol');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $targetCmpId The ID of the target company
   * @param int $rolId       The ID of role to be modified.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId, int $rolId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_ROLE_UPDATE_FUNCTIONALITIES, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');
    $url .= Nub::$nub->cgi->putId('rol', $rolId, 'rol');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $this->createForm();
    $this->executeForm();

    return $this->form->htmlFormEmptyIfValid();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    $pages = Nub::$nub->DL->abcCompanyRoleGetAvailableFunctionalities($this->targetCmpId, $this->rolId, $this->lanId);

    $this->form = new LouverForm();
    $this->form->setRowFactory(new CompanyRoleUpdateFunctionalitiesSlatControlFactory())
               ->addSubmitButton(C::WRD_ID_BUTTON_UPDATE, 'handleForm')
               ->populate($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm(): void
  {
    $method = $this->form->execute();
    switch ($method)
    {
      case 'handleForm':
        $this->handleForm();

        $this->response = new SeeOtherResponse(RoleDetailsPage::getUrl($this->targetCmpId, $this->rolId));
        break;

      default:
        $this->form->defaultHandler($method);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes['data'])) return;

    foreach ($changes['data'] as $funId => $dummy)
    {
      if ($values['data'][$funId]['fun_enabled'])
      {
        Nub::$nub->DL->abcCompanyRoleInsertFunctionality($this->targetCmpId, $this->rolId, $funId);
      }
      else
      {
        Nub::$nub->DL->abcCompanyRoleDeleteFunctionality($this->targetCmpId, $this->rolId, $funId);
      }
    }

    // Use brute force to proper profiles.
    Nub::$nub->DL->abcProfileProper();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

