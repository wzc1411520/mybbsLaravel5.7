<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 所有用户 ID 数组，如：[1,2,3,4]
        $user_ids = \App\Models\User::all()->pluck('id')->toArray();
        $categories = ['分享','教程','问答','公告',];
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);
        // 生成数据集合
        $links = factory(Category::class)->times(20)->make()
            ->each(function ($category, $index)
            use ($user_ids,$categories, $faker)
            {
                // 从用户 ID 数组中随机取出一个并赋值
                $userId = $faker->randomElement($user_ids);
                $name = $faker->randomElement($categories);
                $category->name = $name.$userId;
                $category->user_id = $userId;
            });;

        // 将数据集合转换为数组，并插入到数据库中
        Category::insert($links->toArray());
    }
}
