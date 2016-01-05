<?php
namespace Account\Controller;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use News\Form\NewsForm;
use News\Model\News;
use News\Model\NewsTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     Account\Controller
 * @name        IndexController
 * @version     0.1
 * @created     2015/12/18 13:15
 */
class IndexController extends AbstractActionController
{
    /**
     * @var NewsTable
     */
    protected $news_table;

    /**
     * @var array
     */
    protected $image_sizes = array(
        array(
            750,
            150,
            ImageInterface::THUMBNAIL_OUTBOUND
        ),
        array(
            750,
            100,
            ImageInterface::THUMBNAIL_INSET
        ),
        array(
            750,
            300,
            ImageInterface::THUMBNAIL_INSET
        ),
        array(
            750,
            750,
            ImageInterface::THUMBNAIL_INSET
        )
    );

    /**
     * @var string
     */
    protected $image_folder = "content/images/upload";

    public function __construct()
    {
        @mkdir($this->image_folder, 0777, true);
    }

    public function indexAction()
    {
        $id = $this->zfcUserAuthentication()->getIdentity()->getId();

        return new ViewModel(array('news' => $this->getNewsTable()->getByUserId($id)));
    }

    public function postAction()
    {
        $user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $form = new NewsForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $imagine = $this->getServiceLocator()->get('image_service');
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );


            $news = new News();

            $form->setInputFilter($news->getInputFilter());
            $form->setData($data);

            if ($form->isValid()) {


                $file_name = rand(999999, 9999999).'.jpg';
                foreach ($this->image_sizes as $sizes) {
                    $mode = $sizes[2];
                    $folder = $this->image_folder . '/' . $sizes[0] . 'x' . $sizes[1];
                    @mkdir($this->image_folder . '/' . $sizes[0] . 'x' . $sizes[1], 0777, true);
                    $size = new Box($sizes[0], $sizes[1]);
                    $imagine->open($data['photo']['tmp_name'])
                        ->thumbnail($size, $mode)
                        ->save($folder . '/' . $file_name);
                }

                $news->exchangeArray($form->getData());

                $news->user_id = $user_id;
                $news->photo = $file_name ;
                $this->getNewsTable()->saveNews($news);

                return $this->redirect()->toRoute('account');
            }
        }
        return array('form' => $form, 'user_id' => (int)$user_id);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $news = $this->getNewsTable()->getNews($id);

        foreach ($this->image_sizes as $sizes) {
            $folder = $this->image_folder . '/' . $sizes[0] . 'x' . $sizes[1];
            @unlink($folder.'/'.$news->photo);
        }

        $this->getNewsTable()->deleteNews($id);
        return $this->redirect()->toRoute('account');
    }

    /**
     * @return NewsTable
     */
    public function getNewsTable()
    {
        if (!$this->news_table) {
            $this->news_table = $this->getServiceLocator()->get('News\Model\NewsTable');
        }
        return $this->news_table;
    }
}
