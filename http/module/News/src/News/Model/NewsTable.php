<?php
namespace News\Model;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     News\Model
 * @name        NewsTable
 * @version     0.1
 * @created     2015/12/19 16:36
 */
class NewsTable
{
    /**
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * @var TableGateway
     */
    protected $user_table_gateway;

    /**
     * NewsTable constructor.
     *
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway, $user_table_gateway = null)
    {
        $this->tableGateway = $tableGateway;
        $this->user_table_gateway = $user_table_gateway;
    }

    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * @param int $count
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function top($count = 10)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use ($count) {
            $select->limit($count)->offset(0)->order('news_id DESC');
        });
        return $resultSet;
    }

    /**
     * @param int $id
     *
     * @return array|\ArrayObject|null
     * @throws \Exception
     */
    public function getNews($id)
    {
        $id = (int)$id;
        $rows = $this->tableGateway->select(array('news_id' => $id));
        $row = $rows->current();
        if (!$row) {
            throw new \Exception("Could not find row " . $id);
        }
        return $row;
    }

    /**
     * @param int    $id
     * @param int $limit
     * @param int $offset
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getByUserId($id, $limit = 10, $offset = 0)
    {
        $id = (int)$id;
        $rows = $this->tableGateway->select(function (Select $select) use ($id, $limit, $offset) {
            $select->where(array('user_id' => $id))->limit($limit)->offset(0)->order('news_id DESC');
        });
        return $rows;
    }

    /**
     * @param News $news
     *
     * @throws \Exception
     */
    public function saveNews(News $news)
    {

        $data = array(
            'title'     => $news->title,
            'photo'     => $news->photo,
            'highlight' => $news->highlight,
            'text'      => $news->text,
            'user_id'   => $news->user_id,
            'updated_at'=> date('Y-m-d H:i:s')
        );

        $id = (int)$news->news_id;
        if ($id == 0) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getNews($id)) {
                $this->tableGateway->update($data, array('news_id' => $id));
            } else {
                throw new \Exception('News id does not exist');
            }
        }
    }

    /**
     * @param int $id
     */
    public function deleteNews($id)
    {
        $this->tableGateway->delete(array('news_id' => (int)$id));
    }
}
