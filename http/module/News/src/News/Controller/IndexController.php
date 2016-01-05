<?php
namespace News\Controller;

use DOMPDFModule\View\Model\PdfModel;
use News\Model\NewsTable;
use Zend\Feed\Writer\Feed;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\FeedModel;
use Zend\View\Model\ViewModel;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     News\Controller
 * @name        IndexController
 * @version     0.1
 * @created     2015/12/18 13:34
 */
class IndexController extends AbstractActionController
{
    /**
     * @var NewsTable
     */
    protected $news_table;

    public function indexAction()
    {
        return new ViewModel();
    }

    public function readAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('welcome', array(
                'action' => 'index'
            ));
        }
        $news = $this->getNewsTable()->getNews($id);

        return array(
            'id'   => $id,
            'news' => $news,
        );
    }

    public function pdfAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $download = (int)$this->params()->fromQuery('download', 0);
        if (!$id) {
            return $this->redirect()->toRoute('welcome', array(
                'action' => 'index'
            ));
        }

        $news = $this->getNewsTable()->getNews($id);

        $pdf = new PdfModel();

        if ($download === 1) {
            $pdf->setOption('filename', rand(111111, 99999999));
        }

        $pdf->setOption('paperSize', 'a4');


        $pdf->setVariables(array(
            'id'   => $id,
            'news' => $news,
        ));

        return $pdf;
    }

    /**
     * @return FeedModel
     */
    public function rssAction()
    {
        $feed = new Feed();
        $feed->setTitle('Sample Blog');
        $feed->setFeedLink($this->getUrl() . '/news/rss', 'atom');
        $feed->addAuthor(array(
            'name'  => 'Sample Blog Inc.',
            'email' => 'contact@' . $this->getRequest()->getUri()->getHost(),
            'uri'   => $this->getUrl(),
        ));
        $feed->setDescription('Description of this feed');
        $feed->setLink($this->getUrl());
        $feed->setDateModified(time());

        $news = $this->getNewsTable()->top();
        foreach ($news as $row) {
            $entry = $feed->createEntry();
            $entry->setTitle($row->title);
            $entry->setLink($this->getUrl() . '/news/read/' . $row->news_id);
            $entry->setContent($row->text);
            $entry->setDescription($row->highlight);
            $entry->setDateModified(strtotime($row->updated_at));
            $entry->setDateCreated(strtotime($row->created_at));
            $feed->addEntry($entry);
        }

        $feed->export('rss');

        $feed_model = new FeedModel();
        $feed_model->setFeed($feed);

        return $feed_model;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $uri = $this->getRequest()->getUri();
        $scheme = $uri->getScheme();
        $host = $uri->getHost();
        return sprintf('%s://%s', $scheme, $host);
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
