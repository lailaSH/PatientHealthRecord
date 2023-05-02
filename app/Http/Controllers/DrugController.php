<?php

namespace App\Http\Controllers;

use App\Models\DiseaseGroup;
use App\Models\DrugContent;
use App\Models\DrugScientificName;
use App\Models\DrugsDisease;
use App\Models\DrugsName;
use App\Models\groups;
use App\Models\GroupsDrug;
use App\Models\ScientificName;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class DrugController extends Controller
    {
    use GeneralTrait;
    public function ShowAllDrugs($start,$end)
    {
        $drugs= DrugContent::where('lang','ar')->whereBetween('docid', [$start, $end])
        ->get();
        if(!$drugs)
        {
        return $this->returnError('E100','لا يوجد ادوية');
        }else
        return $this->returnData('Drugs',$drugs);
    }
    public function ShowAllNameDrugs()
    {
        $drugs=DrugsName::all();
        if(!$drugs)
        {
            return $this->returnError('E100','لا يوجد ادوية');
        }else
        return $this->returnData('Drugs',$drugs);
    }
    public function ShowDrug($id)
    {
        $drugs= DrugContent::where('docid',$id)->first();
        $drug_scientific_name= DB::table('drug_scientific_name')->where('DrugID',$id)->get();
        $scientific_name=array();
        $scs=ScientificName::all();
        foreach($drug_scientific_name as $value){
            foreach($scs as $sc){
                if($sc->id==$value->ScientificID)
                    array_push($scientific_name,$sc->ScienceName);
            }
        }
        $groupsdrug= DB::table('groupsdrug')->where('DrugID',$id)->get();
        $groups=array();
        $gs=groups::all();
        foreach($groupsdrug as $value){
            foreach($gs as $g){
                if($g->id==$value->GroupID)
            array_push($groups,$g->groupName);
            }
        }
        $drug=array();
        array_push($drug,$drugs);
        array_push($drug,$scientific_name);
        array_push($drug,$groups);
            if(!$drugs)
            {
        return $this->returnError('E100','لا يوجد ادوية');
            }else
            return response()->json([
                'status' => true,
                'errNum' => "S000",
                'msg' => '',
                'infoDrug' => $drugs,
                'scientific_name'=>$scientific_name,
                'groups'=>$groups
            ]);
    }
    public function SearchDrug(Request $request)
    {
        $DrugContent= DrugContent::all();
        $drugs=array();
        foreach($DrugContent as $Drug){
            $check=strpos($Drug->marketName,$request->name);
            if($check!=null)
            array_push($drugs,$Drug);
        }
        if(count($drugs)==0)
        {
        return $this->returnError('E100','لا يوجد دواء بهذا الاسم');
        }
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => '',
            'infoDrugs' => $drugs,
        ]);    }
    public function ShowAllScientificName()
    {
        
        $scientific_name= ScientificName::all();
        if(!$scientific_name)
        {
        return $this->returnError('E100','Not Data Found');
        }else
        return $this->returnData('scientific_name',$scientific_name);
    }
    public function ShowAllGroups()
    {
        $groups= groups::all();
        if(!$groups)
        {
        return $this->returnError('E100','Not Data Found');
        }else
        return $this->returnData('groups',$groups);
    }

    public function AddScientificName(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [ 
        'ScienceName'=>'required',
        ]);  

        if ($validator->fails()) {  
            return $this->returnError('401',$validator->errors());
        }
        $temp=ScientificName::all()->last();
        $id=$temp->id+1;
        DB::table('scientific_name')->insert([
            'id'=>$id,
            'ScienceName'=>$request->ScienceName,
            ]);
        return $this->returnSuccessMessage('Insert '.$request->ScienceName.' Done ');
    }
    public function AddGroup(Request $request)
    {
            $validator = Validator::make($request->all(), 
            [ 
            'groupName'=>'required',
            ]);  

            if ($validator->fails()) {  
                return $this->returnError('401',$validator->errors());
            }
            $temp=groups::orderByDesc('docid')->first();
            $docid=$temp->docid +1;

            DB::table('groups')->insert([
                'docid'=>$docid,
                'lang'=>'ar',
                'groupName'=>$request->groupName,
                ]);
            return $this->returnSuccessMessage('Insert Done ');
    }
    public function StoreDrug(Request $request)
    {

        $validator = Validator::make($request->all(), 
        [ 
        'factName'=>'required',
        'factoryCountry' => 'required',
        'marketName' => 'required',
        'gauge' => 'required',
        'dosage' => 'required',
        'effects' => 'required',
        'interaction'=>'required',
        'warnings'=>'required',
        'form1'=>'required',
        'formThum'=>'required',
        'indication'=>'required',
        'antiIndication'=>'required'
        ]);  

        if ($validator->fails()) {  
            return $this->returnError('401',$validator->errors());
        }
        $temp=DrugContent::all()->last();
        $drug_id=$temp->docid+1;
        DB::table('drug_content')->insert([
            'docid'=>$drug_id,
            'factName'=>$request->factName,
            'factoryCountry'=>$request->factoryCountry,
            'lang'=>'ar',
            'marketName'=>$request->marketName,
            'gauge'=>$request->gauge,
            'dosage'=>$request->dosage,
            'effects'=>$request->effects,
            'interaction'=>$request->interaction,
            'warnings'=>$request->warnings,
            'form1'=>$request->form1,
            'formThum'=>$request->formThum,
            'indication'=>$request->indication,
            'antiIndication'=>$request->antiIndication
        ]);
        $array_ScientificName=array();
        $array_ScientificName=$request->ScientificName;
        $ScienceNameTemp=array();
        foreach ($array_ScientificName as $item)
        {
            $check=ScientificName::where('ScienceName','LIKE','%'.$item.'%')->first();
            if($check){
                array_push($ScienceNameTemp,$check->id);
            }
            else{
                DB::delete('DELETE FROM drug_content WHERE docid = ?', [$drug_id]);
                return     $this->returnError('E200','Scientific Name not Found !');
            }
        }
        $array_groups=array();
        $array_groups=$request->group;
        $groupTemp=array();
        foreach ($array_groups as $item)
        {
            $check=groups::where('groupName','LIKE','%'.$item.'%')->first();
            if($check){
                array_push($groupTemp,$check->id);
            }
            else{
                DB::delete('DELETE FROM drug_content WHERE docid = ?', [$drug_id]);
                return     $this->returnError('E200','group Name not Found !');
            }
        }
        foreach($ScienceNameTemp as $item){
            $temp=DrugScientificName::all()->last();
            $id=$temp->id+1;
            DB::table('drug_scientific_name')->insert([
                'id'=>$id,
                'ScientificID'=>$item,
                'DrugID'=>$drug_id
                ]);
        }
        foreach($groupTemp as $item){
            $temp=GroupsDrug::all()->last();
            $id=$temp->id+1;
            DB::table('groupsdrug')->insert([
                'id'=>$id,
                'GroupID'=>$item,
                'DrugID'=>$drug_id
                ]);
        }
       return $this->returnSuccessMessage("Insert Done !");

    }

    public function EditDrug($id)
    {
        $Drug = DrugContent::where('docid', $id)->first();
        if(!$Drug)
        {
        return $this->returnError('E100','Not Data Found');
        }else
        return $this->returnData('Drug',$Drug);

    }
    public function UpdateDrug(Request $request,$drug_id)
    {
        $validator = Validator::make($request->all(), 
        [ 
        'factName'=>'required',
        'factoryCountry' => 'required',
        'marketName' => 'required',
        'gauge' => 'required',
        'dosage' => 'required',
        'effects' => 'required',
        'interaction'=>'required',
        'warnings'=>'required',
        'form1'=>'required',
        'formThum'=>'required',
        'indication'=>'required',
        'antiIndication'=>'required'
        ]);  

        if ($validator->fails()) {  
            return $this->returnError('401',$validator->errors());
        }
        DB::table('drug_content')->update([
            'docid'=>$drug_id,
            'factName'=>$request->factName,
            'factoryCountry'=>$request->factoryCountry,
            'lang'=>'ar',
            'marketName'=>$request->marketName,
            'gauge'=>$request->gauge,
            'dosage'=>$request->dosage,
            'effects'=>$request->effects,
            'interaction'=>$request->interaction,
            'warnings'=>$request->warnings,
            'form1'=>$request->form1,
            'formThum'=>$request->formThum,
            'indication'=>$request->indication,
            'antiIndication'=>$request->antiIndication
        ]);
    }
    public function DestroyDrug($id)
    {
        $Drug = DrugContent::where('docid', $id)->first()->delete();    
    }
    public function interactions(Request $request)
    {
        $DrugDrugInteraction=array();
        $drugsName=$request->drugs_name;
        $drugs=array();
        foreach($drugsName as $dr)
        {
            $d=DrugContent::where('marketName','like','%'. $dr .'%')->first();
            if(!$d)
            {
                return $this->returnError('#E100','غير موجود'.$dr);
            }else
            array_push($drugs,$d->docid);
        }
        foreach($drugs as $drug1)
        {
            $drug_scientific_name= DB::table('drug_scientific_name')->where('DrugID',$drug1)->get();
            $scientific_name=array();
            $scs=ScientificName::all();
            foreach($drug_scientific_name as $value){
                foreach($scs as $sc){
                    if($sc->id==$value->ScientificID)
                        array_push($scientific_name,$sc->ScienceName);
                }
            }
            foreach($drugs as $drug2)
            {
                if($drug1!=$drug2)
                foreach($scientific_name as $Sc)
                {
                    $interactions=DrugContent::where('interaction','like','%'. $Sc .'%')->first();

                    if($interactions!=null)
                    {
                        $inter=DrugContent::where('docid',$drug2)->first();
                        array_push($DrugDrugInteraction,$inter->interaction);
                    }
                }
            }

        }
        return $this->returnData('DrugDrugInteraction',$DrugDrugInteraction);
    }
    public function Alternatives($drug_id){
        $AllDrugScientificName= DrugScientificName::all();
        $drug=DrugScientificName::where('DrugID',$drug_id)->get();
        $temp=array();
        foreach($drug as $value)
        {
            foreach($AllDrugScientificName as $DrugScientificName)
            {
                if($DrugScientificName->DrugID!=$drug_id)
                if($value->ScientificID==$DrugScientificName->ScientificID)
                {
                    array_push($temp,$DrugScientificName->DrugID);
                }
            }
        }
        $temp=array_unique($temp);
        $final=array();
        foreach($temp as $one)
        {
            $var=DrugContent::where('docid',$one)->first();
            array_push($final,$var);

        }
        if(count($final)==0){
            $drug=DrugContent::where('docid',$drug_id)->first();
            return $this->returnError('#E300',$drug->marketName.'لا يوجد بدائل للدواء :');
        }
        return $this->returnData('Drugs',$final);
    }

    public function ShowDrugOfGroup($groupID)
    {
        $GroupsDrugs=GroupsDrug::where('GroupID',$groupID)->get();
        $drugs=array();
        foreach($GroupsDrugs as $GroupsDrug)
        {
            $drs=DrugContent::where('docid',$GroupsDrug->DrugID)->get();
            foreach($drs as $dr)
            array_push($drugs,$dr);
        }
        return $this->returnData('Drugs',$drugs);
    }
 
}
