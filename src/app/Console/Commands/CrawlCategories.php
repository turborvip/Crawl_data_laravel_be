<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Categories;
use App\Models\News;

class CrawlCategories extends Command
{
    protected $signature = 'crawl:categories';

    protected $description = 'Crawl category in website dan tri....';

<<<<<<< HEAD
    /**
     * Execute the console command.
     */
=======
>>>>>>> ac5b240 (update)
    public function handle(): void
    {
        try {
            $url = 'https://dantri.com.vn/';

            $client = new Client();

            $categories = [];

            $id = 0;

            $parentId = 0;

            $crawler = $client->request('GET', $url);

            $structure = 'nav.menu.container.bg-wrap ol.menu-wrap.bg-wrap li';

            $crawler->filter($structure)->each(
                function ($node) use ($structure, &$categories, $client, &$id, &$parentId) {
                    if ($node->filter('a')->count() > 0) {
                        $id++;
                        $idParent = $parentId;
                        if($node->filter('ol.submenu')->count() <= 0) {
                            $categories[]=$this->addItemToArr($categories, $node, $id, $idParent);
                        }else{
                            $parentId = $id;
                            $categories[]=$this->addItemToArr($categories, $node, $id, $id);
                            $this->crawlCateChild($structure.' ol.submenu li',$node,$categories,$id,$parentId);
                        }
                    }
                }
            );

            foreach ($categories as $category) {
                $existingCategory = Categories::where('title', $category['title'])->first();
                $newCategory = new Categories();
                if (!$existingCategory) {
                    $newCategory->title = $category['title'];
                    $newCategory->description = null;
<<<<<<< HEAD
=======
                    $newCategory->url = $category['url'];
>>>>>>> ac5b240 (update)
                    $newCategory->status = $category['status'];
                    
                    // Check if the category has a parent
                    if (!empty($category['id_parent'])) {
                        $category = Categories::find($category['id_parent']);
<<<<<<< HEAD
                        $newCategory->parent_id = $category->id;
=======
                        if($category){
                            $newCategory->parent_id = $category->id;
                        }
>>>>>>> ac5b240 (update)
                    }
                    
                    $newCategory->save();
                }
            }
        }
        catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
<<<<<<< HEAD
        
=======
>>>>>>> ac5b240 (update)
    }

    protected function crawlCateChild(string $type, $crawler,$categories,$id,$parentId)
    {
        $idParent = $id;
        $child = $crawler->filter($type)->each(
            function ($node) use ($type, $categories, $crawler, &$id, $parentId){
                if ($node->filter('a')->count() > 0) {
                    $idParent = $parent_id;
                    $categories[]= $this->addItemToArr($categories, $node, $id, $idParent);
                    if($node->filter('ol.submenu')->count() > 0) {
                        $this->crawlCateChild($type.' ol.submenu li',$node,$categories,$id);
                    }
                }
            }
        );
    }

    protected function addItemToArr($arr,$node,$id,$idParent)
    {
<<<<<<< HEAD
        return [
            'id'=>$id,
            'id_parent'=>$idParent == 0 || $idParent == $id ? null : $idParent,
            'title'=>$node->filter('a')->text(),
            'url'=>$node->filter('a')->attr("href"),
            'status'=>true,
        ];
=======
        $url = $node->filter('a')->attr("href");
        if ($url != '/' && substr($url, 0, 1) == '/') {
            return [
                'id'=>$id,
                'id_parent'=>$idParent == 0 || $idParent == $id ? null : $idParent,
                'title'=>$node->filter('a')->text(),
                'url'=>$node->filter('a')->attr("href"),
                'status'=>true,
            ];
        }else{
            return  [
                'id'=>$id,
                'id_parent'=>$idParent == 0 || $idParent == $id ? null : $idParent,
                'title'=>$node->filter('a')->text(),
                'url'=>$node->filter('a')->attr("href"),
                'status'=>false,
            ];
        }
>>>>>>> ac5b240 (update)
    }

}
