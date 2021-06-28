<?
namespace App\Repository;

interface PostRepository 
{
    public function getAllUsers();

    public function getUserById($id);

    public function createOrUpdate( $id = null, $collection = [] );
    public function create();

    public function deleteUser($id);
}
