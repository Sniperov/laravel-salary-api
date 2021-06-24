<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\Tax;
use App\Http\Requests\SalaryRequest;
use SalaryService;

class SalaryController extends Controller
{
    public function getCalculatedSalary(SalaryRequest $request)
    {
        $data = $request->validated();
        $invalidGroup = array_key_exists('invalid_group',$data) ? $data['invalid_group'] : null;

        return response()->json(
            SalaryService::calculateTaxes(
                $data['salary'], 
                $data['tax_ms'], 
                $data['is_pensioner'], 
                $data['is_invalid'],
                $invalidGroup
            )
        );
    }

    public function storeCalculatedSalary(SalaryRequest $request)
    {
        $data = $request->validated();
        $invalidGroup = array_key_exists('invalid_group',$data) ? $data['invalid_group'] : null;
        $calculated = SalaryService::calculateTaxes($data['salary'], $data['tax_ms'], $data['is_pensioner'], $data['is_invalid'], $invalidGroup);

        $data['salary'] = $calculated['finalSalary'];
        $salary = Salary::create($data);
        
        $taxes = $calculated['taxes'];
        $taxes['salary_id'] = $salary->id;

        $tax = Tax::create($taxes);

        return response()->json($calculated);
    }

}
