<?php
namespace Welcome\Controller;

use News\Model\NewsTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     Welcome\Controller
 * @name        IndexController
 * @version     0.1
 * @created     2015/12/18 13:03
 */
class IndexController extends AbstractActionController
{
    /**
     * @var NewsTable
     */
    protected $news_table;

    public function indexAction()
    {
        return new ViewModel(array(
            'news' => $this->getNewsTable()->top(),
        ));
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
