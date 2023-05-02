<?php

namespace App\Http\Controllers;

use App\Models\DiseaseGroup;
use App\Models\DrugContent;
use App\Models\DrugsDisease;
use App\Models\groups;
use App\Models\PatientDisease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PatientDoctor;
use App\Models\ScientificName;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;

class PatientDiseaseController extends BaseController
{
    use GeneralTrait;
    public function new_disease(Request $request, $health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $d = new PatientDisease();
        $d->health_record_id = $health_record_id;
        $record = DiseaseGroup::where('disease', $request->disease)
            ->first();
        if ($record != null) {
            $d->disease = $record->disease;
            $d->type = $record->group;
            $d->type2 = $request->type2;
            $d->save();
        } else {
            $d->disease = $request->disease;
            $d->type2 = $request->type2;
            $d->save();
        }
    }

    public function show_diseases($health_record_id)
    {
        //////////////////////////////////////
        $doctor = Auth::user();
        $pateint_doctor = PatientDoctor::where('user_id', $doctor->id)
            ->where('health_record_id', $health_record_id)
            ->first();
        if ($pateint_doctor == null || $pateint_doctor->statu != "yes")
            return $this->sendError('you are not allowed to enter', null);
        //////////////////////////////////////
        $diseases = PatientDisease::where('health_record_id', $health_record_id)->get();
        return $this->sendResponse($diseases, 'success');
    }
    public function interactionsDiseaseDrug($drugsName,$health_record_id)
    {
        $diseases = PatientDisease::where('health_record_id', $health_record_id)->get();
        $interactions=array();

        foreach($diseases as $d){
        if($d->type!=null)
        {
        $Disease=$d->type;
        $DrugsDisease=DrugsDisease::where('CodeDisease',$Disease)->get();
        $interactions=array();
        if($DrugsDisease){
        foreach($drugsName as $drug){
        $Drugs=DrugContent::where('marketName',$drug)->first();
        $drug_scientific_name= DB::table('drug_scientific_name')->where('DrugID',$Drugs->docid)->get();
        $scientific_name=array();
        $scs=ScientificName::all();
        foreach($drug_scientific_name as $value){
            foreach($scs as $sc){
                if($sc->id==$value->ScientificID)
                    array_push($scientific_name,$sc->ScienceName);

            }
        }
        $groupsdrug= DB::table('groupsdrug')->where('DrugID',$Drugs->docid)->get();
        $groups=array();
        $gs=groups::all();
        foreach($groupsdrug as $value){
            foreach($gs as $g){
                if($g->id==$value->GroupID)
            array_push($groups,$g->groupName);
            }
        }
    
        foreach($DrugsDisease as $DD){
            if($DD->NameDrug!=null){
            foreach($scientific_name as $ScDrug)
                {
                    $check=false;
                    if($ScDrug==$DD->NameDrug){
                        foreach($interactions as $IA){
                            if($IA==$DD->descrpition)
                            $check=true;
                        }
                        if(!$check)
                        array_push($interactions,$DD->descrpition);
                        }
                }
            }
            if($DD->NameGruop!=null)
            {
                foreach($groups as $g)
                {
                    $check=false;
                    if($g==$DD->NameGruop){
                        foreach($interactions as $IA){
                            if($IA==$DD->descrpition)
                            $check=true;
                        }
                        if(!$check)
                        array_push($interactions,$DD->descrpition);
                    }
                }
            }
            
        }
    }
        }
        }
        }
        if($interactions!=null)
        return $this->returnData('interactions',$interactions);
        else{
            return $this->returnError("23232",'لا يوجد تعارضات');
        }
    }

}
