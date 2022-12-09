<?php

namespace Drupal\matterport_embed\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\matterport_embed\MatterportEmbedOptionSetInterface;

/**
 * @property MatterportEmbedOptionSetInterface $entity
 */
class MatterportEmbedOptionSetForm extends EntityForm
{

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state)
  {
    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Label for the matterport embed option set.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\matterport_embed\Entity\MatterportEmbedOptionSet::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $this->buildUrlParameters($form, $form_state);
    $this->buildAllows($form, $form_state);

    return $form;
  }

  public function buildUrlParameters(array &$form, FormStateInterface $form_state)
  {
    $form['url_parameters'] = [
      '#type' => 'fieldset',
      '#title' => t('URL Parameters'),
      '#tree' => TRUE,
      '#attributes' => [
        'id' => 'url-parameters-fieldset-wrapper',
      ],
    ];

    $form['url_parameters']['help'] = [
      '#type' => 'markup',
      '#markup' => $this->t('Read more about <a target="_blank" href=":href-url-parameters">URL Parameters</a>.', [
        ':href-url-parameters' => 'https://support.matterport.com/s/article/URL-Parameters',
      ])
    ];

    $form['url_parameters']['table'] = [
      '#type' => 'table',
      '#title' => 'title',
      '#tree' => TRUE,
      '#header' => [
        $this->t('Key'),
        $this->t('Value'),
        $this->t('Remove'),
      ],
      '#empty' => t('There are no url parameter yet.'),
    ];

    $is_first_run = $form_state->get('is_first_run') ?? TRUE;
    if ($is_first_run) {
      $form_state->set('is_first_run', FALSE);

      $settings = $this->entity->getSettings();
      $url_parameters = $settings['url_parameters'] ?? [];
      if (!is_array($url_parameters)) $url_parameters = [];

      $form_state->set('url_parameters', $url_parameters);
    }

    $url_parameters = $form_state->get('url_parameters') ?? [];

    for ($i = 0; $i < count($url_parameters); $i++) {
      $url_parameter = $url_parameters[$i];
      $form['url_parameters']['table'][$i]['key'] = [
        '#type' => 'textfield',
        '#default_value' => $url_parameter['key'] ?? ''
      ];
      $form['url_parameters']['table'][$i]['value'] = [
        '#type' => 'textfield',
        '#default_value' => $url_parameter['value'] ?? ''
      ];
    }

    $form['url_parameters']['actions'] = [
      '#type' => 'actions',
    ];
    $form['url_parameters']['actions']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
      '#submit' => ['::addUrlParameterItemCallback'],
      '#limit_validation_errors' => [],
      '#ajax' => [
        'callback' => '::addMoreCallback',
        'wrapper' => 'url-parameters-fieldset-wrapper',
      ],
    ];

    if (count($url_parameters) > 0) {
      $form['url_parameters']['actions']['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove last'),
        '#submit' => ['::removeUrlParameterItemCallback'],
        '#limit_validation_errors' => [],
        '#ajax' => [
          'callback' => '::addMoreCallback',
          'wrapper' => 'url-parameters-fieldset-wrapper'
        ],
      ];
    }
  }

  public function buildAllows(array &$form, FormStateInterface $form_state)
  {
    $form['iframe_allows'] = [
      '#type' => 'fieldset',
      '#title' => t('iframe Allow'),
      '#tree' => TRUE,
    ];

    $form['iframe_allows']['table'] = [
      '#type' => 'table',
      '#title' => 'title',
      '#tree' => TRUE,
      '#header' => [
        $this->t('Option'),
        $this->t('Enabled'),
      ]
    ];

    $settings = $this->entity->getSettings();
    $iframe_allows = $settings['iframe_allows'] ?? [];

    foreach ($this->getIframeAllowPossibleOptions() as $option) {
      $is_enabled = in_array($option, $iframe_allows);

      $form['iframe_allows']['table'][$option]['option'] = [
        '#type' => 'markup',
        '#markup' => $option
      ];
      $form['iframe_allows']['table'][$option]['is_enabled'] = [
        '#type' => 'checkbox',
        '#default_value' => $is_enabled
      ];
    }
  }

  public function getIframeAllowPossibleOptions(): array
  {
    return [
      'fullscreen',
      'vr'
    ];
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);

    $parameters = $form_state->getValue(['url_parameters', 'table']);
    if (!is_array($parameters)) $parameters = [];

    $keys = array_map(function ($item) {
      return $item['key'] ?? '';
    }, $parameters);

    $values = array_map(function ($item) {
      return $item['value'] ?? '';
    }, $parameters);

    $is_empty_key_found = count(array_filter($keys)) != count($keys);
    if ($is_empty_key_found) {
      $form_state->setError($form['url_parameters'], $this->t('Some URL Parameter key is empty!'));
    }

    $is_empty_value_found = count(array_filter($values, 'strlen')) != count($values);
    if ($is_empty_value_found) {
      $form_state->setError($form['url_parameters'], $this->t('Some URL Parameter value is empty!'));
    }

    $is_duplication_found = count(array_unique($keys)) != count($keys);
    if ($is_duplication_found) {
      $form_state->setError($form['url_parameters'], $this->t('Some URL Parameter key is duplicated!'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    $url_parameters = $form_state->getValue(['url_parameters', 'table']) ?? [];
    $iframe_allows = $form_state->getValue(['iframe_allows', 'table']) ?? [];
    $iframe_allows_setting = [];
    foreach ($iframe_allows as $key => $iframe_allow) {
      if ($iframe_allow['is_enabled']) {
        $iframe_allows_setting[] = $key;
      }
    }

    $this->entity->setSettings([
      'url_parameters' => $url_parameters,
      'iframe_allows' => $iframe_allows_setting,
    ]);

    $result = parent::save($form, $form_state);
    $this->messenger()->addStatus($this->t('Saved!'));
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

  public function addMoreCallback(array &$form, FormStateInterface $form_state)
  {
    return $form['url_parameters'];
  }

  public function addUrlParameterItemCallback(array &$form, FormStateInterface $form_state)
  {
    $array = $form_state->get('url_parameters') ?? [];
    $array[] = [];

    $form_state->set('url_parameters', $array);
    $form_state->setRebuild();
  }

  public function removeUrlParameterItemCallback(array &$form, FormStateInterface $form_state)
  {
    $array = $form_state->get('url_parameters') ?? [];
    if (!empty($array)) {
      array_pop($array);
      $form_state->set('url_parameters', $array);
      $form_state->setRebuild();
    }
  }

}
