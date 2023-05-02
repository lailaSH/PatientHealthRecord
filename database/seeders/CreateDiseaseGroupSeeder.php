<?php

namespace Database\Seeders;

use App\Models\DiseaseGroup;
use Illuminate\Database\Seeder;

class CreateDiseaseGroupSeeder extends Seeder
{
    public function run()
    {
        //////////////////////////////////////أمراض القلب
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'الشرايين التاجية',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب الناتج عن نقص التروية',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب الناتج عن ارتفاع ضغط الدم',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب الصمامي',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب الالتهابي',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب الناتج عن مرض أيضي في أحد أجهزة الجسم',
            'group' => 'A',
        ]);

        DiseaseGroup::create([
            'disease' => 'ضمور عضلة القلب',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب التضخمي',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب التوسعي',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب اللانظمي أو تليف البطين الأيمن للقلب',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب الاختناقي',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال عضلة القلب الإسفنجي',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'مرض نقص التروية',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'قصور القلب',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'ارتفاع ضغط الدم',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب بطانة القلب',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'تضخم القلب الالتهابي',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'تصلب الشرايين',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'ضعف عضلة القلب',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب التامور',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب عضلة القلب',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب عضلة الشغاف',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'تمدد الشريان الأورطي',
            'group' => 'A',
        ]);
        DiseaseGroup::create([
            'disease' => 'مرض صمامات القلب',
            'group' => 'A',
        ]);
        //الربو
        DiseaseGroup::create([
            'disease' => ' الربو',
            'group' => 'B',
        ]);
        //الصرع
        DiseaseGroup::create([
            'disease' => ' الصرع',
            'group' => 'C',
        ]);
        //السكري
        DiseaseGroup::create([
            'disease' => ' سكري الحمل',
            'group' => 'D',
        ]);
        DiseaseGroup::create([
            'disease' => ' السكري من النمط 1',
            'group' => 'D',
        ]);
        DiseaseGroup::create([
            'disease' => 'السكري من النمط 2',
            'group' => 'D',
        ]);
        //أمراض الكبد
        DiseaseGroup::create([
            'disease' => 'التهاب الأقنية الصفراوية الأولي (PBC)',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب الأقنية الصفراوية المصلب الأولي (PSC)',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'ركود صفراوي داخل الكبد تدريجي عائلي (PFIC)',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'متلازمة راي',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'داء ترسب الأصبغة الدموية',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب الكبد A',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب الكبد A',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب الكبد B',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب الكبد C',
            'group' => 'E',
        ]);

        DiseaseGroup::create([
            'disease' => 'التليف الكبدي',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'متلازمة الكبد الكلوي',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'سرطان الكبد',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'مرض الكبد الدهني غير الكحولي',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'سرطان القناة الصفراوية',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'البورفيريا الكبدية الحادة',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب الكبد المناعي الذاتي',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب الكبد الكحولي',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'تليف الكبد الكحولي',
            'group' => 'E',
        ]);
        DiseaseGroup::create([
            'disease' => 'الفشل الكبدي',
            'group' => 'E',
        ]);

        ////////إمراض الكلى
        DiseaseGroup::create([
            'disease' => 'القصور الكبدي الحاد',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'مرض الكلى المزمن',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب الكلى الخلالي',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'المتلازمة الكلوية',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'مرض التغير الأدنى',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'الالتهاب الكلوي',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'مرض التغير الأدنى',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'مرض فابري',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'الأورام الحبيبية المقطعية البؤرية',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'اعتلال الكلى السكري',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'سرطان الكلى',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب الكلية الذئبي',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'مرض الكلى متعدد الكيسات',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب كبيبات الكلى المزمن',
            'group' => 'F',
        ]);
        DiseaseGroup::create([
            'disease' => 'التهاب كبيبات الكلى الحاد',
            'group' => 'F',
        ]);
    }
}
