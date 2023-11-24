<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $permissions = [
            'الصفوف الدراسيه',
            'الوحدات',
            'الترم',
            'الدروس',
            'الطلاب',
            'الاشعارات',
            'اقسام الفيديوهات',
            'الفيديوهات الاساسية',
            'مصادر الفيديوهات',
            'الفيديوهات الاساسية ملفات ورقية',
            'الخطة الشهرية',
            'الاقتراحات',
            'امتحانات الاونلاين',
            'امتحانات اللايف',
            'امتحانات الورقية',
            'كل الامتحانات',
            'الاتصالات الهاتفية',
            'الشاشات الافتتاحيه',
            'سلايدر',
            'القاعات',
            'الاعدادات',
            'الدليل',
            'ملفات ورقية',
            'بنك الأسئلة',
            'اسئله الامتحانات',
            'الباقات',
            'المدن',
            'التعليقات',
            'الاعلانات',
            'الادمن',
            'الكوبونات',
            'الادوار و الصلاحيات',
        ];

        foreach ($permissions as $permission) {

            Permission::create(['name' => $permission , 'guard_name' => 'admin']);

        }

    }

}
