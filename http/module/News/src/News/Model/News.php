<?php
namespace News\Model;

use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     News\Model
 * @name        News
 * @version     0.1
 * @created     2015/12/18 19:08
 */
class News implements InputFilterAwareInterface
{
    /**
     * @var int
     */
    public $news_id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $photo;

    /**
     * @var string
     */
    public $highlight;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var InputFilter
     */
    protected $inputFilter;

    public function __construct($relations = null)
    {
        $this->relations = $relations;
    }

    /**
     * @param array $data
     */
    public function exchangeArray($data)
    {
        $this->news_id = (!empty($data['news_id'])) ? $data['news_id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->photo = (!empty($data['photo'])) ? $data['photo'] : null;
        $this->highlight = (!empty($data['highlight'])) ? $data['highlight'] : null;
        $this->text = (!empty($data['text'])) ? $data['text'] : null;
        $this->created_at = (!empty($data['created_at'])) ? $data['created_at'] : null;
        $this->updated_at = (!empty($data['updated_at'])) ? $data['updated_at'] : null;
        $this->user_id = (!empty($data['user_id'])) ? $data['user_id'] : null;
    }

    public function user()
    {
        return $this->relations['user']->select(array('user_id' => $this->user_id))->current();
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'news_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));


            $inputFilter->add(array(
                'name'       => 'title',
                'required'   => true,
                'filters'    => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ),
                    ),
                ),
            ));

            $fileInput = new FileInput('photo');
            $fileInput->setRequired(true);

            /**
             * You only need to define validators and filters as if only one file was being uploaded. All files
             * will be run through the same validators and filters automatically.
             */
            $fileInput->getValidatorChain()
                ->attachByName('filesize', array('max' => 2004800))
                ->attachByName('filemimetype', array('mimeType' => 'image/png,image/x-png,image/jpeg,image/jpg'))
                ->attachByName('fileimagesize', array('maxWidth' => 6000, 'maxHeight' => 6000));

            $inputFilter->add($fileInput);

            $inputFilter->add(array(
                'name'       => 'highlight',
                'required'   => true,
                'filters'    => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 100
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'       => 'text',
                'required'   => true,
                'filters'    => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 100,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
