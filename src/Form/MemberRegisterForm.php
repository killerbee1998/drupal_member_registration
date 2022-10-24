<?php

namespace Drupal\member_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;
use \Exception;
use Drupal\Core\Url;
use Drupal\Core\Routing;

class MemberRegisterForm extends FormBase {

  const MEMBER_REGISTRATION_PAGE = 'member_registration_page:values';

  public function getFormId() {
    return 'member_register_page';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = array(
      '#attributes' => array('enctype' => 'multipart/form-data'),
    );

    $validators = array(
      'file_validate_extensions' => array('pdf'),
    );

    $form['member_type'] = array(
      '#type' => 'radios',
      '#prefix' => '<h1 class="text-danger
      ">Allaince Francaise de Dhaka <h1> <br> <h1> Membership Registration Form </h1>',
      // '#suffix' => '</div>',
      '#title' => ('Select Membership Type:'),
      '#options' => array(
        'New Member' => $this->t('New Member'),
        'Membership Renewal' => $this->t('Membership Renewal'),
      ),
      '#attributes' => [
        'name' => 'name_member_type',
      ]
    );

    $form['member_nidpassport'] = array(
      '#type' => 'managed_file',
      '#name' => 'nidpassport',
      '#title' =>  $this->t('NID/Passport'),
      '#size' => 20,
      '#description' =>  $this->t('PDF format only'),
      '#upload_validators' => $validators,
      '#upload_location' => 'public://my_files/',
      // TODO implement seperate conditional for managed_file type
    );

    $form['member_utilitybill'] = array(
      '#type' => 'managed_file',
      '#name' => 'utilitybill',
      '#title' =>  $this->t('Utility Bill'),
      '#size' => 20,
      '#description' =>  $this->t('PDF format only'),
      '#upload_validators' => $validators,
      '#upload_location' => 'public://',
      '#button_type' => 'brn btn-primary rounded p-2',
      // TODO implement seperate conditional for managed_file type
    );


    $form['member_renewal'] = array(
      '#type' => 'textfield',
      '#prefix' => '<div class="form-group">',
      '#suffix' => '</div>',
      '#title' => $this->t('Old Membership Number: '),
      '#attributes' => [
        'id' => 'renewal_number',
      ],
      '#states' => [
        'visible' => [
          ':input[name="name_member_type"]' => ['value' => 'Membership Renewal'],
        ]
      ],
    );

    $form['member_category'] = array(
      '#type' => 'radios',
      '#title' => ('Select Membership Category:'),
      '#options' => array(
        'Student Member' => $this->t('Student Member'),
        'Associate Member' => $this->t('Associate Member'),
        'Full Member' => $this->t('Full Member'),
        'Corporate Member' => $this->t('Corporate Member'),
      ),
    );

    $form['member_prefix'] = array(
      '#type' => 'radios',
      '#prefix' => '<div class=" form-group">',
      '#suffix' => '</div>',
      '#title' => ('Refer to as'),
      '#options' => array(
        'Mr' => $this->t('Mr'),
        'Mrs' => $this->t('Mrs'),
        'Mx' => $this->t('Mx'),
      ),
    );

    $form['member_surname'] = array(
      '#type' => 'textfield',
      '#prefix' => '<div class="form-group">',
      '#suffix' => '</div>',
      '#title' => $this->t('Surname: '),
      '#required' => TRUE,
    );

    $form['member_givenname'] = array(
      '#type' => 'textfield',
      '#prefix' => '<div class="form-group">',
      '#suffix' => '</div>',
      '#title' => $this->t('Given Name  :'),
      '#required' => TRUE,
    );

    $form['member_address'] = array(
      '#type' => 'textfield',
      '#prefix' => '<div class="form-group">',
      '#suffix' => '</div>',
      '#title' => $this->t('Address: '),
      '#required' => TRUE,
    );

    $form['member_email'] = array(
      '#type' => 'email',
      '#prefix' => '<div class="d-flex flex-column form-group"> <p> Email: </p>',
      '#suffix' => '</div>',
      '#title' => $this->t('Email:'),
      '#title_display' => 'invisible',
      '#required' => TRUE,
    );

    $form['member_mobile'] = array(
      '#type' => 'tel',
      '#prefix' => '<div class="form-group"> <p> Mobile: </p>',
      '#suffix' => '</div>',
      '#title' => $this->t('Mobile: '),
      '#title_display' => 'invisible',
    );

    $form['member_telephone'] = array(
      '#type' => 'tel',
      '#prefix' => '<div class="form-group"> <p> Telephone: </p>',
      '#suffix' => '</div>',
      '#title' => $this->t('Office Telephone: '),
      '#title_display' => 'invisible',
    );

    $form['member_dob'] = array(
      '#type' => 'date',
      '#prefix' => '<div class="form-group"> <p> Date of Birth: </p>',
      '#suffix' => '</div>',
      '#title' => $this->t('Date of Birth: '),
      '#required' => TRUE,
      '#title_display' => 'invisible',
    );

    $form['member_pob'] = array(
      '#type' => 'textfield',
      '#prefix' => '<div class="form-group">',
      '#suffix' => '</div>',
      '#title' => $this->t('Place of Birth: '),
      '#required' => TRUE,
    );

    $form['member_nationality'] = array(
      '#type' => 'radios',
      '#title' => ('Nationality:'),
      '#prefix' => '<div class=" form-check p-2">',
      '#suffix' => '</div>',
      '#options' => array(
        'Bangladeshi' => $this->t('Bangladeshi'),
        'Other' => $this->t('Other'),
      ),
      '#attributes' => [
        'name' => 'field_member_nationality',
      ]
    );

    $form['member_othernationality'] = array(
      '#type' => 'textfield',
      '#required' => FALSE,
      '#prefix' => '<div class="p-2 align-self-end">',
      '#suffix' => '</div>',
      '#attributes' => [
        'id' => 'renewal_number',
      ],
      '#states' => [
        'visible' => [
          ':input[name="field_member_nationality"]' => ['value' => 'Other'],
        ]
      ]
    );

    $form['member_occupation'] = array(
      '#type' => 'textfield',
      '#prefix' => '<div class="form-group">',
      '#suffix' => '</div>',
      '#title' => $this->t('Occupation: '),
      '#required' => TRUE,
    );

    $form['member_motivation'] = array(
      '#type' => 'textarea',
      '#prefix' => '<div class=" form-group">',
      '#suffix' => '</div>',
      '#title' => $this->t('Motivation to Join: '),
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#prefix' => '<div class="my-2">',
      '#suffix' => '</div>',
      '#value' => $this->t('Register'),
      '#button_type' => 'primary btn btn-primary rounded p-2',
    );

    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field = $form_state->getValues();
    $contentType = 'member_registration';

    $node = Node::create(['type' => $contentType]);

    $node->title = "Member registration Info";
    $node->field_member_type = $field['member_type'];
    $node->field_member_renewal = $field['member_renewal'];
    $node->field_member_category = $field['member_category'];
    $node->field_member_prefix = $field['member_prefix'];
    $node->field_member_surname = $field['member_surname'];
    $node->field_member_givenname = $field['member_givenname'];
    $node->field_member_address = $field['member_address'];
    $node->field_member_email = $field['member_email'];
    $node->field_member_mobile = $field['member_mobile'];
    $node->field_member_telephone = $field['member_telephone'];
    $node->field_member_dob = $field['member_dob'];
    $node->field_member_pob = $field['member_pob'];

    if ($form_state->getValue('member_nationality') === "Bangladeshi") {
      $node->field_member_nationality = $field['member_nationality'];
    } else {
      $node->field_member_nationality = $field['member_othernationality'];
    }


    $node->field_member_occupation = $field['member_occupation'];
    $node->field_member_motivation = $field['member_motivation'];

    $node->save();

  }
}
