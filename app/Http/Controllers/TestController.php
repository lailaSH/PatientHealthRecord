<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TestController extends BaseController
{

    public function Specify_Test($health_record_id)
    {
        $result = "";
        $health_record = HealthRecord::find($health_record_id);
        $info = $health_record->PatientPersonalInfo;
        $age = Carbon::parse($info->date_of_birth)->diff(Carbon::now())->y;
        if ($age > 18) {
            $result = "
               قد تحتاج إلى الذهاب مرة أو مرتين إلى طبيب الأسنان في السنة

               " . "
            يجب فحص ضغط الدم كل عامين على الأقل بدءًا من سن 18 عامًاإذا كان مرتفعًا ، فقد تضطر إلى فحصه أكثر من مرةاسأل طبيبك عن عدد المرات 

            " . "
             فحص بشرة الجسم بالكامل لفحص الشامات المشبوهة أو الآفات الجلدية )
             (خاصة في حال وجود حالة إصابة بسرطان الجلد في العائلة

             ";
            if ($info->gender == "f")
                $result = $result . "
           ( فحص أورام الثدي والحوض )
            ";
        }
        if ($age > 20) {
            $result = $result . "
            يجب عليك فحص الكوليسترول كل 4-6 سنوات إذا كان عمرك أكبر من 20 عامًا،  من المرجح أن يوصي طبيبك بإجراء فحص أكثر تكرارًا إذا كنت : بالغًا كبيرًا ، أو تعاني من زيادة الوزن ، أو كان لديك تاريخ عائلي للإصابة بأمراض القلب أو ارتفاع الكوليسترول أو داء السكري أو  تاريخ شخصي من ارتفاع نسبة الكوليسترول       
            ";
            if ($info->gender == "f")
                $result = $result . "
     
                ( فحص سرطان عنق الرحم )
        ";
        }
        if ($age > 45) {
            $result = $result . " إ اختبار بسيط يقيس مستوى السكر  في الدم. يجب أن تبدأ الاختبار الروتيني بمجرد بلوغك سن  45. قد يقترح طبيبك إجراؤه في وقت أقرب إذا كنت : تعاني من زيادة الوزن أو لديك ارتفاع في نسبة الكوليسترول أو ارتفاع ضغط الدم أو كان لديك سكري الحمل"
                .
                " اختبار لفحص سرطان المستقيم القولون ";

            if ($info->gender == "m")
                $result = $result . "
                تبدأ فحوصات البروستاتا في سن الخمسين في الحالات الطبيعة
                
                ";
        }
        if ($age > 65)
            $result = $result . " ابتداءً من سن 65 ، يجب أن يخضع الرجال والنساء لدراسة كثافة العظام كل 2 - 5 سنوات";
        return $result;
    }
}
