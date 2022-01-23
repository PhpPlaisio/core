<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Form\SlatControlFactory\SystemFunctionalityUpdatePagesSlatControlFactory;
use Plaisio\Core\Html\VerticalLayout;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Form\LouverForm;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

/**
 * Page for setting the pages that a functionality grants access.
 */
class FunctionalityUpdatePagesPage extends CoreCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var LouverForm
   */
  private LouverForm $form;

  /**
   * The ID of the functionality of which the pages that belong to it will be modified.
   *
   * @var int
   */
  private int $funId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->funId = Nub::$nub->cgi->getManId('fun', 'fun');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $funId The ID of the functionality.
   *
   * @return string
   */
  public static function getUrl(int $funId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_UPDATE_PAGES, 'pag');
    $url .= Nub::$nub->cgi->putId('fun', $funId, 'fun');

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

    if ($this->response===null)
    {
      $layout = new VerticalLayout();
      $layout->addBlock($this->htmlFunctionality())
             ->addBlock($this->form->htmlForm());
      $html = $layout->html();
    }
    else
    {
      $html = null;
    }

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    $pages = Nub::$nub->DL->abcSystemFunctionalityGetAvailablePages($this->funId);

    $this->form = new LouverForm();
    $this->form->setRowFactory(new SystemFunctionalityUpdatePagesSlatControlFactory())
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

        $this->response = new SeeOtherResponse(FunctionalityDetailsPage::getUrl($this->funId));
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

    foreach ($changes['data'] as $pagId => $dummy)
    {
      if ($values['data'][$pagId]['pag_enabled'])
      {
        Nub::$nub->DL->abcSystemFunctionalityInsertPage($this->funId, $pagId);
      }
      else
      {
        Nub::$nub->DL->abcSystemFunctionalityDeletePage($this->funId, $pagId);
      }
    }

    // Use brute force to proper profiles.
    Nub::$nub->DL->abcProfileProper();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns brief info about the functionality.
   */
  private function htmlFunctionality(): string
  {
    $details = Nub::$nub->DL->abcSystemFunctionalityGetDetails($this->funId, $this->lanId);

    $table = new CoreDetailTable();

    // Add row for the ID of the function.
    IntegerTableRow::addRow($table, 'ID', $details['fun_id']);

    // Add row for the module name to which the function belongs.
    TextTableRow::addRow($table, 'Module', $details['mdl_name']);

    // Add row for the name of the function.
    TextTableRow::addRow($table, 'Functionality', $details['fun_name']);

    return $table->htmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
