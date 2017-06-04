<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Babel;

use SetBased\Abc\Core\Page\Babel\WordGroupDetailsPage;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\TableColumn\DualTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A dual table column with the ID and name of a word group.
 */
class WordGroupTableColumn extends DualTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target language.
   *
   * @var int
   */
  private $lanIdTarget;

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * Object constructor.
   *
   * @param string|int|null $headerText  The header of this column.
   * @param int             $lanIdTarget The ID of the target language.
   */
  public function __construct($headerText, $lanIdTarget)
  {
    parent::__construct('numeric', 'text');

    $this->headerText  = $headerText;
    $this->lanIdTarget = $lanIdTarget;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlCell($row)
  {
    $url = WordGroupDetailsPage::getUrl($row['wdg_id'], $this->lanIdTarget);

    $ret = '<td class="number link">';
    $ret .= Html::generateElement('a', ['href' => $url], $row['wdg_id']);
    $ret .= '</td>';

    $ret .= Html::generateElement('td', [], $row['wdg_name']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
