<?php
set_time_limit(300);
require_once "database.php";
$now_time = date("Y-m-d H:i:s", time());
$files = glob("C:\\Users\\ASUS\\Desktop\\company-single\\*.json");
$start = 7000;
$end = 7719;
exit();
$companies = [];
foreach ($files as $i => $file) {
    if ($i < $start) {
        continue;
    }
    if ($i > $end) {
        break;
    }
    $companies[$i] = json_decode(file_get_contents($file));
    $company_english_name = explode("\\", $file);
    $company_english_name = explode(".json", end($company_english_name))[0];
    $companies[$i]->english_name = $company_english_name;
}

for ($i = $start; $i <= $end; $i++) {
    $company_cat = select("company_cat", []);
    $companies_in_database = select("companies", [], ["english_name" => $companies[$i]->english_name]);

    if (count($companies_in_database) == 0) {
        $flag = false;
        foreach ($company_cat as $item) {
            if ($companies[$i]->category === $item->name) {
                insert("companies", [
                    "name" => $companies[$i]->name,
                    "english_name" => $companies[$i]->english_name,
                    "established" => $companies[$i]->release,
                    "company_cat_id" => $item->id,
                    "team_size" => $companies[$i]->size,
                    "website" => $companies[$i]->website,
                    "detail" => $companies[$i]->description,
                    "logo" => $companies[$i]->logo,
                    "large_logo" => $companies[$i]->large_logo,
                    "created_at" => $now_time
                ]);
                $flag = true;
                break;
            }
        }
        if ($flag == false) {
            $added_cat_id = insert("company_cat", ["name" => $companies[$i]->category, "created_at" => $now_time]);
            insert("companies", [
                "name" => $companies[$i]->name,
                "english_name" => $companies[$i]->english_name,
                "established" => $companies[$i]->release,
                "company_cat_id" => $added_cat_id,
                "team_size" => $companies[$i]->size,
                "website" => $companies[$i]->website,
                "detail" => $companies[$i]->description,
                "logo" => $companies[$i]->logo,
                "large_logo" => $companies[$i]->large_logo,
                "created_at" => $now_time
            ]);
        }
    } else {
        var_dump($companies_in_database[0]->id);
        $flag = false;
        foreach ($company_cat as $item) {
            if ($companies[$i]->category === $item->name) {
                update("companies", end($companies_in_database)->id,
                    [
                        "name",
                        "english_name",
                        "established",
                        "company_cat_id",
                        "team_size",
                        "website",
                        "detail",
                        "logo",
                        "large_logo",
                        "updated_at"],
                    [
                        $companies[$i]->name,
                        $companies[$i]->english_name,
                        $companies[$i]->release,
                        $item->id,
                        $companies[$i]->size,
                        $companies[$i]->website,
                        $companies[$i]->description,
                        $companies[$i]->large_logo,
                        $companies[$i]->large_logo,
                        $now_time
                    ]
                );
                $flag = true;
                break;
            }
        }
        if ($flag == false) {
            $added_cat_id = insert("company_cat", ["name" => $companies[$i]->category, "created_at" => $now_time]);
            update("companies", end($companies_in_database)->id,
                [
                    "name",
                    "english_name",
                    "established",
                    "company_cat_id",
                    "team_size",
                    "website",
                    "detail",
                    "logo",
                    "large_logo",
                    "updated_at"],
                [
                    $companies[$i]->name,
                    $companies[$i]->english_name,
                    $companies[$i]->release,
                    $added_cat_id,
                    $companies[$i]->size,
                    $companies[$i]->website,
                    $companies[$i]->description,
                    $companies[$i]->large_logo,
                    $companies[$i]->large_logo,
                    $now_time
                ]
            );
        }
    }
}
/*
 * var_dump($companies_in_database[0]->company_cat_id);
        exit();
        /////////////////////////////////////////mishe tamoom beshe
//        $flag = false;
//        foreach ($company_cat as $item) {
//            if ($companies[$i]->category === $item->name) {
//                update("companies", end($companies_in_database)->id,
//                    ["name",
//                        "established",
//                        "company_cat_id",
//                        "team_size",
//                        "website",
//                        "detail",
//                        "logo",
//                        "large_logo",
//                        "updated_at"],
//                    [
//                        $companies[$i]->name,
//                        $companies[$i]->release,
//                        $item->id,
//                        $companies[$i]->size,
//                        $companies[$i]->website,
//                        $companies[$i]->description,
//                        $companies[$i]->large_logo,
//                        $companies[$i]->large_logo,
//                        $now_time
//                    ]
//                );
//                $flag = true;
//                break;
//            }
//        }
//        if ($flag == false) {
//            $added_cat_id = insert("company_cat", ["name" => $companies[$i]->category, "created_at" => $now_time]);
//            update("companies", $companies_in_database[0]->id,
//                ["name",
//                    "established",
//                    "company_cat_id",
//                    "team_size",
//                    "website",
//                    "detail",
//                    "logo",
//                    "large_logo",
//                    "updated_at"],
//                [
//                    $companies[$i]->name,
//                    $companies[$i]->release,
//                    $item->id,
//                    $companies[$i]->size,
//                    $companies[$i]->website,
//                    $companies[$i]->description,
//                    $companies[$i]->large_logo,
//                    $companies[$i]->large_logo,
//                    $now_time
//                ]
//            );
//        }
 *
 * */
