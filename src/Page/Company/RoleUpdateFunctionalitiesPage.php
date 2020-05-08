<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Form\Control\CoreButtonControl;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Form\SlatControlFactory\CompanyRoleUpdateFunctionalitiesSlatControlFactory;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\LouverControl;
use Plaisio\Form\Control\SubmitControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Page for modifying the granted functionalities to a role.
 */
class RoleUpdateFunctionalitiesPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the role.
   *
   * @var array
   */
  private $details;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  private $form;

  /**
   * The ID of the role.
   *
   * @var int
   */
  private $rolId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->rolId = Nub::$nub->cgi->getManId('rol', 'rol');

    $this->details = Nub::$nub->DL->abcCompanyRoleGetDetails($this->targetCmpId, $this->rolId, $this->lanId);
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
    // Get all available functionalities.
    $pages = Nub::$nub->DL->abcCompanyRoleGetAvailableFunctionalities($this->targetCmpId, $this->rolId, $this->lanId);

    // Create form.
    $this->form = new CoreForm();

    // Add field set.
    $field_set = new FieldSet('');
    $this->form->addFieldSet($field_set);

    // Create factory.
    $factory = new CompanyRoleUpdateFunctionalitiesSlatControlFactory();
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl();
    $submit = new SubmitControl('submit');
    $submit->setMethod('handleForm');
    $submit->setValue(Nub::$nub->babel->getWord(C::WRD_ID_BUTTON_UPDATE));
    $button->addFormControl($submit);

    // Put everything together in a LouverControl.
    $louver = new LouverControl('data');
    $louver->addClass('overview_table');
    $louver->setRowFactory($factory);
    $louver->setFooterControl($button);
    $louver->setData($pages);
    $louver->populate();

    // Add the lover control to the form.
    $field_set->addFormControl($louver);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Grants and revokes functionalities from the role.
   */
  private function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    foreach ($changes['data'] as $fun_id => $dummy)
    {
      if ($values['data'][$fun_id]['fun_enabled'])
      {
        Nub::$nub->DL->abcCompanyRoleInsertFunctionality($this->targetCmpId, $this->rolId, $fun_id);
      }
      else
      {
        Nub::$nub->DL->abcCompanyRoleDeleteFunctionality($this->targetCmpId, $this->rolId, $fun_id);
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

    $this->response = new SeeOtherResponse(RoleDetailsPage::getUrl($this->targetCmpId, $this->rolId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

