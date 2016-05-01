<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Babel;

use SetBased\Abc\Core\Page\Babel\WordTranslatePage;
use SetBased\Abc\Core\TableColumn\IconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon linking to page for translating a single word.
 */
class WordTranslateIconTableColumn extends IconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target language.
   *
   * @var int
   */
  private $actLanId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $targetLanId The ID of the target language.
   */
  public function __construct($targetLanId)
  {
    parent::__construct();

    $this->iconUrl  = ICON_SMALL_BABEL_FISH;
    $this->altValue = 'translate';
    $this->actLanId = $targetLanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($row)
  {
    return WordTranslatePage::getUrl($row['wrd_id'], $this->actLanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
