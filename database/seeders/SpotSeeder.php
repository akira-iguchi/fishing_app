<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('spots')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'spot_name' => 'かもめ大橋',
                'explanation' => 'かもめ大橋の下に位置する波止が釣り場である。内側はアジやクロダイ、
                    外側はハマチなどの青物が狙える。秋になると、外側から内側の順にタチウオが釣れ始める。',
                'address' => '〒559-0032 大阪府大阪市住之江区南港南5丁目',
                'latitude' => 34.6114,
                'longitude' => 135.419,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'spot_name' => 'シーサイドコスモ',
                'explanation' => 'コスモスクエア駅出入口のすぐ右方向に位置し、電車でのアクセスが良い。
                    根魚や青物、タチウオ、シーバスなど幅広い魚が釣れる。柵があり、お子様連れのご家族でも安心して釣りを行える。
                    一部、釣り禁止区域が存在する。',
                'address' => '〒559-0034 大阪府大阪市住之江区南港北1丁目32',
                'latitude' => 34.6438,
                'longitude' => 135.414,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'spot_name' => '貝塚人工島',
                'explanation' => 'その名の通り貝塚の人工島にある釣り場であり、規模がとても大きい。
                    大阪で特に人気の釣り場で、平日に満員になることもある。釣れる魚も多種多様でありながら、サイズにも大きく期待できる。
                    メインはテトラ帯だが、家族でも安心して釣りが行えるベランダ帯もある。',
                'address' => '597-0094 大阪府貝塚市二色南町15-2',
                'latitude' => 34.4502,
                'longitude' => 135.329,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 4,
                'user_id' => 1,
                'spot_name' => '貝塚人工島水路',
                'explanation' => '貝塚人工島と本州を結ぶ運河が釣り場である。
                    沖向きにある貝塚人工島に比べて人が少ないため、ゆったりと釣りを行える。柵があるため、家族でも安心して楽しむことができる。
                    クロダイやシーバス、タチウオなどが釣れる。底が砂地であるため、キスやメゴチなども狙える。',
                'address' => '〒597-0091 大阪府貝塚市二色南町',
                'latitude' => 34.4475,
                'longitude' => 135.339,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 5,
                'user_id' => 1,
                'spot_name' => 'とっとパーク小島',
                'explanation' => '道の駅にある、埋め立て土砂の積み出し桟橋を有効利用した海釣り公園。
                    先端付近は水深が約25mもあり、大型の真鯛やブリなどが狙える。手前付近では真鯛の実績が劣るが、雨や直射日光を遮ることができるためご家族での釣りに向いている。
                    1人当たりの釣りスペースが非常に狭いことや、常連客やスタッフの態度が良くないという意見が多いため、初心者には少し難しい釣り場である。',
                'address' => '599-0314 大阪府泉南郡岬町多奈川小島455-1',
                'latitude' => 34.3152,
                'longitude' => 135.1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 6,
                'user_id' => 1,
                'spot_name' => '深日港',
                'explanation' => '深日港駅から徒歩約5分で到着でき、電車でのアクセスが良い。釣具店もすぐ近くにある。
                    大阪でも数少ないアオリイカが釣れるスポットであり、大阪のアオリイカの実績は最高といえる。
                    他にも、テトラが敷き詰められているため穴釣りができたり、底が砂地であるためちょい投げでキスが狙える。',
                'address' => '〒599-0303 大阪府泉南郡岬町深日',
                'latitude' => 34.3187,
                'longitude' => 135.142,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ]);
    }
}
