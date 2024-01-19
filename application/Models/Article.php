<?php

namespace App\Models;

use App\Core\Model;

/** таблица статей */
class Article extends Model
{
    public function exists($fieldName, $value): bool
    {
        $sql = "select count(*) as count from articles where $fieldName = :field";
        $args = ['field' => $value];
        $isExisted = $this->dbQuery->queryPrepared($sql, $args)['count'] === 1;

        return $isExisted;
    }

    public function all(int $limit = null, int $offset = null)
    {
        $sql = 'select articles.id as id, login as author, title, summary, content from articles ';
        $sql .= 'join users on articles.author_id = users.id order by title ';
        if (!empty($limit) || !empty($offset)) {
            $sql .= "limit $limit offset $offset";
        }

        return $this->dbQuery->query($sql, false);
    }

    public function get(int $id): array
    {
        $sql = 'select articles.id as id, title, summary, content, login as author, time from articles ';
        $sql .= 'join users on users.id=articles.author_id where articles.id = :id';
        $args = [':id' => $id];
        $article = $this->dbQuery->queryPrepared($sql, $args);

        return $article;
    }

    public function add($author, $title, $summary, $content)
    {
        $sql = 'insert into articles(author_id, title, summary, content) values(:author, :title, :summary, :content)';
        $args = [':author' => $author, ':title' => $title, ':summary' => $summary, ':content' => $content];
        $id = $this->dbQuery->insert($sql, $args);

        return $id;
    }

    public function update($columns): bool
    {
        $sql_values = '';
        foreach ($columns as $key => $value) {
            $sql_values .= "$key = :$key, ";
        }
        $sql_values = mb_substr($sql_values, 0, mb_strlen($sql_values) - 2);
        $sql = "update articles set $sql_values where id = :id";

        return $this->dbQuery->update($sql, $columns);
    }

    public function remove($id): bool
    {
        $sql = 'delete from articles where id = :id';
        $args = [':id' => $id];

        return $this->dbQuery->delete($sql, $args);
    }

    /** число записей */
    public function count()
    {
        $sql = 'select count(*) as count from articles';

        return $this->dbQuery->query($sql)['count'];
    }
}
