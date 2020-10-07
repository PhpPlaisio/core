<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Form\SlatControlFactory\CompanyModulesUpdateSlatControlFactory;
use Plaisio\Form\LouverForm;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Page for enabling and disabling the modules for a company.
 */
class ModuleUpdatePage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var LouverForm
   */
  private LouverForm $form;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $targetCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_MODULE_UPDATE, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    // Get all available modules.
    $modules = Nub::$nub->DL->abcCompanyModuleGetAllAvailable($this->targetCmpId, $this->lanId);

    $this->form = new LouverForm();
    $this->form->setFactory(new CompanyModulesUpdateSlatControlFactory())
               ->setData($modules)
               ->addSubmitButton(C::WRD_ID_BUTTON_UPDATE, 'handleForm')
               ->populate();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Handles the form submit.
   */
  private function databaseAction(): void
  {
    $values  = $this->form->getValues();
    $changes = $this->form->getChangedControls();

    // If no changes are submitted return immediately.
    if (empty($changes)) return;

    foreach ($changes['data'] as $mdl_id => $dummy)
    {
      if ($values['data'][$mdl_id]['mdl_enabled'])
      {
        Nub::$nub->DL->abcCompanyModuleEnable($this->targetCmpId, $mdl_id);
      }
      else
      {
        Nub::$nub->DL->abcCompanyModuleDisable($this->targetCmpId, $mdl_id);
      }
    }

    // Use brute force to proper profiles.
    Nub::$nub->DL->abcProfileProper();
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
    $this->databaseAction();

    $this->response = new SeeOtherResponse(ModuleOverviewPage::getUrl($this->targetCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
