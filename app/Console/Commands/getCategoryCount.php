<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class getCategoryCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:catetory-topic-num';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计每个分类下的话题总数';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("同步开始！");
        $c = new Category();
        $category = $c->withCount('topics')->get();
        foreach ($category as $cate) {
            $cate->post_count = $cate->topics_count;
            $res = $cate->save();
            if($res){
                $this->info($cate->namwe."同步成功！");
            }else{
                $this->info("同步失败！");
            }

        }





    }
}
