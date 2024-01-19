<?php

namespace App\Models;

use App\Core\Model;

/** таблица комментариев */
class Comment extends Model
{
    // добавляет комментарий
    public function add($authorId, $articleId, $content)
    {
        $sql = 'insert into comments(author_id, article_id, content) values(:authorId, :articleId, :content)';
        $args = [':authorId' => $authorId, ':articleId' => $articleId, ':content' => $content];

        return $this->dbQuery->insert($sql, $args);
    }

    // удаляет комментарий
    public function remove($id)
    {
        $sql = 'delete from comments where id = :id';
        $args = [':id' => $id];

        return $this->dbQuery->delete($sql, $args);
    }

    // список комментариев статьи
    public function getCommentsOfArticle($articleId)
    {
        $sql = 'select comments.id as id, login, content, time from comments ';
        $sql .= 'join users on users.id = comments.author_id where article_id = :articleId order by time';
        $args = [':articleId' => $articleId];

        return $this->dbQuery->queryPrepared($sql, $args, false);
    }
}
