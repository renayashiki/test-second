<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contact;
use App\Models\Category;


class ContactFactory extends Factory
{

    protected $model = Contact::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Category::pluck('id') が実行時にCategoryモデルを参照できるように修正
        $categoryIds = Category::pluck('id')->toArray();

        // categoryIds が空の場合の防御的な処理
        if (empty($categoryIds)) {
            $categoryId = 1; // カテゴリIDのデフォルト値
        } else {
            $categoryId = $this->faker->randomElement($categoryIds);
        }
        
        return [
            'category_id' => $this->faker->randomElement($categoryIds),
            'first_name' => $this->faker->firstName($this->faker->randomElement(['male', 'female', 'other'])),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->numberBetween(1, 3), // 1:男性, 2:女性, 3:その他 を想定
            'email' => $this->faker->unique()->safeEmail(),
            'tel' => '090' . $this->faker->randomNumber(8, true), // 11桁のダミー電話番号
            'address' => $this->faker->address(),
            'building' => $this->faker->optional(0.5)->secondaryAddress(), // 50%の確率で建物名を生成
            'detail' => $this->faker->realText(100), // お問い合わせ内容
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
