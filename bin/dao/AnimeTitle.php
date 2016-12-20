<?php
namespace Dao;

class AnimeTitle extends Base
{
    public function insert($row)
    {
        $sql = "insert into anime_titles ( ";
        $sql .= "title, company, created_at, updated_at) value (";
        $sql .= "?, ?, NOW(), NOW())";

        $values[] = $row['title'];
        $values[] = $row['company'];

        return $this->db->execute($sql, $values);
    }
}