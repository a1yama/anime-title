<?php
namespace Acme\App;

class Wikipedia
{
    /**
     * @param $client
     * @param $url
     * @return array
     */
    public static function run($client, $url)
    {

        $crawler = $client->request('GET', $url);
        $obj = $crawler->filter('table.wikitable tr td');

        $title_and_company = [];
        $title_and_company_key = 0;
        $title_key = 1;
        $company_key = 2;
        foreach ($obj as $key => $target) {
            if ($key == $title_key) {
                $title_and_company[$title_and_company_key]["title"] = $target->textContent;
                $title_key += 5;
            }
            if ($key == $company_key) {
                $title_and_company[$title_and_company_key]["company"] = $target->textContent;
                $company_key += 5;
                // 制作会社まで入ったらキーを+1する
                $title_and_company_key++;
            }
        }

        return $title_and_company;
    }
}