<?php

namespace App\Models;

use App\Core\Model;
use RedBeanPHP\R;

/** Книга */
class Book extends Model
{
    private string $tableName = 'books';

    /** все книги.
     *
     * @param bool $isFullData полные данные?
     */
    public function get_all($isFullData = true): array
    {
        $sql = 'select 
            books.id as id,
            authors.name as author_name, 
            authors.surname as author_surname, 
            books.name as name, 
            genres.name as genre';
        if ($isFullData) {
            $sql .= ', 
                books.year as year, 
                books.description as description, 
                books.picture as picture   
            ';
        }
        $sql .= '
            from books 
            join authors on author_id = authors.id
            join genres on genre_id = genres.id
            order by author_surname
        ';

        $queryResult = R::getAll($sql);
        $books = [];
        foreach ($queryResult as $row) {
            $books[] = [
                'id' => (int) $row['id'],
                'author_name' => $row['author_name'].' '.$row['author_surname'],
                'name' => $row['name'],
                'genre' => $row['genre'],
            ];
            if ($isFullData) {
                $books['year'] = $row['year'];
                $books['description'] = $row['description'];
                $books['picture'] = $row['picture'];
            }
        }

        return $books;
    }

    // получить данные о книге
    public function get(int $id)
    {
        $bookRecod = R::load($this->tableName, $id);

        $book = [];
        $book['id'] = (int) $bookRecod->id;
        $book['name'] = $bookRecod->name;
        $book['picture'] = $bookRecod->picture;
        $book['year'] = (int) $bookRecod->year;
        $book['description'] = $bookRecod->description;
        // имя автора
        $authorRecord = R::load('authors', $bookRecod->author_id);
        $book['author_name'] = "$authorRecord->name $authorRecord->surname";
        // жанр
        $genreRecord = R::load('genres', $bookRecod->author_id);
        $book['genre'] = mb_strtoupper(mb_substr($genreRecord->name, 0, 1)).mb_substr($genreRecord->name, 1);

        return $book;
    }

    public function get_id(string $name, int $author_id)
    {
        $condition = 'name = :name and author_id = :author_id';
        $args = ['name' => $name, 'author_id' => $author_id];
        $rows = R::find($this->tableName, $condition, $args);

        if (empty($rows)) {
            return false;
        }

        foreach ($rows as $key => $value) {
            return $key;
        }
    }

    // добавить
    public function add(array $fields): int
    {
        $book = R::dispense($this->tableName);
        $book->name = $fields['name'];
        $book->author_id = $fields['author_id'];
        $book->genre_id = $fields['genre_id'];
        $book->year = $fields['year'];

        return R::store($book);
    }

    // удалить
    public function remove(string $id): bool
    {
        $book = R::load($this->tableName, $id);

        return R::trash($book) > 0;
    }

    // обновить
    public function update(int $id, array $fields): bool
    {
        $book = R::load($this->tableName, $id);
        $book->name = $fields['name'];
        $book->author_id = $fields['author_id'];
        $book->genre_id = $fields['genre_id'];
        $book->year = $fields['year'];

        return R::store($book) > 0;
    }
}
