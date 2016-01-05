<?php
namespace News\Model;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * @author      Zeki Unal <zekiunal@gmail.com>
 * @description
 *
 * @package     User\Model
 * @name        UserTable
 * @version     0.1
 * @created     2015/12/19 20:57
 */
class UserTable
{
    /**
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * UserTable constructor.
     *
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
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
            $select->limit($count)->offset(0)->order('user_id DESC');
        });
        return $resultSet;
    }

    /**
     * @param int $id
     *
     * @return User
     * @throws \Exception
     */
    public function getUser($id)
    {
        $id = (int)$id;
        $rows = $this->tableGateway->select(array('user_id' => $id));
        $row = $rows->current();
        if (!$row) {
            throw new \Exception("Could not find row " . $id);
        }
        return $row;
    }

    /**
     * @param $token
     *
     * @return User
     * @throws \Exception
     */
    public function getByToken($token)
    {
        $rows = $this->tableGateway->select(array('token' => $token));
        $row = $rows->current();
        if (!$row) {
            throw new \Exception("Could not find row " . $token);
        }
        return $row;
    }

    /**
     * @param User $user
     *
     * @throws \Exception
     */
    public function saveUser(User $user)
    {
        $data = array(
            'username'     => $user->getUsername(),
            'email'        => $user->getEmail(),
            'display_name' => $user->getDisplayName(),
            'password'     => $user->getPassword(),
            'state'        => $user->getState(),
            'updated_at'   => date('Y-m-d H:i:s'),
            'token'        => $user->token,
        );

        $id = (int)$user->getId();
        if ($id == 0) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                if ($user->created_at = "0000-00-00 00:00:00") ;
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->tableGateway->update($data, array('user_id' => $id));
            } else {
                throw new \Exception('User id does not exist');
            }
        }
    }

    /**
     * @param int $id
     */
    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('user_id' => (int)$id));
    }
}
