<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Abstract parent page for inserting and updating details of a role for the target company.
 */
abstract class RoleBasePage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the word for the text of the submit button of the form shown on this page.
   *
   * @var int
   */
  protected $buttonWrdId;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $form;

  /**
   * The ID of the role that is been inserted or updated.
   *
   * @var int
   */
  protected $rolId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a role of the target company.
   *
   * @return void
   */
  abstract protected function databaseAction(): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->createForm();
    $this->loadValues();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the initial values of the form shown on this page.
   *
   * @return void
   */
  abstract protected function loadValues(): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    $this->form = new CoreForm();

    // Input for role group.
    $roleGroups = Nub::$DL->abcSystemRoleGroupGetAll($this->lanId);
    $input      = new SelectControl('rlg_id');
    $input->setOptions($roleGroups, 'rlg_id', 'rlg_name');
    $this->form->addFormControl($input, 'Role Group', true);

    // Input for name.
    $input = new TextControl('rol_name');
    $input->setAttrMaxLength(C::LEN_ROL_NAME);
    $this->form->addFormControl($input, 'Name', true);

    // Input for weight.
    $input = new TextControl('rol_weight');
    $input->setAttrMaxLength(C::LEN_ROL_WEIGHT);
    $this->form->addFormControl($input, 'Weight');

    // Input for label.
    $input = new TextControl('rol_label');
    $input->setAttrMaxLength(C::LEN_ROL_LABEL);
    $this->form->addFormControl($input, 'Label');

    // Create a submit button.
    $this->form->addSubmitButton($this->buttonWrdId, 'handleForm');
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
    };
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

