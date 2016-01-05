<?php
namespace News\Form;

use Zend\Form\Form;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     News\Form
 * @name        NewsForm
 * @version     0.1
 * @created     2015/12/20 01:18
 */
class NewsForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('news');

        $this->add(array(
            'name' => 'news_id',
            'type' => 'Hidden',
        ));


        $this->add(array(
            'name'       => 'title',
            'type'       => 'Text',
            'options'    => array(
                'label' => 'Title',
            ),
            'attributes' => array(
                'class'       => 'form-control',
                'placeholder' => 'Title'
            )
        ));


        $this->add(array(
            'name'       => 'photo',
            'type'       => 'File',
            'options'    => array(
                'label' => 'Photo',
            ),
            'attributes' => array(
                'id'          => 'photo',
                'multiple'    => false,
                'class'       => 'form-control',
                'placeholder' => 'Photo'
            )
        ));

        $this->add(array(
            'name'       => 'highlight',
            'type'       => 'TextArea',
            'options'    => array(
                'label' => 'Short Description',
            ),
            'attributes' => array(
                'class'       => 'form-control ckeditor',
                'placeholder' => 'Short Description',
                "rows"        => 10
            )
        ));

        $this->add(array(
            'name'       => 'text',
            'type'       => 'TextArea',
            'options'    => array(
                'label' => 'Content',
            ),
            'attributes' => array(
                'class'       => 'form-control ckeditor',
                'placeholder' => 'Content',
                "rows"        => 10
            )
        ));

        $this->add(array(
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => array(
                'value' => 'Add New Post',
                'id'    => 'submitbutton',
                'class' => 'btn btn-success pull-right',
            ),
        ));
    }
}
