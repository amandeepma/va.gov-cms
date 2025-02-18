<?php

namespace Drupal\va_gov_backend\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Plugin implementation of the 'tooltip_textfield' widget.
 *
 * @FieldWidget(
 *   id = "tooltip_textfield",
 *   label = @Translation("Tooltip textfield"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class TooltipTextFieldFormatter extends WidgetBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'size' => 60,
      'placeholder' => '',
      'tooltip_text' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['size'] = [
      '#type' => 'number',
      '#title' => $this->t('Size of textfield'),
      '#default_value' => $this->getSetting('size'),
      '#required' => TRUE,
      '#min' => 1,
    ];
    $element['placeholder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Placeholder'),
      '#default_value' => $this->getSetting('placeholder'),
      '#description' => $this->t('Text that will be shown inside the field until a value is entered. This hint is usually a sample value or a brief description of the expected format.'),
    ];
    $element['tooltip_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Tooltip text'),
      '#default_value' => $this->getSetting('tooltip_text'),
      '#description' => $this->t('Text that will be shown inside the tooltip on hover.'),
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = $this->t('Textfield size: @size', ['@size' => $this->getSetting('size')]);
    $placeholder = $this->getSetting('placeholder');
    if (!empty($placeholder)) {
      $summary[] = $this->t('Placeholder: @placeholder', ['@placeholder' => $placeholder]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['value'] = $element + [
      // Create and format the tooltip.
      // Uses aria-label for accessibility.
      '#prefix' => Markup::create('
      <div class="
      tooltip-container-div
      name-button-container-div">
      <button
      aria-label="tooltip"
      type="button"
      class="css-tooltip-toggle"
      role="presentation"
      data-tippy-animate="fade"
      data-tippy-size="large"
      data-tippy-pos="right"
      data-tippy="' . $this->getSetting('tooltip_text') . '"
      aria-label="' . $this->getSetting('tooltip_text') . '"
      value="' . $this->getSetting('tooltip_text') . '">
      </div>
      </button>'),

      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
      '#size' => $this->getSetting('size'),
      '#placeholder' => $this->getSetting('placeholder'),
      '#maxlength' => $this->getFieldSetting('max_length'),
      '#attributes' => ['class' => ['js-text-full', 'text-full']],
    ];

    return $element;
  }

}
