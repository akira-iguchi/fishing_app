<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FishingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fishing_types')->insert([
            [
                'id' => 1,
                'fishing_type_name' => 'サビキ釣り',
                'content' => '「サビキ」と呼ばれる６本以上の擬似餌バリで釣る方法。
                    仕掛けは釣具店で手軽に買える。上部または下部に「アミカゴ」を付け、アミエビを入れて魚を寄せる方法がメイン。
                    アジやイワシなど、主に小型の回遊魚を狙う。手軽な釣り方であり、釣りを始めるならこの釣り方がおすすめ。初心者からベテランの釣り師まで、誰でも楽しめる釣り方である。',
                'fishing_type_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/fishing_type/sabiki.png',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 2,
                'fishing_type_name' => 'ルアー釣り',
                'content' => '「ルアー」と呼ばれる擬似餌を使って魚を釣る方法。
                    その釣り方に多くの人が魅了され、あらゆる大会が存在するなど、最も人気の釣り方といっても過言ではない
                    魚を寄せることができないので、魚がいる場所、釣れる時間帯、魚の寄せ方などが必要でゲーム性がとても高い。
                    メタルジグやミノー、エギ、タイラバなどたくさんの種類がある。エサが必要ないので、いつでも手軽に釣りに行ける。',
                'fishing_type_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/fishing_type/rua-.jpg',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 3,
                'fishing_type_name' => 'ウキ釣り',
                'content' => '水面にウキを浮かべて、ウキの動き（アタリ）で魚を狙う釣り方。
                    ラインにウキをつけるというシンプルな仕掛けだからこそ、仕掛けの微妙な調整や技術の差によって同じ釣り場でも釣果が大きく変わってくる。
                    主な仕掛けは、ウキをウキゴムなどで固定する固定ウキ仕掛け、ウキを固定せず、ウキ止めを使ってタナ（魚のいる層）を調整する半誘導仕掛けがある。
                    また、ウキを全く固定しない全誘導仕掛けもある。',
                'fishing_type_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/fishing_type/uki.jpg',
                'crated_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 4,
                'fishing_type_name' => '投げ釣り',
                'content' => '投げ釣り専用の仕掛けを使って遠投し、遥か沖にいる魚を狙う釣り方。
                    投げの飛距離を競う大会が存在するなど、人気な釣り方でもある。砂浜（サーフ）が主な釣り場になる。
                    メインのターゲットはキスやカレイなど海底にいる魚である。遠投するにはコツがいるが、短めの竿などライトタックルを使うちょい投げ釣りは、
                    投げ釣りほど飛ばさないので初心者でも手軽に始められる。',
                'fishing_type_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/fishing_type/nage.jpg',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 5,
                'fishing_type_name' => 'カゴ釣り',
                'content' => 'カゴの付いた仕掛けを使った釣り方。カゴにはオキアミなどの撒き餌を入れる。
                    （ウキ→）カゴ→針の順に仕掛けがセットされ、ハリについたエサをカゴから出た撒き餌と同調させて食わせる。
                    仕掛けが長く、遠投するので、５m以上の竿や１００m以上のラインが必要になる。
                    アジやサバといった小型〜中型の魚から、マダイやブリといった大物の魚まで釣ることができる。',
                'fishing_type_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/fishing_type/kago.jpg',
                'crated_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 6,
                'fishing_type_name' => 'ぶっこみ釣り',
                'content' => 'オモリとハリだけのシンプルな仕掛けを使い、その名の通り「ぶっこむ」ように投げる釣り方。
                    シンプルな仕掛けで、投げ釣りほど遠投せず、投げた後は魚が食いつくまで待つ釣りなので、初心者でも簡単に始められる。
                    クロダイやシーバスといった大物から、マダイやキジハタなどの高級魚も釣れる。
                    魚を釣り上げるまで何が釣れるかわからないので、わくわく感を楽しめる。',
                'fishing_type_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/fishing_type/bukkomi.jpg',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 7,
                'fishing_type_name' => '胴付き仕掛け',
                'content' => 'サビキのような枝分かれした仕掛けを使う釣り方。多彩な魚を釣る「五目釣り」に適している。
                    主なエサはオキアミやイソメであるが、カワハギにはアサリが有効などエサによって釣れる魚も若干変わってくるので、
                    いろんなエサを持ってくると長く楽しめる。主に岸際を狙うので、長い竿だとやりづらくなる。小魚がよく釣れるが、たまに大物も混じってくる。',
                'fishing_type_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/fishing_type/dotuki.png',
                'crated_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id' => 8,
                'fishing_type_name' => '泳がせ釣り',
                'content' => '「飲ませ釣り」ともいう。生きている魚を生き餌として泳がせ、それを狙う魚を釣る方法。対象魚が大型なので、強い仕掛けが必要。
                    ウキをつけて仕掛けを浮かせると青物、仕掛けにオモリをつけて底に沈ませるとヒラメやキジハタなど、
                    高級魚が簡単に釣れるので、人気の釣り方である。ルアーや冷凍エサと違ってエサが生きているので、狙う魚の反応がとても良い。
                    デメリットとして、生きているエサが必要なので、エサの確保が困難。',
                'fishing_type_image' => 'https://osakafish.s3-us-west-1.amazonaws.com/fishing_type/oyogase.jpg',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ]);
    }
}
