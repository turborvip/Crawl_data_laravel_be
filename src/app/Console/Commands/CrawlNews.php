<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Categories;
use App\Models\News;

class CrawlNews extends Command
{
    protected $signature = 'crawl:news';
    protected $description = 'Crawl news with categories';

    protected $domain = 'https://dantri.com.vn/';


    public function handle(): void
    {
        $domain = 'https://dantri.com.vn';
        $categories = Categories::where('status', true)->get(['id','url']);
        $news = [];
        $client = new Client();

        foreach ($categories as $category) {
            $url = $domain.$category->url;
            // crawl data follow category
            $this->crawlNewsInCate($url, $news,$client,$category->id);
        }

        foreach ($news as $item) {
            $newsExists = News::where('caption', $item['caption'])->exists();
            if ($newsExists) {
                // News is exist in database
                
                $newsDB = News::where('caption', $item['caption'])->get(['id']);
                // Add referent with news and categories in news_categories
                foreach ($newsDB as $news) {
                    $news->categories()->syncWithoutDetaching([$item['idCate']]);
                }
                
            } else {
                // News is not exist in database
                $newsDB = new News();
                $newsDB->caption = $item['caption'];
                $newsDB->image = $item['image'];
                $newsDB->description = $item['description'];
                $newsDB->content = $item['content'];
                $newsDB->author = $item['author'];
                $newsDB->save();

                // Assign categories to news
                $newsDB->categories()->attach($item['idCate']);
            }
        }
    }

    protected function crawlNewsInCate(string $link, &$news,$client,$idCate)
    {
        $crawler = $client->request('GET', $link);
        $structure = 'article.article-item';
        $crawler->filter($structure)->each(
            function ($node) use (&$news, $link,$client,$idCate) {
                if ($node->filter('div.article-thumb a img')->count() > 0 && $node->filter('div.article-thumb a')->count() > 0) {
                    $thumbnail = $node->filter('div.article-thumb a img')->first()->attr('src');
                    $url = $node->filter('div.article-thumb a')->first()->attr('href'); 
                    // Because get data in list data of Category so can't get detail news -->crawlContent to get detail content
                    $this->crawlContent($link.$url, $thumbnail, $news,$client,$idCate);
                }
            }
        );
    }

    protected function crawlContent(string $url, $thumbnail, &$news,$client,$idCate)
    {
        $crawler = $client->request('GET', $url);
        $structure = 'article.singular-container';

        $crawler->filter($structure)->each(function ($node) use (&$news, $thumbnail,$idCate) {
            $this->addNews($news,$node, $thumbnail,$idCate);
        });
    }

    protected function addNews(&$news,$node, $thumbnail,$idCate)
    {
        $news[] = [
            'caption' => $this->crawlData('h1.title-page.detail',$node),
            'image' => $thumbnail,
            'description' => $this->crawlData('h2.singular-sapo',$node),
            'content' => $node->filter('div.singular-content')->html(),
            'author' => $this->crawlData('div.author-wrap div.author-name',$node),
            'idCate'=>$idCate
        ];
    }

    protected function crawlData(string $type, $crawler)
    {
        $result = $crawler->filter($type)->each(function ($node) {
            return $node->text();
        });

        if (!empty($result)) {
            return $result[0];
        }
        return null;
    }
}


    