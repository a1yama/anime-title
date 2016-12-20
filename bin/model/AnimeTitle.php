<?php
namespace Model;

require __DIR__ . "/../bootstrap.php";

class AnimeTitle
{
    /**
     * @param $title_and_company
     */
    public static function insert($title_and_company)
    {
        $daoAnimeTitle = new \Dao\AnimeTitle(Container::getDbal());
        $daoAnimeTitle->begin();
        foreach ($title_and_company as $data) {
            $daoAnimeTitle->insert($data);
        }
        $daoAnimeTitle->commit();

    }
}