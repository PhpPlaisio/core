<?php
declare(strict_types=1);

namespace Plaisio\Core\Html;

use Plaisio\Helper\Html;

/**
 * Helper class for generating HTML code for layout.
 */
abstract class Layout
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The class of this layout element.
   *
   * @var string
   */
  protected string $class;

  /**
   * The structure of the block elements in this layout element.
   *
   * @var string
   */
  private string $blocks = '';

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an HTML block to this layout element.
   *
   * @param string|null $html The HTML code of the block.
   *
   * @return $this
   */
  public function addBlock(?string $html): self
  {
    $this->blocks .= $html;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds HTML blocks to this layout element.
   *
   * @param string[]|null $blocks The HTML code of the blocks.
   *
   * @return $this
   */
  public function addBlocks(?array $blocks): self
  {
    if (!empty($blocks))
    {
      foreach ($blocks as $block)
      {
        $this->addBlock($block);
      }
    }

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the structure of this layout element.
   *
   * @return string
   */
  public function html(): string
  {
    return Html::htmlNested(['tag'  => 'div',
                             'attr' => ['class' => $this->class],
                             'html' => $this->blocks]);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
